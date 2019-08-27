<?php

namespace backend\modules\settings\models;

use Yii;
use yii\base\Model;
/**
 * This is the model class for table "settings".
 *
 * @property int $id
 * @property string $name
 * @property string $body
 */
class RowLang extends Model
{
    public $id;
    public $lang;
    public $alias;
    public $status;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
             [['lang', 'alias'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    
}
