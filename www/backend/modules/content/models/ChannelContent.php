<?php


namespace backend\modules\content\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "channel_content".
 *
 * @property int $id
 * @property int $channel_id
 * @property string $name
 * @property string $label
 * @property string $type
 * @property string $text
 */
class ChannelContent extends ActiveRecord
{
    public function rules() {
        return [
            [['name', 'label', 'type'], 'required'],
            ['channel_id', 'integer'],
            [['name', 'label', 'type', 'content'], 'string'],
        ];
    }
}