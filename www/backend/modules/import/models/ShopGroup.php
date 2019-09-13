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
class ShopGroup extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'shop_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [];
    }

}
