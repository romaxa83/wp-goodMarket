<?php

namespace app\modules\users\administrators\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * AdministratorsSearch представляет собой модель, лежащую в основе формы поиска `common\models\User`.
 */
class AdministratorsSearch extends User
{

    /**
     * @var string Переменая для работы модуля
     */
    public $roleName;

    /**
     * Метод описывает правила валидации данных для фильтрации
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-model#rules()-detail
     * @return array
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'first_name', 'last_name', 'email', 'roleName', 'search'], 'safe'],
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
        $query = User::find()->joinWith('role')->where(['user.type' => 0])->andWhere(['!=', 'user.id', 1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $dataProvider->setSort([
            'attributes' => [
                'id',
                'username',
                'first_name',
                'last_name',
                'email',
                'roleName' => [
                    'asc' => ['auth_assignment.item_name' => SORT_ASC],
                    'desc' => ['auth_assignment.item_name' => SORT_DESC],
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

//        $query->andFilterWhere(['or', ['like', 'user.username', $this->search], ['like', 'user.email', $this->search]])
//            ->orFilterWhere(['like', 'auth_assignment.item_name', $this->search]);

        $query->andFilterWhere(['=', 'user.id', $this->id])
            ->andFilterWhere(['like', 'user.username', $this->username])
            ->andFilterWhere(['like', 'user.first_name', $this->first_name])
            ->andFilterWhere(['like', 'user.last_name', $this->last_name])
            ->andFilterWhere(['like', 'user.phone', $this->phone])
            ->andFilterWhere(['like', 'auth_assignment.item_name', $this->roleName])
            ->andFilterWhere(['like', 'user.email', $this->email]);

        return $dataProvider;
    }
}
