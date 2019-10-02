<?php

namespace backend\modules\content\models;

use Yii;
use backend\widgets\langwidget\LangWidget;
use common\models\Lang;
/**
 * This is the model class for table "page_meta".
 *
 * @property int $id
 * @property int $page_id
 * @property string $title
 * @property string $description
 * @property string $keywords
 *
 * @property Page $page
 */
class PageMetaLang extends \yii\db\ActiveRecord 
{

    private $currentLang;
    public $languageData;
    /**
     * {@inheritdoc}
     */
    public static function tableName() 
    {
        return 'page_meta_lang';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() 
    {
        return [
            [['title', 'lang_id', 'meta_id', 'description', 'keywords'], 'required', 'message' => 'Необходимо заполнить '],
            [['meta_id','lang_id'], 'integer'],
            [['title', 'description', 'keywords'], 'string', 'max' => 255],
            ['meta_id' , 'exist', 'skipOnError' => true, 'targetClass' => PageMeta::className(), 'targetAttribute' => ['meta_id' => 'id']],
            ['lang_id' , 'exist', 'targetClass' => Lang::class, 'targetAttribute' => ['lang_id' => 'id'], 'message' => 'Ошибка связи с таблицей языков :']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() 
    {
        return [
            'id' => 'ID',
            'page_id' => 'Page ID',
            'title' => 'Title',
            'description' => 'Description',
            'keywords' => 'Keywords',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage() 
    {
        return $this->hasOne(Page::className(), ['id' => 'page_id']);
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

            $model = new PageMetaLang();
            $model->meta_id = $baseId;
            $model->lang_id = $oneLang['id'];
            $model->title = $currentData['title'];
            $model->description = $currentData['description'];
            $model->keywords = $currentData['keywords'];
            $model->save();
        }
    }

    public function updateLang($data,$baseId)
    {
        $langAlias = LangWidget::getActiveLanguageData(['alias','id']);
        $model = PageMetaLang::findAll(['meta_id' => $baseId]);
        //$indexKey - default key of index array
        foreach($langAlias as $indexKey => $oneLang){
            $this->currentLang = $oneLang['alias'];
            $currentData = $this->existLangKey($data);

            $model[$indexKey]->title = $currentData['title'];
            $model[$indexKey]->description = $currentData['description'];
            $model[$indexKey]->keywords = $currentData['keywords'];
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
