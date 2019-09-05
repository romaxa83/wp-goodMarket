<?php

namespace backend\modules\content\models;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "channel_record".
 *
 * @property int $id
 * @property int $channel_id
 * @property string $title
 * @property int $seo_id
 * @property int $cover_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Channel $channel
 * @property SeoData $seoData
 * @property ChannelRecordContent $channelRecordContent
 * @property ChannelRecordsCommonField $channelRecordsCommonField
 * @property SlugManager $slugManager
 * @property Channel[] $ids
 * @property ChannelCategory[] $channelCategories
 */
class ChannelRecord extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'channel_record';
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
                    'channelRecordContent' => ['cascadeDelete' => true],
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
            [['channel_id', 'title', 'seo_id', 'route_id', 'created_at', 'updated_at'], 'required'],
            [['channel_id', 'seo_id', 'cover_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['channel_id'], 'exist', 'skipOnError' => true, 'targetClass' => Channel::className(), 'targetAttribute' => ['channel_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'channel_id' => 'Channel ID',
            'title' => 'Название записи',
            'seo_id' => 'Seo ID',
            'cover_id' => 'Обложка',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChannel()
    {
        return $this->hasOne(Channel::className(), ['id' => 'channel_id'])
            ->with([
                'channelContent' => function(ActiveQuery $query) {
                    $query->indexBy('name');
                }
            ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeoData()
    {
        return $this->hasOne(SeoData::className(), ['id' => 'seo_id']);
    }

    public function getSlugManager() {
        return $this->hasOne(SlugManager::className(), ['id' => 'route_id']);
    }

    public function getChannelRecordContent()
    {
        return $this->hasMany(ChannelRecordContent::className(), ['channel_record_id' => 'id']);
    }

    public function getChannelCategories()
    {
        return $this->hasMany(ChannelCategory::className(), ['id' => 'category_id'])
            ->viaTable('channel_category_record', ['record_id' => 'id']);
    }

    public function getChannelRecordsCommonField()
    {
        return $this->hasMany(ChannelRecordsCommonField::className(), ['channel_id' => 'channel_id'])
            ->indexBy('name');
    }
}