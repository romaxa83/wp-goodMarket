<?php

namespace backend\modules\product\models;

use yii\data\ActiveDataProvider;
use backend\modules\product\models\Product;

class ProductSearch extends Product {

    public $sale;
    public $sale_price;
    public $product_lang_name;
    public $product_lang_price;

    public function rules() {
        return [
            [['id', 'category_id', 'product_lang_name', 'product_lang_price', 'sale', 'sale_price', 'rating', 'amount', 'publish'], 'safe'],
        ];
    }

    public function search($params) {
        $query = Product::find()->joinWith('categoryLang')->joinWith('productLang')->orderBy(['id' => SORT_DESC])->distinct();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => FALSE,
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);
        
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->FilterWhere(['=', 'product.id', $this->id])
                ->andFilterWhere(['like', 'category_lang.name', $this->category_id])
                ->andFilterWhere(['like', 'product_lang.name', $this->product_lang_name])
                ->andFilterWhere(['like', 'product_lang.price', $this->product_lang_price])
                ->andFilterWhere(['=', 'product.rating', $this->rating])
                ->andFilterWhere(['=', 'product.amount', $this->amount])
                ->andFilterWhere(['=', 'product.publish', $this->publish]);

        return $dataProvider;
    }

}
