<?php

namespace backend\modules\reviews\models;

use Yii;
use yii\base\Model;
use backend\modules\reviews\models\Reviews;
use backend\modules\product\models\Product;
use yii\data\ArrayDataProvider;
use \yii\helpers\ArrayHelper;

class ReviewsSearch extends Model {

    public $product_name;
    public $full_name;
    public $date;
    public $rating;

    public function rules() {
        return [
                [['full_name', 'rating', 'date', 'product_name'], 'safe']
        ];
    }

    private function attachAnswerToReview($reviews, $answers = []) {

        if (!empty($answers)) {

            $reviews = array_values($reviews);
            $answers = array_filter($answers, function($element) use ($reviews) {
                return in_array($element['answer_id'], array_column($reviews, 'id'));
            });

            foreach ($answers as $answer) {
                foreach ($reviews as $key => $review) {
                    if ($review['id'] == $answer['answer_id']) {
                        array_splice($reviews, $key + 1, 0, [$answer]);
                        break;
                    }
                }
            }
        }

        return $reviews;
    }

    private function processFields($reviews) {
        foreach ($reviews as $key => $rw) {
            $rw['full_name'] = ($rw['user_id'] == 0) ? 'Гость' : $rw['full_name'];
            $rw['product_name'] = isset($rw['productLang'][0]['name']) ? $rw['productLang'][0]['name'] : 'Нет имени';

            $reviews[$key] = $rw;
        }

        return $reviews;
    }

    private function formattedDate($date) {

        $date = explode('-', $date);

        $date_start = strtotime(trim($date[0]) . ' 00:00:00');
        $date_end = strtotime(trim($date[1]) . '00:00:00');

        return ['begin' => $date_start, 'end' => $date_end];
    }

    public function filter($array, $index, $value) {

        $data = [];

        if (is_array($array) && count($array) > 0) {

            foreach (array_keys($array) as $key) {

                if (isset($array[$key][$index])) {
                    $temp[$key] = $array[$key][$index];
                }

                if (isset($temp[$key])) {

                    if ($index == 'date') {

                        $date = $this->formattedDate($value);
                        $temp[$key] = strtotime($temp[$key]);

                        if (($temp[$key] >= $date['begin']) && ($temp[$key] <= $date['end'])) {
                            $data[$key] = $array[$key];
                        }
                    } else if (mb_strripos($temp[$key], (string) $value) !== FALSE) {
                        $data[$key] = $array[$key];
                    }
                }
            }
        }

        return $data;
    }

    public function search($params) {

        $reviews = Reviews::getReviews();
        $answers = Reviews::getAnswers();

        $reviews = $this->processFields($reviews);

        if (isset($params['ReviewsSearch'])) {
            foreach ($params['ReviewsSearch'] as $k => $v) {
                if (!empty($v)) {
                    $reviews = $this->filter($reviews, $k, $v);
                }
            }
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $reviews,
            'sort' => [
                'attributes' => ['full_name', 'rating', 'date', 'product_name']
            ],
            'pagination' => [
                'pageSize' => 15
            ]
        ]);

        $review_answer = $this->attachAnswerToReview($dataProvider->getModels(), $answers);
        $review_answer = $this->processFields($review_answer);
        $dataProvider->setModels($review_answer);
        $dataProvider->setKeys(array_keys($review_answer));

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

}
