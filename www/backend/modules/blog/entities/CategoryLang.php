<?php

namespace backend\modules\blog\entities;

use yii\db\ActiveRecord;
use common\models\Lang;
use backend\widgets\langwidget\LangWidget;
use yii\web\NotFoundHttpException;

class CategoryLang extends ActiveRecord 
{
    private $currentLang;
    // private $title;
    // private $category_id;
    // private $lang_id;

    public static function tableName() 
    {
        return '{{%blog_category_lang}}';
    }

    public function rules() 
    {
        return [
            [['category_id', 'lang_id', 'title'], 'required'],
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

            $model = new self;
            $model->category_id = $baseId;
            $model->lang_id = $oneLang['id'];
            $model->title = $currentData['title'];
            $model->save();
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