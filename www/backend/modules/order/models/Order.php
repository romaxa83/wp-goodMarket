<?php

namespace backend\modules\order\models;

use common\models\Guest;
use common\models\User;
use backend\modules\order\models\HistoryStatusOrder;
use yii\db\ActiveRecord;
use backend\modules\order\models\OrderProduct;

class Order extends ActiveRecord {

    const PAYMENT_UNKNOWN = 0;
    const PAYMENT_CASH = 1;
    const PAYMENT_VISA = 2;
    const PAYMENT_PRIVAT = 3;

    const DELIVERY_UNKNOWN = 0;
    const DELIVERY_NP = 1;
    const DELIVERY_COURIER = 2;

    const STATUS_NEW = 1;
    const STATUS_CONFIRM = 2;
    const STATUS_CANCEL = 3;
    const STATUS_SEND = 4;
    const STATUS_ONE_CLICK = 5;

    const UNPAID = 0;
    const PAID = 1;

    const FULL_ORDER_GUEST = 'full_order_guest';
    const FULL_ORDER_USER = 'full_order_user';
    const SHORT_ORDER_USER = 'short_order_user';
    const DELIVERY_COURIER_USER = 'delivery_courier_user';
    const DELIVERY_COURIER_GUEST = 'delivery_courier_guest';
    const DELIVERY_NP_USER = 'delivery_np_user';
    const DELIVERY_NP_GUEST = 'delivery_np_guest';
    const FRONT_ORDER = 'front_order';

    public $fullname;
    public $user_id;
    public $user_status;
    public $order_status;
    public $street;
    public $home;
    public $flat;
    public static function tableName()
    {
        return 'order';
    }

    public function rules() {
        return [
            [['city', 'street', 'home'], 'required', 'on'=>self::FRONT_ORDER],
            [['city', 'street', 'home', 'user_id'], 'required', 'on'=>self::DELIVERY_COURIER_USER],
            [['city', 'street', 'home'], 'required', 'on'=>self::DELIVERY_COURIER_GUEST],
            [['city', 'address', 'user_id'], 'required', 'on'=>self::DELIVERY_NP_USER],
            [['city', 'address'], 'required', 'on'=>self::DELIVERY_NP_GUEST],

        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'delivary' => 'Способ доставки',
            'payment_method' => 'Способ оплаты',
            'date' => 'Дата оформления заказа',
            'status' => 'Статус заказа',
            'paid' => 'Оплачен',
            'city' => 'Населенный пункт',
            'fullname' => 'Имя пользователя',
            'user_id' => 'ID пользователя',
            'comment' => 'Комментарии',
            'address' => 'Адрес доставки',
            'user_status' => 'Статус пользователя',
            'order_status' => 'Бланк заказа',
            'street' => 'Улица',
            'home' => 'Дом',
            'flat' => 'Квартира'
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if(!$this->isNewRecord){
                $oldAttributes = $this->getOldAttributes();
                $model = new HistoryStatusOrder();
                $model->status = $oldAttributes['status'];
                $model->order_id = $this->id;
                $model->date = date("Y-m-d H:i:s");
                $model->save();
            }
            return true;
        } else {
            return false;
        }
    }

    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getGuest() {
        return $this->hasOne(Guest::className(), ['id' => 'guest_id']);
    }

    public function getOrderProduct() {
        return $this->hasMany(OrderProduct::className(), ['order_id' => 'id']);
    }
}
