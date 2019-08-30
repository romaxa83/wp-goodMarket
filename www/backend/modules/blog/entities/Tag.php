<?php

namespace backend\modules\blog\entities;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use Ausi\SlugGenerator\SlugGenerator;

/**
 * @property integer $id
 * @property string $title
 * @property string $alias
 * @property integer $status
*/

class Tag extends ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    public static function tableName():string
    {
        return '{{%blog_tags}}';
    }

    public static function create($title,$alias):self
    {
        $tag = new static();
        $tag->title = $title;
        $tag->alias = $alias;
        return $tag;
    }

    public static function generateAlias($tag) : string
    {
       $generator = new SlugGenerator();
       return $generator->generate($tag);
    }

    public function edit($title,$alias):void
    {
        $this->title = $title;
        $this->alias = $alias;
    }

    public function status($status):void
    {
        $this->status = $status;
    }

    //Relation
    /**
     * @return ActiveQuery
     */
    public function getTagAssignments(): ActiveQuery
    {
        return $this->hasMany(TagAssignment::class, ['tag_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTagReviewAssignments(): ActiveQuery
    {
        return $this->hasMany(TagReviewAssignment::class, ['tag_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getPosts(): ActiveQuery
    {
        return $this->hasMany(Post::class, ['id' => 'post_id'])->orderBy(['published_at' => SORT_DESC])->via('tagAssignments');
    }

    /**
     * @return ActiveQuery
     */
    public function getHotelReview(): ActiveQuery
    {
        return $this->hasMany(HotelReview::class, ['id' => 'hotel_review_id'])->orderBy(['published_at' => SORT_DESC])->via('tagReviewAssignments');
    }
}
