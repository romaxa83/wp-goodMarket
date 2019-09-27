<?php

namespace backend\modules\blog\repository;

use backend\modules\blog\entities\TagAssignment;

class TagAssignmentsRepository
{
    public function save($tags,$post_id): void
    {
        \Yii::$app->db->createCommand()->batchInsert(
            'blog_tag_assignment',['post_id','tag_id'],
            array_map(function($item) use ($post_id) {
                return [
                    'post_id' => $post_id,
                    'tag_id' => $item,
                ];
            },$tags))->execute();

    }

    public function delete($id): void
    {
        if($this->isTagAssignment($id)){
            TagAssignment::deleteAll(['post_id' => $id]);
        }
    }

    public function isTagAssignment($post_id)
    {
        return TagAssignment::find()->andWhere(['post_id' => $post_id])->exists();
    }
}
