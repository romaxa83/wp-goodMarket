<?php

namespace backend\modules\reviews\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;
/**
 * This is the model class for table "settings".
 *
 * @property int $id
 * @property string $name
 * @property string $body
 */
class ReviewForm extends Model
{
    public $text;
    public $rating;
    public $verifyCode;
    
    public function rules()
    {
    return [
            [['text'], 'string'],
            [['text','verifyCode'], 'required', 'message' => 'Запоните поле'],
            ['verifyCode', 'captcha']
        ];
    }
    
}
