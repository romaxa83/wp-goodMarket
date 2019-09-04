<?php

namespace backend\modules\blog\entities;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use Ausi\SlugGenerator\SlugGenerator;
use common\models\Lang;

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

    public $title;

    public static function tableName():string
    {
        return '{{%blog_tag}}';
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
    public function getPosts(): ActiveQuery
    {
        return $this->hasMany(Post::class, ['id' => 'post_id'])->orderBy(['published_at' => SORT_DESC])->via('tagAssignments');
    }

    public function getOneLang()
    {   
        return $this->hasOne(TagLang::class, ['tag_id' => 'id']);
    }

    public function getManyLang()
    {   
        return $this->hasMany(TagLang::class, ['tag_id' => 'id']);
    }

    public function getAliasLang()
    {   
        return $this->hasMany(Lang::class, ['id' => 'lang_id'])->via('manyLang');
    }
}
