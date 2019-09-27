<?php

namespace backend\modules\content\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "channel_records_common_field".
 *
 * @property int $id
 * @property int $channel_id
 * @property string $name
 * @property string $label
 * @property string $type
 * @property string $content
 *
 * @property Channel $channel
 */
class ChannelRecordsCommonField extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'channel_records_common_field';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'label', 'type'], 'required'],
            [['channel_id'], 'integer'],
            [['content'], 'string'],
            [['name', 'label', 'type'], 'string', 'max' => 255],
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
            'name' => 'Name',
            'label' => 'Label',
            'type' => 'Type',
            'content' => 'Content',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getChannel()
    {
        return $this->hasOne(Channel::className(), ['id' => 'channel_id']);
    }
}