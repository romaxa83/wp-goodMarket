<?php

namespace backend\modules\import\models;

/**
 * This is the model class for table "parse_shop_attribute".
 *
 * @property int $id
 * @property int $shop_id
 * @property string $update_frequency
 * @property string $currency
 * @property double $currency_value
 */
class ParseShopAttribute extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'parse_shop_attribute';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['shop_id'], 'integer'],
            [['currency', 'currency_value'], 'required'],
            [['currency_value'], 'number'],
            [['update_frequency'], 'string', 'max' => 20],
            [['currency'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'shop_id' => 'Shop ID',
            'update_frequency' => 'Частота обновления',
            'currency' => 'Валюта',
            'currency_value' => 'Курс',
        ];
    }

}
