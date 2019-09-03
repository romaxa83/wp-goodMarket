<?php

namespace backend\modules\blog\helpers;

class TitleHelper
{
    /**
     * если в строке больше двух слов возвращает массив,где
     * first - первые два слова
     * second - остальные слова
     * @param $title
     */
    public static function detDelivery($title)
    {
        $str = explode(' ', $title);

        if(count($str) <= 2){
            return $title;
        }
        $first = [];
        $second = [];
        foreach ($str as $key => $value){
            if($key == 0 || $key == 1){
                $first [$key] = $value;
            } else {
                $second [$key] = $value;
            }
        }
        $str_1 = implode(' ',$first);
        $str_2 = implode(' ',$second);

        return "<span class=\"header-title__text\">$str_1</span> $str_2";
    }
}
