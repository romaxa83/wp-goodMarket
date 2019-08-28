<?php

namespace backend\modules\product\models;

use yii\grid\ActionColumn;

class CustomActionColumn extends ActionColumn {

    public $filter;

    protected function renderFilterCellContent() {
        return $this->filter;
    }

}
