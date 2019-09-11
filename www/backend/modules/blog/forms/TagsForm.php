<?php

namespace backend\modules\blog\forms;

use backend\modules\blog\entities\Post;
use backend\modules\blog\entities\Tag;
use backend\modules\blog\entities\TagAssignment;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class TagsForm extends Model
{
    public $existing = [];

    public $tags_arr;

    public function __construct(Post $post = null, $config = [])
    {
        if ($post) {
            $this->existing = $this->checkTagList($post->id);
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['existing', 'each', 'rule' => ['string']],
            ['existing', 'default', 'value' => []],
        ];
    }

    public function tagsList(): array
    {
        $tag = Tag::find()->andWhere(['status' => Tag::STATUS_ACTIVE])->with('oneLang')->asArray()->all();
        $preparedTagList = [];

        foreach($tag as $oneElement){
            $title = $oneElement['oneLang']['title'];
            $preparedTagList[$title] = $title;
        }

        return $preparedTagList;
    }

    public function checkTagList($post_id)
    {
        $tagAssignment = ArrayHelper::map(TagAssignment::find()->where(['post_id' => $post_id])->asArray()->all(),'tag_id','tag_id');
        $tag = Tag::find()->where(['in','id',$tagAssignment])->andWhere(['status' => Tag::STATUS_ACTIVE])->with('oneLang')->asArray()->all();

        $preparedTagList = [];
        foreach($tag as $oneElement){
            $title = $oneElement['oneLang']['title'];
            $preparedTagList[$title] = $title;
        }
        return $preparedTagList;
    }

}
