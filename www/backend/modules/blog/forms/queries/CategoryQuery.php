<?php

namespace backend\modules\blog\forms\queries;

use yii\db\ActiveQuery;
use paulzi\nestedsets\NestedSetsQueryTrait;

class CategoryQuery extends ActiveQuery
{
    use NestedSetsQueryTrait;
}