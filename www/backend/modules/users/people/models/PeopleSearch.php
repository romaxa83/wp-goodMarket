<?php

namespace backend\modules\users\people\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * PeopleSearch представляет собой модель, лежащую в основе формы поиска `common\models\User`.
 */
class PeopleSearch extends User
{
    /**
     * Метод описывает правила валидации данных для фильтрации
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-model#rules()-detail
     * @return array
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['first_name', 'last_name', 'phone', 'email', 'created_at' ,'search'], 'safe'],
        ];
    }

    /**
     * Метод создает сценарии для валидации данных
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-model#scenarios()-detail
     * @return array
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Создает экземпляр поставщика данных с применением поискового запроса.
     *
     * @param array $params Параметры запроса поиска
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {

        $query = User::find()->where(['!=', 'type', 0]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);

//        echo '<pre>';
//        var_dump($this);
//        echo '</pre>';
//        die;
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['=', 'id', $this->id])
            ->andFilterWhere(['like', 'first_name', $this->first_name])
            ->andFilterWhere(['like', 'last_name', $this->last_name])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'FROM_UNIXTIME(created_at)', $this->created_at]);

        return $dataProvider;
    }
}
