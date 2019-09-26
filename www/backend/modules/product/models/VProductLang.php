<?php

namespace backend\modules\product\models;

use common\models\Lang;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use function foo\func;

class VProductLang extends ActiveRecord {

    public $languageData;

    public static function tableName() {
        return 'vproduct_lang';
    }

    public static function indexLangBy(array $data, string $column = 'lang_id') {
        foreach ($data as $k => $v) {
            foreach ($v['vproducts'] as $k1 => $v1) {
                $vProductLang = [];
                foreach ($v1['vProductLang'] as $k2 => $v2) {
                    $vProductLang[$v2[$column]] = $v2;
                }
                $data[$k]['vproducts'][$k1]['vProductLang'] = $vProductLang;
            }
        }
        return $data;
    }

}
