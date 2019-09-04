<?php
namespace backend\modules\order\helpers;

class AddressHelper
{
    public static function nice($address)
    {
        if(strpos($address,'|')){
            return str_replace('|',' ,',$address);
        }
        return $address;
    }

    public static function niceWithCity($city,$address,$phone)
    {
        $phone = ($phone) ? $phone : 'Не указан номер телефона';
        return '<ul><li>'.$city.'</li><li>'. self::nice($address) .'</li><li>' . $phone . '</li></ul>';
    }
}