<?php

namespace backend\modules\blog\entities;

use yii\db\ActiveRecord;
use common\models\Lang;
use backend\widgets\langwidget\LangWidget;
use yii\web\NotFoundHttpException;
use backend\modules\blog\entities\Category;

class CategoryLang extends ActiveRecord 
{
    private $currentLang;
    public $languageData;

    public static function tableName() 
    {
        return '{{%blog_category_lang}}';
    }

    public function rules() 
    {
        return [
            [['category_id', 'lang_id', 'title'], 'required', 'message' => 'Поле не может быть пустым:'],
            ['title', 'string', 'message' => 'Поле должно быть строкой:'],
            ['lang_id' , 'exist', 'targetClass' => Lang::class, 'targetAttribute' => ['lang_id' => 'id'], 'message' => 'Ошибка связи с таблицей языков :'],
            ['category_id' , 'exist', 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id'], 'message' => 'Ошибка связи с таблицей категорий :']
        ];
    }

    public function attributeLabels() 
    {
        return [
            'id' => 'ID',
            'category_id' => 'Продукт',
            'lang_id' => 'Язык',
            'title' => 'Название'
        ];
    }

    public function getLang() 
    {
        return $this->hasOne(Lang::className(), ['id' => 'lang_id']);
    }

    public function saveLang($data,$baseId)
    {
        $langAlias = LangWidget::getActiveLanguageData(['alias','id']);
        foreach($langAlias as $oneLang){
            $this->currentLang = $oneLang['alias'];
            $currentData = $this->existLangKey($data);

            $model = new CategoryLang();
            $model->category_id = $baseId;
            $model->lang_id = $oneLang['id'];
            $model->title = $currentData['title'];
            $model->save();
        }
    }

    public function updateLang($data,$baseId)
    {
        $langAlias = LangWidget::getActiveLanguageData(['alias','id']);
        $model = CategoryLang::findAll(['category_id' => $baseId]);
        //$indexKey - default key of index array
        foreach($langAlias as $indexKey => $oneLang){
            $this->currentLang = $oneLang['alias'];
            $currentData = $this->existLangKey($data);

            $model[$indexKey]->title = $currentData['title'];
            $model[$indexKey]->update();
        }
    }

    private function existLangKey($data)
    {
        if(isset($data[$this->currentLang])){
            return $data[$this->currentLang];
        }

        throw new NotFoundHttpException('index ' . "'" . $this->currentLang . "'" . ' in coming array');
    }
}