<?php

namespace backend\modules\import\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "parse_shop".
 *
 * @property int $id
 * @property string $name
 * @property string $link
 * @property string $date_create
 * @property string $date_update
 */
class ParseShop extends ActiveRecord {

    const IN_PROCESS = 1;
    const LOADED = 2;
    const NOT_PROCESS = 0;
    const HIGH_PRIORITY = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'parse_shop';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['link', 'name'], 'required'],
            [['link'], 'string'],
            [['date_create', 'date_update'], 'safe'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Название магазина',
            'link' => 'Ссылка',
            'update_frequency' => 'Частота обновления',
            'currency' => 'Валюта',
            'currency_value' => 'Курс валюты',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата обновления',
            'prod_process' => 'Процесс'
        ];
    }

}
