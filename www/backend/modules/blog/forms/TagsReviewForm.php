<?php

namespace backend\modules\blog\forms;

use backend\modules\blog\entities\HotelReview;
use backend\modules\blog\entities\Tag;
use backend\modules\blog\entities\TagAssignment;
use backend\modules\blog\entities\TagReviewAssignment;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class TagsReviewForm extends Model
{
    public $existing = [];

    public $tags_arr;

    public function __construct(HotelReview $hotel_review = null, $config = [])
    {
        if ($hotel_review) {
            $this->existing = $this->checkTagList($hotel_review->id);
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
        return ArrayHelper::map(Tag::find()->andWhere(['status' => Tag::STATUS_ACTIVE])->orderBy('title')->asArray()->all(), 'title', 'title');
    }

    public function checkTagList($hotel_review_id)
    {
        $tags = ArrayHelper::map(TagReviewAssignment::find()->where(['hotel_review_id' => $hotel_review_id])->asArray()->all(),'tag_id','tag_id');
        return ArrayHelper::getColumn(Tag::find()->where(['in','id',$tags])->andWhere(['status' => Tag::STATUS_ACTIVE])->asArray()->all(),'title');

    }

}
