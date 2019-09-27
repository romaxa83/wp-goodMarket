<?php

namespace backend\modules\content\models;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsTrait;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "channel".
 *
 * @property int $id
 * @property string $title
 * @property int $route_id
 * @property int $seo_id
 * @property int $content_id
 * @property string $record_structure
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ChannelRecord[] $channelRecords
 * @property SeoData $seoData
 * @property SlugManager $slugManager
 * @property ChannelContent $channelContent
 * @property ChannelRecordsCommonField $channelRecordsCommonField
 * @property ChannelRecord[] $ids
 */
class Channel extends ActiveRecord
{
    use SaveRelationsTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'channel';
    }

    public function behaviors() {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => date('Y-m-d')
            ],
            'saveRelations' => [
                'class' => SaveRelationsBehavior::className(),
                'relations' => [
                    'seoData' => ['cascadeDelete' => true],
                    'slugManager' => ['cascadeDelete' => true],
                    'channelContent' => ['cascadeDelete' => true],
                    'channelRecordsCommonField' => ['cascadeDelete' => true],
                ],
            ],
        ];
    }

    public function transactions() {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'route_id', 'seo_id', 'record_structure', 'created_at', 'updated_at'], 'required'],
            [['route_id', 'seo_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'record_structure'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название типа записи',
            'route_id' => 'Route ID',
            'seo_id' => 'Seo ID',
            'record_structure' => 'Структура записи',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChannelRecords()
    {
        return $this->hasMany(ChannelRecord::className(), ['channel_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeoData()
    {
        return $this->hasOne(SeoData::className(), ['id' => 'seo_id']);
    }

    public function getSlugManager()
    {
        return $this->hasOne(SlugManager::className(), ['id' => 'route_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChannelContent()
    {
        return $this->hasMany(ChannelContent::className(), ['channel_id' => 'id']);
    }

    public function getChannelRecordsCommonField()
    {
        return $this->hasMany(ChannelRecordsCommonField::className(), ['channel_id' => 'id']);
    }
}