<?php

namespace backend\modules\blog\entities;

use backend\modules\blog\helpers\DateHelper;
use backend\modules\referenceBooks\models\Country;
use Carbon\Carbon;
use common\models\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use backend\modules\filemanager\models\Mediafile;

/**
 * @property integer $id
 * @property integer $category_id
 * @property integer $country_id
 * @property integer $author_id
 * @property integer $seo_id
 * @property string $title
 * @property string $alias
 * @property string $description
 * @property string $content
 * @property integer $media_id
 * @property integer $views
 * @property integer $likes
 * @property integer $links
 * @property integer $comments
 * @property integer $position
 * @property integer $status
 * @property integer $published_at
 * @property integer $created_at
 * @property integer $updated_at
 */

class Post extends ActiveRecord
{
    const INACTIVE = 0;
    const ACTIVE = 1;
    const DRAFT = 2;

    const ON_MAIN_PAGE = 1;
    const NOT_MAIN_PAGE = 0;

    public static function tableName(): string
    {
        return '{{%blog_post}}';
    }

    public static function create(
        $category_id,
        $author_id,
        $title,
        $alias,
        $description,
        $content,
        $media_id,
        $status,
        $published_at
    ): self
    {
        $post = new static();
        $post->category_id = $category_id;
        $post->author_id = $author_id;
        $post->alias = $alias;
        $post->media_id = $media_id;
        $post->status = self::setStatus((int)$status,DateHelper::convertPublishedForUnix($published_at));
        $post->published_at = ($published_at) ? DateHelper::convertPublishedForUnix($published_at):false;
        $post->created_at = time();
        $post->updated_at = time();

        return $post;
    }

    public function edit(
        $category_id,
        $country_id,
        $title,
        $alias,
        $description,
        $content,
        $media_id,
        $status,
        $published_at): void
    {

        $this->category_id = $category_id;
        $this->country_id = $country_id != '' ? $country_id : null;
        $this->title = $title;
        $this->alias = $alias;
        $this->description = $description;
        $this->content = $content;
        $this->media_id = $media_id;
        $this->status = self::setStatus((int)$status,DateHelper::convertPublishedForUnix($published_at));
        $this->published_at = DateHelper::convertPublishedForUnix($published_at);
        $this->updated_at = time();
    }

    /**
     * @param $status
     */
    public function status($status):void
    {
        $this->status = $status;
        $this->published_at = time();
        $this->updated_at = time();
    }
    
    public function setPosition($position):void
    {
        $this->position = $position;
        $this->updated_at = time();
    }

    public function addView():void
    {
        $this->views += 1;
        $this->updated_at = time();
    }

    /**
     * @param $seo_id
     */
    public function addSeo($seo_id) :void
    {
        $this->seo_id = $seo_id;
    }
    //Relation

    /**
     * @return ActiveQuery
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getSeo(): ActiveQuery
    {
        return $this->hasOne(Meta::class, ['page_id' => 'id'])->andWhere(['alias' => 'post']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTagAssignments(): ActiveQuery
    {
        return $this->hasMany(TagAssignment::class, ['post_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getTags(): ActiveQuery
    {
        return $this->hasMany(Tag::class, ['id' => 'tag_id'])->via('tagAssignments');
    }

    /**
     * @return ActiveQuery
     */
    public function getMedia(): ActiveQuery
    {
        return $this->hasOne(Mediafile::class, ['id' => 'media_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getComments(): ActiveQuery
    {
        return $this->hasMany(Comment::class, ['post_id' => 'id']);
    }

    public function getLangRow(int $lang_id = 1)
    {   
        return $this->hasOne(PostLang::class, ['post_id' => 'id'])->andWhere(['lang_id' => $lang_id]);
    }

    public function getTitle(int $lang_id = 1)
    {   
        return $this->hasMany(PostLang::class, ['post_id' => 'id']);
    }

    public function getArrayPosition():array
    {
        return [
            0 => 'Позиция',
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
        ];
    }

    public static function setStatus($status,$published_at)
    {

        if($status == self::ACTIVE && $published_at > (Carbon::now())->add(10,'min')->timestamp){

            return self::DRAFT;
        }

        if($status == self::DRAFT && $published_at < (Carbon::now())->add(5,'min')->timestamp){
            return self::ACTIVE;
        }
        return $status;
    }
}