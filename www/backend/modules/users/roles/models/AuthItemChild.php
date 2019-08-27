<?php

namespace backend\modules\users\roles\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Это класс модели для таблицы "auth_item_child".
 *
 * @property string $parent
 * @property string $child
 *
 * @property AuthItem $parent0
 * @property AuthItem $child0
 */
class AuthItemChild extends ActiveRecord
{
    /**
     * Возвращает название таблицы в базе данных этой модели
     * @see https://www.yiiframework.com/doc/api/2.0/yii-db-activerecord#tableName()-detail
     */
    public static function tableName()
    {
        return 'auth_item_child';
    }

    /**
     * Метод описывает правила валидации данных
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-model#rules()-detail
     * @return array
     */
    public function rules()
    {
        return [
            [['parent', 'child'], 'required'],
            [['parent', 'child'], 'string', 'max' => 64],
            [['parent', 'child'], 'unique', 'targetAttribute' => ['parent', 'child']],
            [['parent'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['parent' => 'name']],
            [['child'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['child' => 'name']],
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
            'parent' => 'Parent',
            'child' => 'Child',
        ];
    }

    /**
     * Связь один-к-одному с моделью AuthItem ['name' => 'parent']
     * @return \yii\db\ActiveQuery
     */
    public function getParent0()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'parent']);
    }

    /**
     * Связь один-к-одному с моделью AuthItem ['name' => 'child']
     * @return \yii\db\ActiveQuery
     */
    public function getChild0()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'child']);
    }
}
