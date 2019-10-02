<?php

namespace backend\modules\content\models;

use Yii;

/**
 * This is the model class for table "content_options".
 *
 * @property int $id
 * @property string $name
 * @property string $value
 */
class ContentOptions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'content_options';
    }

    public static function getOption($option)
    {
        $row = self::find()->select('value')->where(['name' => $option])->asArray()->one();
        return $row['value'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'value'], 'required'],
            [['name', 'value'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'value' => 'Значение',
        ];
    }
}