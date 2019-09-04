<?php

namespace backend\modules\blog\entities;

use yii\db\ActiveRecord;
use common\models\Lang;
use backend\widgets\langwidget\LangWidget;
use yii\web\NotFoundHttpException;

class PostLang extends ActiveRecord 
{
    private $currentLang;
    public $languageData;

    public static function tableName() 
    {
        return '{{%blog_post_lang}}';
    }

    public function rules() 
    {
        return [
            [['post_id', 'lang_id', 'title','description','content'], 'required', 'message' => 'Поле не может быть пустым:'],
            [['title','description','content'], 'string', 'message' => 'Поле должно быть строкой:'],
            ['lang_id' , 'exist', 'targetClass' => Lang::class, 'targetAttribute' => ['lang_id' => 'id'], 'message' => 'Ошибка связи с таблицей языков '],
            ['post_id' , 'exist', 'targetClass' => Post::class, 'targetAttribute' => ['post_id' => 'id'], 'message' => 'Ошибка связи с таблицей тегов ']
        ];
    }

    public function attributeLabels() 
    {
        return [
            'id' => 'ID',
            'post_id' => 'Продукт',
            'lang_id' => 'Язык',
            'title' => 'Название блога',
            'description' => 'Описание',
            'content' => 'Контент'
        ];
    }

    public function saveLang($data,$baseId)
    {
        $langAlias = LangWidget::getActiveLanguageData(['alias','id']);
        foreach($langAlias as $oneLang){
            $this->currentLang = $oneLang['alias'];
            $currentData = $this->existLangKey($data);

            $model = new PostLang();
            $model->post_id = $baseId;
            $model->lang_id = $oneLang['id'];
            $model->title = $currentData['title'];
            $model->description = $currentData['description'];
            $model->content = $currentData['content'];
            $model->save();
        }
    }

    public function updateLang($data,$baseId)
    {
        $langAlias = LangWidget::getActiveLanguageData(['alias','id']);
        $model = PostLang::findAll(['post_id' => $baseId]);
        //$indexKey - default key of index array
        foreach($langAlias as $indexKey => $oneLang){
            $this->currentLang = $oneLang['alias'];
            $currentData = $this->existLangKey($data);

            $model[$indexKey]->title = $currentData['title'];
            $model[$indexKey]->description = $currentData['description'];
            $model[$indexKey]->content = $currentData['content'];
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
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }
}