<?php
namespace backend\modules\blog\repository;

use backend\modules\blog\entities\Tag;

class TagRepository
{
    /**
     * @param $id
     * @return Tag
     */
    public function get($id): Tag
    {
        if (!$tag = Tag::findOne($id)) {
            throw new \DomainException('Tag is not found.');
        }
        return $tag;
    }

    /**
     * @return array|bool|\yii\db\ActiveRecord[]
     */
    public function getAll()
    {
        if (!$tags = Tag::find()->where(['status' => Tag::STATUS_ACTIVE])->all()) {
            return false;
        }
        return $tags;
    }

    /**
     * @param $title
     * @return Tag|null
     */
    public function findByName($title): ?Tag
    {
        return Tag::findOne(['title' => $title]);
    }

    /**
     * @param $alias
     * @return Tag|null
     */
    public function findByAlias($alias): ?Tag
    {
        return Tag::findOne(['alias' => $alias]);
    }

    /**
     * @param Tag $tag
     */
    public function save(Tag $tag): void
    {
//        if($existTag = $this->existTag($tag->alias)){
//            $tag->alias = $tag->alias . '_' . $existTag->id;
//        }
        if (!$tag->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @param Tag $tag
     */
    public function remove(Tag $tag): void
    {
        if (!$tag->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

    private function existTag($tag)
    {
        return Tag::find()->where(['alias' => $tag])->orderBy(['id' => SORT_DESC])->limit(1)->one();
    }
}
