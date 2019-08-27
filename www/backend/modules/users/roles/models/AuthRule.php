<?php

namespace backend\modules\users\roles\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Это класс модели для таблицы "auth_rule".
 *
 * @property string $name
 * @property resource $data
 * @property int $created_at
 * @property int $updated_at
 *
 * @property AuthItem[] $authItems
 */
class AuthRule extends ActiveRecord
{
    /**
     * Возвращает название таблицы в базе данных этой модели
     * @see https://www.yiiframework.com/doc/api/2.0/yii-db-activerecord#tableName()-detail
     */
    public static function tableName()
    {
        return 'auth_rule';
    }

    /**
     * Метод описывает правила валидации данных
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-model#rules()-detail
     * @return array
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['data'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['name'], 'unique'],
        ];
    }

    /**
     * Метод описывает атрибуты таблицы в базе данных
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-model#attributeLabels()-detail
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Связь один-ко-многим с моделью AuthItem
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItems()
    {
        return $this->hasMany(AuthItem::className(), ['rule_name' => 'name']);
    }
}
