<?php

namespace backend\modules\product\models;

use yii\grid\SerialColumn;

class CustomSerialColumn extends SerialColumn {

    public $filter;

    protected function renderFilterCellContent() {
        return $this->filter;
    }

}
