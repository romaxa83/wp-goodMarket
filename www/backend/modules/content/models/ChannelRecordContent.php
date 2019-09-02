<?php

namespace backend\modules\content\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "channel_record_content".
 *
 * @property int $id
 * @property int $channel_record_id
 * @property string $name
 * @property string $label
 * @property string $type
 * @property string $text
 */
class ChannelRecordContent extends ActiveRecord
{
    public function rules() {
        return [
            [['name', 'label', 'type', 'content'], 'required', 'message' => 'Не заполнено поле'],
            ['channel_record_id', 'integer'],
            [['name', 'label', 'type', 'content'], 'string'],
        ];
    }
}