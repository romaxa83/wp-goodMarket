<?php

namespace backend\modules\order\components;

use yii\grid\ActionColumn;
use yii\helpers\Html;

class CustomActionColumn extends ActionColumn
{
    public $option = [];

    protected function renderFilterCellContent()
    {
        return Html::a($this->option['name'],$this->option['url'], ['class' => 'btn btn-primary']);

    }
}
