<?php

namespace backend\modules\blog\helpers;

use backend\modules\blog\entities\Post;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use DateTime;
use yii\db\Exception;

class DateHelper
{
    public static function convertDate($date) : string
    {
        return date('d.m.Y',$date);
    }

    public static function convertDateTime($date) : string
    {
        return date('d.m.Y H:i:s',$date);
    }

    public static function convertForRoute($date) : string
    {
        return date('Y-m-d',$date);
    }

    public static function convertDateTimeFormat($date) : string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s',$date)->format('d.m.Y');
    }

    public static function convertForAdmin($date) : string
    {
        if(self::validateDate($date)){
            return Carbon::createFromFormat('Y-m-d H:i:s',$date)->format('d-m-Y');
        }
        return 'не верный формат даты';
    }

    public static function convertForSearch($date) : string
    {
        return Carbon::createFromFormat('d-m-Y',$date)->format('Y-m-d');
    }

    public static function convertForPoint($date) : string
    {
        return Carbon::createFromFormat('Y-m-d',$date)->format('d.m.Y');
    }

    public static function convertForApi($date) : string
    {
        return Carbon::createFromFormat('d.m.Y',$date)->format('Y-m-d');
    }

    public static function convertForOffers($date) : string
    {
        return Carbon::createFromFormat('Y-m-d',$date)->format('d.m.Y');
    }

    /* конвертирует дату публикации в timestamp */
    public static function convertPublishedForUnix($published_at)
    {
        if($published_at){

            return (\DateTimeImmutable::createFromFormat('d-m-Y H:i',$published_at))->getTimestamp();
        }
//        throw new Exception('Wrong published for timestamp');
    }

    public static function convertUnixForPublished($timestamp)
    {
        return date('d-m-Y H:i',$timestamp);
    }



    public static function postStatus($model) : string
    {
        if ($model->status == Post::ACTIVE && $model->published_at != null){
            $status = 'Опубликовано - ' . self::convertDate($model->published_at);
        } elseif ($model->status == Post::DRAFT && $model->published_at != null){
            $status = 'Отложенно до - '. self::convertDate($model->published_at);
        } else {
            $status = 'В черновиках';
        }
        return $status . '<br>' .  'Статья создана - '. self::convertDate($model->created_at);
    }

    /**
     * @param $timestamp
     * @return string
     */
    public static function dateFrontend($timestamp)
    {
        return self::convertDate($timestamp);
    }

    public static function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}
