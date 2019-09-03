<?php

namespace backend\modules\blog\forms\queries;

use yii\db\ActiveQuery;
use backend\modules\blog\entities\Post;

class PostQuery extends ActiveQuery
{
    /**
     * @param null $alias
     * @return $this
     */
    public function active($alias = null)
    {
        return $this->andWhere([
            ($alias ? $alias . '.' : '') . 'status' => Post::ACTIVE,
        ]);
    }
}