<?php

namespace backend\modules\users\roles\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\users\roles\models\AuthItem;

/**
 * PermissionSearch представляет собой модель, лежащую в основе формы поиска `backend\modules\users\roles\models\AuthItem`.
 */
class RolesListSearch extends AuthItem
{

    /**
     * Метод описывает правила валидации данных для фильтрации
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-model#rules()-detail
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'description', 'rule_name', 'data'], 'safe'],
            [['type', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * Метод создает сценарии для валидации данных
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-model#scenarios()-detail
     * @return array
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
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
        $query = AuthItem::find()->where(['type' => 1])->andWhere(['<>', 'name', 'superAdmin']);

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

        $query->andFilterWhere([
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'rule_name', $this->rule_name])
            ->andFilterWhere(['like', 'data', $this->data]);

        return $dataProvider;
    }
}
