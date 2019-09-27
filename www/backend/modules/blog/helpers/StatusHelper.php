<?php

namespace backend\modules\blog\helpers;

use backend\modules\blog\entities\Post;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class StatusHelper
{
    const FAQ_INFO = [
        'page_faq' => ' на странице Faq.',
        'page_vip' => ' на странице Vip туры.',
        'page_exo' => ' на странице Экзотические туры.',
        'page_hot' => ' на странице Горячие туры.',
    ];

    public static function list($variant = null): array
    {
        if($variant){
            return [
                0 => 'Выкл',
                1 => 'Вкл',
            ];
        }
        return [
            0 => 'Не активен',
            1 => 'Активен',
        ];
    }

    public static function listPost(): array
    {
        return [
            Post::INACTIVE => 'Черновики',
            Post::ACTIVE => 'Опубликовано',
            Post::DRAFT => 'Отложено',
        ];
    }

    public static function listVote(): array
    {
        return [
            1 => '1',
            2 => '2',
            3 => '3',
            4 => '4',
            5 => '5',
            6 => '6',
            7 => '7',
            8 => '8',
            9 => '9',
            10 => '10',
        ];
    }


    public static function label($status,$post = false,$active = false): string
    {
        switch ($status) {
            case 0:
                $class = 'label label-danger';
                break;
            case 1:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag('span', ArrayHelper::getValue($post ? self::listPost() : self::list($active?true:false), $status), [
            'class' => $class,
        ]);
    }

    public static function labelUser($status)
    {
        $text = [
            0 => 'Не активен',
            10 => 'Активен',
        ];

        switch ($status) {
            case 0:
                $class = 'label label-danger';
                break;
            case 10:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag('span', $text[$status], [
            'class' => $class,
        ]);
    }


    public static function infoFlash($status,$entities) : string
    {
        return $status == 1 ? $entities. ' опубликована' : $entities. ' снята с публикации';
    }

    public static function infoMain($status) : string
    {
        return $status == 1
            ? 'Пост будет выведен на главную страницу'
            : 'Пост снят с главной страницы';
    }

    //для FAQ
    public static function infoFlashFaq($status,$alias) : string
    {
        $str = $status == 1 ? 'Вопрос опубликован' : 'Вопрос снят с публикации';
        $page = '.';
        if($alias){
            $page = self::FAQ_INFO[$alias];
        } else {
            $str = $status == 1 ? 'Вопрос активен' : 'Вопрос не активен';
        }
        return $str . $page;
    }

    //для Notification
    public static function infoFlashNotification($status) : string
    {
        return $status == 1 ? 'Уведомление включено' : 'Уведомление отключено';
    }

    //для Subscriber
    public static function infoFlashSubscriber($status) : string
    {
        return $status == 1 ? 'Пользователю включена рассылка' : 'Пользователю отключена рассылка';
    }

    /**
     * @param $model
     * @param $url
     * @param string $field
     * @return string
     */
    public static function checkBox($model, $url, $field = 'status'):string
    {
        $checked = ($model->$field == 1) ? 'true' : '';
        $disabled = ($model->$field == 2) ? 'true' : '';
        $options = [
            'id' => 'cp_'. $model->id .'_' . $field,
            'class' => 'tgl tgl-light publish-toggle status-toggle change_status',
            'data-id' => $model->id,
            'data-url' => \yii\helpers\Url::to([$url])
        ];

        if ($disabled) {
            $options['disabled'] = $disabled;
        }

        return Html::beginTag('div').
            Html::checkbox($field, $checked, $options).
            Html::label('', 'cp_' . $model->id .'_' . $field, ['class' => 'tgl-btn']).
            Html::endTag('div');
    }
}
