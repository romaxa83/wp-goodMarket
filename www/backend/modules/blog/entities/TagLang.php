<?php

namespace backend\modules\blog\entities;

use yii\db\ActiveRecord;
use common\models\Lang;
use backend\widgets\langwidget\LangWidget;
use yii\web\NotFoundHttpException;

class TagLang extends ActiveRecord 
{
    private $currentLang;
    public $languageData;

    public static function tableName() 
    {
        return '{{%blog_tag_lang}}';
    }

    public function rules() 
    {
        return [
            [['tag_id', 'lang_id', 'title'], 'required', 'message' => 'Поле не может быть пустым:'],
            ['title', 'string', 'message' => 'Поле должно быть строкой:'],
            ['lang_id' , 'exist', 'targetClass' => Lang::class, 'targetAttribute' => ['lang_id' => 'id'], 'message' => 'Ошибка связи с таблицей языков '],
            ['tag_id' , 'exist', 'targetClass' => Tag::class, 'targetAttribute' => ['tag_id' => 'id'], 'message' => 'Ошибка связи с таблицей тегов ']
        ];
    }

    public function attributeLabels() 
    {
        return [
            'id' => 'ID',
            'tag_id' => 'Продукт',
            'lang_id' => 'Язык',
            'title' => 'Название'
        ];
    }

    public function saveLang($data,$baseId)
    {
        $langAlias = LangWidget::getActiveLanguageData(['alias','id']);
        foreach($langAlias as $oneLang){
            $this->currentLang = $oneLang['alias'];
            $currentData = $this->existLangKey($data);

            $model = new TagLang();
            $model->tag_id = $baseId;
            $model->lang_id = $oneLang['id'];
            $model->title = $currentData['title'];
            $model->save();
        }
    }

    public function updateLang($data,$baseId)
    {
        $langAlias = LangWidget::getActiveLanguageData(['alias','id']);
        $model = TagLang::findAll(['tag_id' => $baseId]);
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

    public function getLang() 
    {
        return $this->hasOne(Lang::className(), ['id' => 'lang_id']);
    }

    public function getBaseTag() 
    {
        return $this->hasOne(Tag::className(), ['id' => 'tag_id']);
    }
}