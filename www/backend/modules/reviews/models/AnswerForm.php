<?php

namespace backend\modules\reviews\models;

use Yii;
use yii\base\Model;
/**
 * This is the model class for table "settings".
 *
 * @property int $id
 * @property string $name
 * @property string $body
 */
class AnswerForm extends Model
{
    public $text;

    public function rules()
    {
    return [
            [['text'], 'string'],
            [['text'], 'required', 'message' => 'Запоните поле'],
        ];
    }
    
}
