<?php

namespace backend\modules\users\roles\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Это класс модели для таблицы «auth_item».
 *
 * @property string $name
 * @property int $type
 * @property string $description
 * @property string $rule_name
 * @property resource $data
 * @property int $created_at
 * @property int $updated_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $authItemChildren
 * @property AuthItemChild[] $authItemChildren0
 * @property AuthItem[] $children
 * @property AuthItem[] $parents
 */
class AuthItem extends ActiveRecord
{
    /**
     * @var string Переменая для работы модуля
     */
    public $permission;

    /**
     * @var string Константа для сценария валидации
     */
    const SCENARIO_ROLE = 'role';

    /**
     * @var string Константа для сценария валидации
     */
    const SCENARIO_PERMISSION = 'permission';

    /**
     * Возвращает название таблицы в базе данных этой модели
     * @see https://www.yiiframework.com/doc/api/2.0/yii-db-activerecord#tableName()-detail
     */
    public static function tableName()
    {
        return 'auth_item';
    }

    /**
     * Метод создает сценарии для валидации данных
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-model#scenarios()-detail
     * @return array
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_ROLE => ['name', 'description'],
            self::SCENARIO_PERMISSION => ['name', 'description', 'data'],
        ];
    }

    /**
     * Метод описывает правила валидации данных
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-model#rules()-detail
     * @return array
     */
    public function rules()
    {
        return [


            [['name'], 'required', 'message' => 'Для выбора роли, создайте ее', 'on' => self::SCENARIO_ROLE],

            //[['name', 'data'], 'required', 'on' => self::SCENARIO_PERMISSION],
            //[['name'], 'unique', 'on' => self::SCENARIO_PERMISSION],

            [['description'], 'string'],
            [['name'], 'string', 'max' => 64],
            [['name'], 'unique', 'message' => 'Имя уже существует'],
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
            'name' => 'Имя',
            'type' => 'Type',
            'description' => 'Описание',
            'rule_name' => 'Имя правила',
            'data' => 'Имя модуля',
            'created_at' => 'Время создания',
            'updated_at' => 'Время обновления',
        ];
    }

    /**
     * Связь один-ко-многим с моделью AuthAssignment
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['item_name' => 'name']);
    }

    /**
     * Связь один-к-одному с моделью AuthRule
     * @return \yii\db\ActiveQuery
     */
    public function getRuleName()
    {
        return $this->hasOne(AuthRule::className(), ['name' => 'rule_name']);
    }

    /**
     * Связь один-ко-многим с моделью AuthItemChild ['parent' => 'name']
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren()
    {
        return $this->hasMany(AuthItemChild::className(), ['parent' => 'name']);
    }

    /**
     * Связь один-ко-многим с моделью AuthItemChild ['child' => 'name']
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItemChildren0()
    {
        return $this->hasMany(AuthItemChild::className(), ['child' => 'name']);
    }

    /**
     * Связь один-ко-многим с моделью AuthItemChild ['name' => 'child']
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'child'])->viaTable('auth_item_child', ['parent' => 'name']);
    }

    /**
     * Связь один-ко-многим с моделью AuthItemChild ['name' => 'parent']
     * @return \yii\db\ActiveQuery
     */
    public function getParents()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'parent'])->viaTable('auth_item_child', ['child' => 'name']);
    }

    /**
     * Получаем массив существующих имен модулей
     * @return array|ActiveRecord[]
     */
    public function getData(){
        return AuthItem::find()->where(['type' => 2])->groupBy('data')->asArray()->all();
    }

}
