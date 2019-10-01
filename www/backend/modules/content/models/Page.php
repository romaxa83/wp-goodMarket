<?php

namespace backend\modules\content\models;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsTrait;
use pendalf89\filemanager\behaviors\MediafileBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use common\models\Lang;

/**
 * This is the model class for table "page".
 *
 * @property int $id
 * @property string $title
 * @property int $slug_id
 * @property string $lang
 * @property int $status
 * @property string $creation_date
 * @property string $modification_date
 *
 * @property PageText[] $pageText
 * @property PageMeta[] $pageMetas
 * @property SlugManager[] $slugManager
 */
class Page extends ActiveRecord {

    use SaveRelationsTrait;

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['creation_date', 'modification_date'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['modification_date'],
                ],
                'value' => date('Y-m-d')
            ],
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
        return 'page';
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
            [['slug_id'], 'required'],
            [['status', 'slug_id'], 'integer'],
            [['creation_date', 'modification_date'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'alias' => 'Псевдоним',
            'status' => 'Статус',
            'creation_date' => 'Дата создания',
            'modification_date' => 'Дата изменения',
        ];
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        if(is_null($this->pageMetas)) return false;
        return parent::save($runValidation, $attributeNames);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageText() {
        return $this->hasMany(PageText::className(), ['page_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageMetas() {
        return $this->hasOne(PageMeta::className(), ['page_id' => 'id']);
    }

    public function getSlugManager() {
        return $this->hasOne(SlugManager::className(), ['id' => 'slug_id']);
    }

    public function getOneLang()
    {   
        return $this->hasOne(PageLang::class, ['page_id' => 'id']);
    }

    public function getManyLang()
    {   
        return $this->hasMany(PageLang::class, ['page_id' => 'id']);
    }

    public function getAliasLang()
    {   
        return $this->hasMany(Lang::class, ['id' => 'lang_id'])->via('manyLang');
    }
}
