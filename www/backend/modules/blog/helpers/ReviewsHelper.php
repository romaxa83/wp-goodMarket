<?php

namespace backend\modules\blog\helpers;

class ReviewsHelper
{
    public $all_reviews = [
        5 => 'Отлично',
        4 => 'Очень хорошо',
        3 => 'Неплохо',
        2 => 'Плохо',
        1 => 'Ужасно'
    ];

    public function getDataReviews($reviews):array
    {
        $count_reviews = count($reviews);
        $middle = $this->all_reviews;
        $arr = [
            'Отлично' => 0,
            'Очень хорошо' => 0,
            'Неплохо' => 0,
            'Плохо' => 0,
            'Ужасно' => 0
        ];
        foreach($reviews as $review){
            if(array_key_exists($this->getRatingForFiveSystem($review->vote),$this->all_reviews)){
                $arr[$this->all_reviews[$this->getRatingForFiveSystem($review->vote)]] += 1;
            }
        }

        $data = array_map(function($item,$key) use ($middle,$count_reviews){
            return [
                'name' => $key,
                'count' => $item,
                'type' => array_search($key,$middle),
                'percent' => $this->calculatePercent($count_reviews,$item),
            ];
        },$arr,array_keys($arr));

        return $data;
    }

    public function getDataNotReviews():array
    {
        $arr = [];
        for($i = 5 ; $i > 0 ; $i--){
            $arr[$i]['name'] = $this->all_reviews[$i];
            $arr[$i]['count'] = 0;
            $arr[$i]['type'] = $i;
            $arr[$i]['percent'] = 0;
        }
        return $arr;
    }

    public function getRatingForFiveSystem($vote)
    {
        return (int) round($vote/2);
    }

    public function getCountReview(array $reviews)
    {
        return count($reviews);
    }

    public function getReviewsRating(array $reviews)
    {
        if(empty($reviews)){
            return '0.0';
        }

        $all_vote = array_reduce(array_column($reviews,'vote') ,function($carry,$item){
           return $carry += $item;
        });

        $rating = (int)$all_vote / (int)count($reviews);

        return number_format($rating,2);
    }

    private function calculatePercent($all_count,$number)
    {
        if($number != 0){
            return ((int)$number*100)/$all_count;
        }
        return 0;
    }
}
