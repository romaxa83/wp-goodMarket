<?php

namespace backend\modules\product\models;

use yii\data\ActiveDataProvider;
use backend\modules\product\models\VProduct;

class VProductSearch extends VProduct {

    function search($params) {
        $query = VProduct::find();
        if (isset($params['id'])) {
            $query->where(['product_id' => $params['id']]);
        }
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
