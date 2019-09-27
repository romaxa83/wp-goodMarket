<?php

namespace backend\modules\blog\components;

use yii\grid\ActionColumn;
use yii\helpers\Html;

class CustomActionColumn extends ActionColumn
{
    protected function renderFilterCellContent()
    {
        return Html::a('Сброс','?', ['class' => 'btn btn-primary']);

    }
}