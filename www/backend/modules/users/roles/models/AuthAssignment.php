<?php

namespace backend\modules\users\roles\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Это класс модели для таблицы "auth_assignment".
 *
 * @property string $item_name
 * @property string $user_id
 * @property int $created_at
 *
 * @property AuthItem $itemName
 */
class AuthAssignment extends ActiveRecord
{
    /**
     * Возвращает название таблицы в базе данных этой модели
     * @see https://www.yiiframework.com/doc/api/2.0/yii-db-activerecord#tableName()-detail
     */
    public static function tableName()
    {
        return 'auth_assignment';
    }

    /**
     * Метод описывает правила валидации данных
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-model#rules()-detail
     * @return array
     */
    public function rules()
    {
        return [
            [['item_name', 'user_id'], 'required'],
            [['created_at'], 'integer'],
            [['item_name', 'user_id'], 'string', 'max' => 64],
            [['item_name', 'user_id'], 'unique', 'targetAttribute' => ['item_name', 'user_id']],
            [['item_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['item_name' => 'name']],
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
            'item_name' => 'Роль',
            'user_id' => 'ID пользователя',
            'created_at' => 'Время создания',
        ];
    }

    /**
     * Связь один-к-одному с моделью AuthItem
     * @return \yii\db\ActiveQuery
     */
    public function getItemName()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'item_name']);
    }
}
