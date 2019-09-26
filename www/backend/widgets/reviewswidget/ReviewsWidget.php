<?php

namespace backend\widgets\reviewswidget;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\data\ArrayDataProvider;
use backend\widgets\reviewswidget\ReviewsWidgetAsset;
use backend\modules\reviews\models\Reviews;
use backend\modules\reviews\models\ReviewForm;
use frontend\models\User;

class ReviewsWidget extends Widget {

    public $review_type;
    public $form_visible = true;
    public $page_size = 5;
    public $id = 1;
    public function init() {
        parent::init();
        Yii::setAlias('@reviewswidget-assets', __DIR__ . '/assets');
        ReviewsWidgetAsset::register(Yii::$app->view);
    }

    public function run() {
        if ($this->review_type == 'good') {
            $name_id = 'reviews.product_id';
        } else if ($this->review_type == 'user') {
            $name_id = 'reviews.user_id';
        }
        $condition = $name_id . '=' . $this->id;
        $request = Reviews::getFrontReviews($condition);
        $request = ArrayHelper::index($request, 'id');
        arsort($request);
        foreach ($request as $key => $value){
            if ($value['answer_id'] != 0){
                $request[$value['answer_id']]['answers'][] = ArrayHelper::remove($request, $key);
            }
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $request,
            'pagination' => [
                'pageSize' => $this->page_size,
            ]
        ]);
        $model = new ReviewForm();
        return $this->render('_reviews', [
            'dataProvider' => $dataProvider,
            'model' => $model,
            'product_id' => $this->id,
            'form_visible' => $this->form_visible]);
    }

}
