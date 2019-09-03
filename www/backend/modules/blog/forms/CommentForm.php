<?php

namespace backend\modules\blog\forms;

use yii\base\Model;

class CommentForm extends Model
{
    public $parent_id;
    public $text;

    public function rules(): array
    {
        return [
            [['text'], 'required'],
            ['text', 'string'],
            ['parentId', 'integer'],
        ];
    }
}