<?php

namespace backend\modules\blog\helpers;

use backend\modules\blog\type\MessageType;

class MessageHelper
{


    private $errorPost = [
        1 => 'Данный пост закреплен на главной,по этому не может быть снят с публикации.',
        2 => 'Кол-во выводимых постов на главной странице не может быть больше четырех.',
        3 => 'У поста уже указана позиция.',
        4 => 'Не представляеть возможным,закрепить отложенный пост на главной странице.'
    ];

    private $errorHotelReview = [
        1 => 'Не передан url.',
        2 => 'Данная картинка уже присутствует в галереи.'
    ];

    private $successPost = [
        0 => 'Пост снят с публикации',
        1 => 'Пост опубликован'
    ];

    private $successPostMain = [
        0 => 'Пост снят с главной страницы',
        1 => 'Пост будет выведен на главную страницу'
    ];

    public function errorPost($type_message)
    {
        return new MessageType('error',$this->errorPost[$type_message]);
    }

    public function errorHotelReview($type_message)
    {
        return new MessageType('error',$this->errorHotelReview[$type_message]);
    }

    public function successPost($type_message,$flag = 'post',$str=null)
    {
        switch ($flag) {
            case 'post':
                $message = $this->successPost[$type_message];
                break;

            case 'post_main':
                $message = $this->successPostMain[$type_message];
                break;

            case 'str':
                if($str){
                    $message = $str;
                } else {
                    throw new \DomainException('Укажите текст сообщения третим параметром');
                }
                break;

            default:
                throw new \DomainException('Неверно указан flag');
        }


        return new MessageType('success',$message);
    }

}
