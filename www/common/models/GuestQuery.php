<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Guest]].
 *
 * @see Guest
 */
class GuestQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Guest[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Guest|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
