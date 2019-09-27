<?php

namespace backend\modules\content\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "channel_category_content".
 *
 * @property int $id
 * @property int $channel_id
 * @property string $name
 * @property string $label
 * @property string $type
 * @property string $content
 */
class ChannelCategoryContent extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'channel_category_content';
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
}