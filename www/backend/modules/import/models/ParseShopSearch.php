<?php

namespace backend\modules\import\models;

use yii\data\ActiveDataProvider;
use backend\modules\import\models\ParseShop;

class ParseShopSearch extends ParseShop {

    public function rules() {
        return [
            [['name', 'link', 'date_update', 'process'], 'safe'],
        ];
    }

    public function search($params) {
        $query = ParseShop::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->FilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'link', $this->link])
                ->andFilterWhere(['like', 'date_update', $this->date_update]);
                //->andFilterWhere(['like', 'process', $this->process]);

        return $dataProvider;
    }

}
