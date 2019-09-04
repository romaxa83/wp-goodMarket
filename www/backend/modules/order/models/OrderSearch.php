<?php

namespace backend\modules\order\models;

use backend\modules\order\models\Order;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
class OrderSearch extends Order
{
    public function rules()
    {
        return [
            [['status', 'delivary', 'payment_method', 'paid'], 'integer'],
            [['fullname', 'city', 'date', 'address'], 'string'],
            [['fullname', 'city', 'date', 'address',], 'safe']
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

    public function search($params)
    {
        $int_fields = ['status', 'delivary', 'payment_method', 'paid'];
        $query = self::find()
            ->select(['user.*', 'guest.*', 'order.*'])
            ->leftJoin('user','order.user_id=user.id')
            ->leftJoin('guest','order.guest_id=guest.id')
            ->orderBy('order.id DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $dataProvider->setSort([
            'attributes' => array_merge($dataProvider->getSort()->attributes, [
                'id' => [
                    'asc' => [Order::tableName() . '.id' => SORT_ASC],
                    'desc' => [Order::tableName() . '.id' => SORT_DESC]
                ],
                'status' => [
                    'asc' => [Order::tableName() . '.status' => SORT_ASC],
                    'desc' => [Order::tableName() . '.status' => SORT_DESC]
                ],
                'date' => [
                    'asc' => [Order::tableName() . '.date' => SORT_ASC],
                    'desc' =>  [Order::tableName() . '.date' => SORT_DESC],
                ],
            ])
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if(!empty($this->fullname)){
            $query->andWhere(['like', 'concat(user.first_name," ",user.last_name)', $this->fullname])
            ->orWhere(['like', 'concat(guest.first_name, " ",guest.last_name)', $this->fullname]);
        }

        if(!empty($this->address)){
            $query->andWhere(['like', 'concat(order.address, order.city)', $this->address]);
        }

        foreach ($int_fields as $key) {
            if($this->$key !== ''){
                $query->andWhere(['order.'.$key=>$this->$key]);
            }
        }

        if(!empty($this->date)){
            $query->andWhere(['like', 'order.date', $this->date]);
        }
        return $dataProvider;
    }
    
    public function getStatusHistory(){
        return HistoryStatusOrder::find()->where(['order_id' => $this->id])->asArray()->all();
    }
}
