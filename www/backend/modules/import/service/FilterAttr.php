<?php

namespace backend\modules\import\service;

use backend\modules\product\models\ProductCharacteristic;

class FilterAttr {

    const ATTRIBUTE_LIST = [
        'price' => 'price',
        'manufacture' => 'manufacture',
        'brand' => 'brand',
        'color' => 'color',
        'weight' => 'weight',
        'power' => 'power',
        'material' => 'material',
        'diagonal' => 'diagonal',
        'size' => 'size'
    ];
    const REGISTER_ATTRIBUTE = [
        'price' => [
            0 => 'цена',
            1 => 'price'
        ],
        'manufacture' => [
            0 => 'manufacture',
            1 => 'vendor',
            2 => 'производитель',
            3 => 'страна производитель'
        ],
        'brand' => [
            0 => 'brand',
            1 => 'бренд',
        ],
        'color' => [
            0 => 'color',
            1 => 'цвет',
        ],
        'size' => [
            0 => 'size',
            1 => 'размер',
        ],
        'weight' => [
            0 => 'weight',
            1 => 'вес',
        ],
        'power' => [
            0 => 'power',
            1 => 'мощьность',
        ]
    ];

    private $importService;

    public function init() {
        parent::init();
    }

    /**
     * @return array
     */
    public function list()
    {
    return self::ATTRIBUTE_LIST;
}

/**
 * Метод возвращает имя атрибута привязаный к системе
 * @var string $attr Принимает атрибут из вне
 * @return null|string возвращает имя аттрибута работаюший в системе
 */
public function findAttribute($attr) {
    $str = mb_strtolower($attr);
    $name = null;
    foreach (self::REGISTER_ATTRIBUTE as $key => $value) {
        if (in_array($str, $value)) {
            $name = $key;
            break;
        }
    }
    return $name;
}

/*
 * Метод находит id характеристик товара
 */

public function getProdChars($prod_id) {
    $result = ProductCharacteristic::find()->select('product_characteristic.*, characteristic.*')
            ->leftJoin('characteristic', 'product_characteristic.characteristic_id = characteristic.id')
            ->where(['or', ['product_characteristic.product_id' => $prod_id], ['product_import_id' => $prod_id]])
            ->asArray()
            ->all();
    return array_column($result, 'characteristic_id');
}

private function saveCatChar($category_new, $category_old, array $char_add, array $char_del) {
    if (!empty($char_add)) {
        $table_data = \Yii::$app->db->createCommand("SELECT category_id, characteristic_id FROM category_characteristic")->queryAll();
        if (!empty($table_data)) {
            $table_data = $this->formattedTableToNormal($table_data);
        }
        $insert_data = $char_add;
        if (isset($table_data[$category_new])) {
            $insert_data = array_diff($char_add, $table_data[$category_new]);
        }
        if (!empty($insert_data)) {
            \Yii::$app->db->createCommand()->batchInsert(
                    'category_characteristic', ['category_id', 'characteristic_id'], $this->formattedNormalToTable($category_new, $insert_data))->execute();
        }
        \Yii::$app->db->createCommand()
                ->update('category_characteristic', ['prod_count' => new \yii\db\Expression('prod_count + 1')], ['or'] + $this->formattedNormalToTable($category_new, $this->cutArray($char_add, $insert_data)))
                ->execute();
    }
    if (!empty($char_del)) {
        \Yii::$app->db->createCommand()
                ->update('category_characteristic', ['prod_count' => new \yii\db\Expression('prod_count - 1')], ['or'] + $this->formattedNormalToTable($category_old, $char_del))
                ->execute();
        \Yii::$app->db->createCommand()->delete('category_characteristic', 'prod_count = 0')->execute();
    }
}

public function saveCategoryCharacteristics($category_old = null, $category_new = null, array $characters_old = [], array $characters_new = []) {
    $char_add_ids = [];
    $char_del_ids = [];
    $char_add = array_diff($characters_new, $characters_old);
    $char_del = array_diff($characters_old, $characters_new);

    /* Ситуация, когда меняются только характеристики продукта */
    // if($category_new === null && !empty($characters_new) && !empty($characters_old)){
    //     $category_old = $category_new;
    // }
    /* Ситуация, когда меняется только категория продукта */
    if (($category_old !== $category_new) && empty($char_add) && empty($char_del)) {
        $char_add = $characters_new;
        $char_del = $characters_old;
    }

    /* Ситуация, когда у товара нету ни стырах, ни новых характеристик */
    if (empty($characters_old) && empty($characters_new)) {
        return;
    }
    $this->saveCatChar($category_new, $category_old, $char_add, $char_del);
}

private function formattedNormalToTable($category_id, array $chars) {
    $table_format = array_map(function($item) use ($category_id) {
        return [
            'category_id' => $category_id,
            'characteristic_id' => $item
        ];
    }, $chars);
    return $table_format;
}

private function formattedTableToNormal(array $cat_char) {
    $normal_format = [];
    foreach ($cat_char as $item) {
        $normal_format[$item['category_id']][] = $item['characteristic_id'];
    }
    return $normal_format;
}

private function cutArray($total_arr, $piece_arr) {
    return array_filter($total_arr, function($element)use($piece_arr) {
        return !in_array($element, $piece_arr);
    });
}

}
