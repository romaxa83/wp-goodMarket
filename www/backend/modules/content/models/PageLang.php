<?php

namespace backend\modules\content\models;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsTrait;
use pendalf89\filemanager\behaviors\MediafileBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use backend\widgets\langwidget\LangWidget;
use common\models\Lang;

class PageLang extends ActiveRecord {

    use SaveRelationsTrait;

    private $currentLang;
    public $languageData;
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'saveRelations' => [
                'class' => SaveRelationsBehavior::className(),
                'relations' => [
                    'pageMetas',
                    'pageText',
                    'slugManager' => ['cascadeDelete' => true]
                ],
            ],
        ];
    }

    public static function tableName() {
        return 'page_lang';
    }

    public function transactions() {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['title', 'lang_id', 'page_id'], 'required', 'message' => 'Необходимо заполнить '],
            [['lang_id','page_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            ['lang_id' , 'exist', 'targetClass' => Lang::class, 'targetAttribute' => ['lang_id' => 'id'], 'message' => 'Ошибка связи с таблицей языков :']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'title' => 'Название страницы',
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

            $model = new PageLang();
            $model->page_id = $baseId;
            $model->lang_id = $oneLang['id'];
            $model->title = $currentData['title'];
            $model->save();
        }
    }

    public function updateLang($data,$baseId)
    {
        $langAlias = LangWidget::getActiveLanguageData(['alias','id']);
        $model = PageLang::findAll(['page_id' => $baseId]);
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
