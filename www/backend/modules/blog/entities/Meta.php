<?php

namespace backend\modules\blog\entities;

use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property integer $page_id
 * @property string $h1
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property string $seo_text
 * @property string $language
 * @property string $alias
 */

class Meta extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%seo_meta}}';
    }

    public static function create(
        $post_id,
        $h1,
        $title,
        $keywords,
        $description,
        $seo_text,
        $alias): self
    {
        $meta = new static();
        $meta->page_id = $post_id;
        $meta->h1 = $h1;
        $meta->title = $title;
        $meta->keywords = $keywords;
        $meta->description = $description;
        $meta->seo_text = $seo_text;
        $meta->language = 'ru';
        $meta->alias = $alias;

        return $meta;
    }

    public function edit(
        $h1,
        $title,
        $keywords,
        $description,
        $seo_text): void
    {
        $this->h1 = $h1;
        $this->title = $title;
        $this->keywords = $keywords;
        $this->description = $description;
        $this->seo_text = $seo_text;
    }
}
