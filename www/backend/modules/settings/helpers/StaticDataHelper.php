<?php

namespace backend\modules\settings\helpers;

class StaticDataHelper
{
    public static function parseDescriptionForAdmin($str)
    {
        if(strpos($str,'|')){

            $string = '<ul>';
            foreach (explode('|',$str) as $item) {
                $string .= '<li>'.$item.'</li>';
            }

            return $string .= '</ul>';
        }
        return '<ul><li>'.$str.'</li></ul>';
    }

    public static function parseDescriptionForFront($str)
    {
        if(strpos($str,'|')){

            $string = '<ul>';
            foreach (explode('|',$str) as $item) {
                $string .= '<li>'.$item.'</li>';
            }

            return $string .= '</ul>';
        }
        return $str;
    }
}