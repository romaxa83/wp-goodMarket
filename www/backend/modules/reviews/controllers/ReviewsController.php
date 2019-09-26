<?php

namespace backend\modules\reviews\controllers;
use Yii;
use yii\helpers\Html;
use backend\controllers\BaseController;
use backend\modules\reviews\models\Reviews;
use backend\modules\reviews\models\ReviewForm;
use backend\modules\reviews\models\AnswerForm;
use yii\data\ActiveDataProvider;
use yii\bootstrap\ActiveForm;
use backend\modules\product\models\Product;
use backend\modules\product\controllers\ProductController;
use yii\web\Response;
use backend\modules\reviews\models\ReviewsSearch;
use common\controllers\AccessController;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

class ReviewsController extends BaseController
{
    public function behaviors()
    {
        $rules = [
            [
                'allow' => true,
                'actions' => ['add-review', 'show-answer-form', 'validate', 'update-stats'],
                'roles'=> ['@', '?']
            ]
        ];
        $rules = array_merge($rules,  AccessController::getAccessRules(Yii::$app->controller));
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => $rules,
                'denyCallback' => function($rule, $action) {
                   throw new ForbiddenHttpException('Вам не разрешено производить данное действие.');
                }
            ],
        ];
    }

    // public function beforeAction($action)
    // {
    //     if (parent::beforeAction($action)) {
    //         if (!AccessController::checkPermission($action->controller->route)) {
    //             throw new ForbiddenHttpException('Вам не разрешено производить данное действие.');
    //         }
    //         return parent::beforeAction($action);
    //     } else {
    //         return false;
    //     }
    // }

     public function beforeAction($action)
    {
        if ($action->id == 'add-review' || $action->id == 'show-answer-form' || $action->id == 'validate' || $action->id == 'update-stats') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    private static function getStockId($model, $id){
        return $model->className()::find()->select('stock_id')->where(['id'=>$id])->asArray()->one()['stock_id'];
    }

    private function getCategoryID($product_id){
        return Product::find()->select('category_id')->where(['stock_id'=>$product_id])->one()->category_id;
    }

    public function actionIndex()
    {
        $search_model = new ReviewsSearch();
        $dataProvider = $search_model->search(Yii::$app->request->get());
        $answer_model = new AnswerForm();
        return $this->render('index',[
            'dataProvider' => $dataProvider,
            'searchModel' => $search_model,
            'answer_model' => $answer_model,
            'user_settings' => Yii::$app->user->identity->getSettings('review')
        ]);
    }

    public function actionValidate($action) {
        if (Yii::$app->request->isAjax) {
            if($action=='addAnswer'){
                $model = new AnswerForm();
            }else{
                $model = new ReviewForm();
            }

            if ($model->load(Yii::$app->request->post()) && $model->validate(false)) {
                return $this->asJson(['success' => true]);
            }
            $result = [];

            foreach ($model->getErrors() as $attribute => $errors) {
                $result[Html::getInputId($model, $attribute)] = $errors;
            }

            return $this->asJson(['validation' => $result]);
        }
    }

    public function actionAddReview(){
         if(Yii::$app->request->isAjax){
            if(Yii::$app->request->post()){
                $data = Yii::$app->request->post();
                $model = new Reviews();
                $model->user_id = $data['user_id'];
                $model->text = $data['text'];
                if($data['action']=='addAnswer'){
                    $model->answer_id = $data['parent_id'];
                    $model->rating = 0;
                    $model->product_id = Reviews::getProdoctID($data['parent_id']);
                    $type = 'answer';
                }else{
                    $model->answer_id = 0;
                    $model->product_id = $data['product_id'];
                    if(empty($data['rating'])){
                        $model->rating = 0;
                    }else{
                        $model->rating = $data['rating'];
                    }
                    $type = 'review';
                }

                $model->date = date("Y-m-d H:i:s");
                $model->publication = 1;
                if($model->save()){
                    $new_model = $model->getFrontReviewOne();
                    if($data['action']=='addAnswer'){
                        $parent = Reviews::getReview($data['parent_id']);
                        $parent->answered = 1;
                        $parent->save();
                    }else{
                        if($product = Product::findOne(['id'=>$model->product_id])){
                            $primary_id = (is_null($product->stock_id))?$product->import_id:$product->stock_id;
                            $product_cache = ProductController::getProductList()[$primary_id];
                            if(!array_key_exists('admin_prod_rating', isset($product_cache['fields']) ? $product_cache['fields'] : [])){
                                $product->rating = 20*Reviews::getAverageRating($product->id);
                                $product->save();
                            }
                        }
                    }
                    return $this->renderPartial('@backend/widgets/reviewswidget/views/_list_item',[
                        'model'=>$new_model,
                        'type'=>$type,
                        'user_id'=>$data['user_id']
                    ]);
                }
            }
        }
    }

    public function actionShowAnswerFormBack(){
        if(Yii::$app->request->isAjax){
            $answer_model = new AnswerForm();
            return $this->renderAjax('answer_form',['model'=>$answer_model]);
        }
    }

    public function actionShowAnswerForm(){
        if(Yii::$app->request->isAjax){
            if(Yii::$app->request->post()){
                $data = Yii::$app->request->post();
                $review_id = $data['review_id'];
                $answer_model = new AnswerForm();
                $product_id = Reviews::getProdoctID($review_id);
                return $this->renderPartial('@backend/widgets/reviewswidget/views/_answer_form',['model'=>$answer_model, 'product_id'=>$product_id]);
            }
        }
    }

    public function actionUpdateStatus() {
        if(Yii::$app->request->isAjax){
            if(Yii::$app->request->post()){
                $data = Yii::$app->request->post();
                $record_id = $data['id'];
                $status = $data['checked'];
                $review_model =  Reviews::getReview($record_id);
                if($review_model->answer_id==0){
                    Reviews::updateAll(['publication'=>$status],['=','answer_id',$record_id]);
                }
                $review_model->publication = $status;
                $review_model->save();
            }
         }
    }

    public function getCountText($count){
        if((($count % 100)>10) && (($count % 100)<20)){
            return 'отзывов';
        }
        $end_number = $count % 10;
        switch (true) {
            case $end_number==1: return 'отзыв';
            break;
            case ($end_number>=2 && $end_number<=4): return 'отзыва';
            break;
            default: return 'отзывов';
            break;
        }
    }

    public function actionUpdateStats(){
        if(Yii::$app->request->isAjax){
            if(Yii::$app->request->post()){
                $data = Yii::$app->request->post();
                $product_id = $data['product_id'];
                $color = $data['color'];
                $count = Reviews::getProductsCount($product_id);
                $text = $count.' '.$this->getCountText($count);
                $avg_rating = Reviews::getAverageRating($product_id);
                return $this->renderAjax('@backend/widgets/stats_reviews_widget/views/_stats_review',[
                    'product_id'=>$product_id,
                    'text'=>$text,
                    'avg_rating'=>$avg_rating,
                    'visible_reviews_count' => true,
                    'color'=>$color,
                    'block_name'=>''
                ]);
            }
        }
    }
}
