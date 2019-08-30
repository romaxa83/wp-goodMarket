<?php

namespace backend\modules\blog\helpers;

use backend\modules\blog\entities\HotelReview;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\modules\filemanager\models\Mediafile;

class ImageHelper
{
    /*
     * метод возвращет url нужной (по размеру картинки) из таблицы filemanager_media ,поле thumbs
     * принимает два обязательных параметра - сериализованую строку из поля thumbs и название размера(small,medium,large)
     * также можно установить флаг true (для отображение на фронте)
     */
    public static function renderImg($thumbs,$size,$frontend=null) : string
    {
        if(!$thumbs){
            throw new \Exception('Нет превью');
        }
        if(array_key_exists($size,unserialize($thumbs))){

            $url = Url::base() . unserialize($thumbs)[$size];

            if ($frontend){
                return Html::img(  '/admin' . $url);
            }
            return Html::img($url);
        }
        throw new \Exception('Картинки с размером '.$size.' не существует');
    }

    public static function getAvatar($media_id,$frontend=null) : string
    {
        if($media_id){
            $image = Mediafile::findOne($media_id);

            if(!$image){
                if($frontend){
                    return Html::img('/admin' .Url::base() . '/img/no_ava.png');
                }
                return Html::img(Url::base() . '/img/no_ava.png');
            }
            if($image->thumbs){
                return self::renderImg($image->thumbs,'small',$frontend);
            }

            if($frontend){
                return Html::img(  '/admin' . Url::base() . $image->url);
            }
            return Html::img(  Url::base() . $image->url);
        }

    }

    public static function notImg($frontend = null) : string
    {
        if($frontend){
            return '/admin' . Url::base() . '/img/not-images.png';
        }
        return Html::img(  Url::base() . '/img/not-images.png',['style' => 'width:70px']);
    }

    public static function notAvatar($frontend=null) : string
    {
        $url = Url::base() . '/img/no_ava.png';
        if($frontend){
            return Html::img('/admin' . $url,['style' => 'width:70px']);
        }
        return Html::img($url,['style' => 'width:70px']);
    }

    public static function frontendImg($model)
    {
        //для обзора отелей
        if(isset($model->hotel_id)){
            $media_ids = ImageHelper::parseMediaIds($model->media_ids);
            if($media_ids){
                return '/admin' . Url::base() . self::getImageUrlById($media_ids[0]);
            }
            return self::notImg(true);
        }

        //для постов
        if($model->media_id == null){
            return self::notImg(true);
        }
        return '/admin' . Url::base() . $model->media->url;
    }

    public static function parseMediaIds($media_ids)
    {
        if($slice = substr(substr($media_ids,1),0,-1)){
            return explode(',',$slice);
        }
        return  false;
    }

    public static function getImageByIdAndRender($id,$size)
    {
        $img = Mediafile::find()->select(['url','thumbs'])->where(['id' => $id])->one();

        if($img->thumbs){

            return self::renderImg($img->thumbs,$size);
        }
        return Html::img(Url::base() .$img->url);
    }

    public static function getImageUrlById($id,$frontend = null)
    {
        if($frontend){
            return '/admin' . Url::base() . (Mediafile::find()->select('url')->where(['id' => $id])->one())->url;
        }

        if($media = Mediafile::find()->select('url')->where(['id' => $id])->one()){
            return $media->url;
        }
        return Url::base() . '/img/not-images.png';
    }

    public static function getImageUrlsByIds($ids)
    {
        if($ids && is_array($ids)){

            $urls = Mediafile::find()->select(['id','url','filename','alt','description'])->where(['in','id',$ids])->asArray()->all();
            $mapping =  array_map(function($item){
                return [
                    'id' => $item['id'],
                    'url' => '/admin'. Url::base() . $item['url'],
                    'alt' => $item['alt'] != null ? $item['alt'] : $item['filename'],
                    'title' => $item['description'] != null && $item['description'] != '' ? $item['description'] : false,
                ];
            },$urls);

            //востанавливаем правильную сортировку
            $sort = array_flip($ids);
            $key_mapping = ArrayHelper::index($mapping,'id');
            foreach($sort as $key => $item){
                $sort[$key] = $key_mapping[$key];
            }
            $arr = array_values($sort);

            return array_map(function($item,$key){
                return [
                    'url' => $item['url'],
                    'alt' => $item['alt'],
                    'title' => $item['title'],
                    'class' => self::getClass($key),
                ];
            },$arr,array_keys($arr));
        }

        return self::notImg(true);
    }

    public static function getClass($key)
    {
        if($key == 0){
            return 'active';
        }elseif ($key == 1 || $key == 2){
            return '';
        }else{
            return '';
        }

    }

    public static function getAltFront($model)
    {
        if($model instanceof HotelReview){

            $media_ids = ImageHelper::parseMediaIds($model->media_ids)[0];
            if($media_ids){
                $media = Mediafile::find()->where(['id' => $media_ids])->one();
                if($media->alt){
                    return $media->alt;
                }
                return $media->filename;
            }
            return 'not-image';
        }

        if(!$model->media_id){
            return 'not-image';
        }
        if($model->media->alt){
            return $model->media->alt;
        }
        return $model->media->filename;
    }

    public static function getAltReviewAvatar($url)
    {
        $arr = explode("/",$url);
        return end($arr);
    }

    public static function getAltForArray($array)
    {
        if($array['alt']){
            return $array['alt'];
        }
        return $array['filename'];
    }
}
