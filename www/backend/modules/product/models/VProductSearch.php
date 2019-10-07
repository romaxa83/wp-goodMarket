<?php

namespace backend\modules\product\models;

use yii\data\ActiveDataProvider;
use backend\modules\product\models\VProduct;

class VProductSearch extends VProduct {
    public $product_id;

    function search($params) {
        $query = VProduct::find()->where(['product_id' => $this->product_id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => FALSE,
            'pagination' => FALSE
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        return $dataProvider;
    }

}
