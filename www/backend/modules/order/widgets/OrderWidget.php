<?php

namespace backend\modules\order\widgets;

use common\models\Guest;
use Yii;
use yii\base\Widget;
use backend\modules\order\assets\OrderWidgetAsset;

class OrderWidget extends Widget
{
    public $template;
    public $user;
    public $buyer;

    public $order;
    public $count;
    public $payments;
    public $deliveries;

    public function init()
    {
        parent::init();

        Yii::setAlias('@orderwidget-assets',  Yii::getAlias('@backend').'/modules/order/assets');
        OrderWidgetAsset::register(Yii::$app->view);
    }

    public function run()
    {
        $view = Yii::getAlias('@backend').'/modules/order/views/' . $this->template . '.php';

        if (!file_exists($view)) {
            return 'Неверно задан параметр $template';
        }
        if($this->template == 'pre-ordering-front-widget'){
            $guest = new Guest();

            return $this->renderFile($view,[
                'user' => $this->user,
                'guest' => $guest
            ]);
        }
        if($this->template == 'cabinet-list-order'){

            return $this->renderFile($view,[
                'order' => $this->order,
                'count' => $this->count
            ]);
        }

        return $this->renderFile($view,[
            'user' => $this->user,
            'buyer' => $this->buyer,
            'deliveries' => $this->deliveries,
            'payments' => $this->payments,
        ]);
    }
}