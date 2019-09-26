<?php

namespace common\helpers;

use backend\modules\stock\models\Stock;
use backend\modules\stock\models\StocksProducts;
use Exception;

class ProductsHelper
{
    /*
     * метод принимает сериализованую строку(id-товаров)
     * и возращает массив этих id
     */
    public static function productsIdUnser($products_str)
    {
        return !empty($products_str) ? explode(',',unserialize($products_str)) : '';
    }

    /*
     * метод принимает массив(id-товаров)
     * и возращает сериализованую строку этих id
     */
    public static function productsIdSer($arr_product_id)
    {
        return !empty($arr_product_id) ? (serialize(implode(',',$arr_product_id))) : '';
    }

//    /*
//     * метод принимает цену разделенную точкой
//     * и возращает раделеную запятой
//     */
//    public static function viewPrice($price)
//    {
//        return str_replace('.',',',$price);
//    }

    public static function viewPrice($price)
    {
        return  rtrim(rtrim(number_format($price,2,'.',''), '0'), '.');
    }

    /*
     * метод принимает цену и скидку
     * и возвращает новую цену с учетом скидки
     */
    public static function priceWithSale($price,$sale)
    {
        return self::viewPrice((((float)$price*100) - (((float)$price*100)*(float)$sale/100))/100);
    }

    /*
     * метод возвращет url нужной (по размеру картинки) из таблицы filemanager_media ,поле thumbs
     * принимает два параметра - сериализованую строку из поля thumbs и название размера(small,medium,large)
     */
    public static function productImage($thumbs,$size)
    {
        if(array_key_exists($size,unserialize($thumbs))){
            return unserialize($thumbs)[$size];
        }
        throw new Exception('Картинки с размером '.$size.' не существует');
    }

    /*
     * метод возвращет сумму по товару
     * принимает два параметра - цену товара и кол-во
     */
    public static function getAllPrice($price,$amount)
    {
        return self::viewPrice((float)$price*(int)$amount);
    }


    public static function getSaleFromPrices($price,$price_sale)
    {
        if($price == 0){
            return 0;
        }
        return round(self::viewPrice((((float)$price_sale/(float)$price)*100) - 100));
    }
}