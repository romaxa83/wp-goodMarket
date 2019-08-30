<?php

namespace backend\modules\blog\entities;

use yii\db\ActiveRecord;

/**
 * @property integer $hotel_review_id
 * @property integer $tag_id
 */
class TagReviewAssignment extends ActiveRecord
{
    public static function create($tag_id) : self
    {
        $assignment = new static();
        $assignment->tag_id = $tag_id;

        return $assignment;
    }

    public static function tableName() : string
    {
        return '{{%blog_tag_review_assign}}';
    }
}