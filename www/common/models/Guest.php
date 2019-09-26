<?php

namespace common\models;

use backend\modules\order\models\Order;
use Yii;

/**
 * This is the model class for table "guest".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $phone
 *
 * @property Order[] $orders
 */
class Guest extends \yii\db\ActiveRecord
{
    const REGISTER_ADMIN_IN_ORDER = 'register_admin_in_order';
    const FRONT_ORDER_PERSONAL = 'front_order_personal';
    const ONE_CLICK = 'one_click';
    const EDIT_GUEST_BACK = 'edit_guest_back';
    public $phone_one_click;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'guest';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            /*Для заказа на фонте*/
            [['first_name','last_name','phone','email'],'required','message' => 'Поле "{attribute}" не может быть пустым', 'on' => self::FRONT_ORDER_PERSONAL],
            [['first_name', 'last_name', 'phone'], 'string', 'max' => 50, 'on' => self::FRONT_ORDER_PERSONAL],
            [['email'], 'string', 'max' => 255, 'on' => self::FRONT_ORDER_PERSONAL],
            [['email'], 'email','message' => '"{attribute}" должен быть корректным', 'on' => self::FRONT_ORDER_PERSONAL],
            ['email', 'unique', 'message' => 'Такое "{attribute}" уже существует', 'on' => self::FRONT_ORDER_PERSONAL],
            ['phone', 'validatePhone', 'on' => self::FRONT_ORDER_PERSONAL],
            /*Для заказа на бэке*/
            [['last_name','phone'],'required','message' => 'Поле "{attribute}" не может быть пустым', 'on' => self::REGISTER_ADMIN_IN_ORDER],
            [['phone'], 'string', 'max' => 50, 'on' => self::REGISTER_ADMIN_IN_ORDER],
            [['email'], 'email','message' => '"{attribute}" должен быть реальным', 'on' => self::REGISTER_ADMIN_IN_ORDER],
            [['phone','email'], 'unique','message' => 'Такое "{attribute}" уже существует', 'on' => self::REGISTER_ADMIN_IN_ORDER],
            ['phone', 'validatePhone', 'on' => self::REGISTER_ADMIN_IN_ORDER],
            /*Для заказа при редактировании*/
            [['last_name','phone'],'required','message' => 'Поле "{attribute}" не может быть пустым', 'on' => self::EDIT_GUEST_BACK],
            ['phone', 'validatePhone', 'on' => self::EDIT_GUEST_BACK],
            [['phone'], 'string', 'max' => 50, 'on' => self::EDIT_GUEST_BACK],
            [['email'], 'email','message' => '"{attribute}" должен быть реальным', 'on' => self::EDIT_GUEST_BACK],
            // [['phone','email'], 'unique','message' => 'Такое "{attribute}" уже существует', 'on' => self::EDIT_GUEST_BACK],

        ];
    }

    public function validatePhone($attribute, $params)
    {
        if (strlen(preg_replace("/[^0-9]/", '', $this->phone)) != 12){
            $this->addError($attribute, 'Введите корректно номер телефона (380*********)');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'email' => 'Email',
            'phone' => 'Телефон',
            'phone_one_click' => 'Телефон',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::className(), ['guest_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return GuestQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GuestQuery(get_called_class());
    }

    public function getFullName()
    {
        return ucfirst($this->first_name) .' '.ucfirst($this->last_name);
    }
}
