<?php

namespace backend\modules\settings\models;

use Yii;

/**
 * This is the model class for table "settings".
 *
 * @property int $id
 * @property string $name
 * @property string $body
 */
class Settings extends \yii\db\ActiveRecord
{  // public $id;
    public $lang;
    public $alias;
    public $status;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['body'], 'string'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */

    /**
     * {@inheritdoc}
     * @return SettingsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SettingsQuery(get_called_class());
    }

}
