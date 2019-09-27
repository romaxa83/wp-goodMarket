<?php

namespace backend\modules\blog\services;

use backend\modules\referenceBooks\models\HotelReview as Review;
use Carbon\Carbon;
use yii\db\Exception;
use yii\helpers\Html;

class ReviewService
{
    public function changeStatus($id, $status): void
    {
        $review = $this->getReviewById($id);
        $review->status = $status;
        $review->save(false);
    }

    public function createReview($post)
    {
        $review = new Review();
        $review->title = Html::encode($post['title']);
        $review->comment = Html::encode($post['comment']);
        $review->vote = (int)$post['vote'] * 2;
        $review->hotel_id = $post['hotel_id'];
        $review->user_id = $post['user_id'];
        $review->status = Review::STATUS_DRAFT;
        $review->avatar = !empty($post['avatar']) ? '/admin/uploads/avatar/'.$post['avatar'] : '/admin/img/no_ava.png';
        $review->date = Carbon::now();

        if(!$review->save()){
            throw new Exception('Error for saving review');
        }
    }

    public function remove($id)
    {
        $review = $this->getReviewById($id);
        $review->delete();
    }

    private function getReviewById($id) : Review
    {
        return Review::findOne($id);
    }
}
