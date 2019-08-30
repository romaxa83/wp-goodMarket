<?php

namespace backend\modules\blog\controllers;

use Yii;
use yii\base\Module;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use backend\modules\user\useCase\Access;
use backend\modules\blog\helpers\StatusHelper;
use backend\modules\blog\services\ReviewService;
use backend\modules\referenceBooks\models\HotelReview as Review;
use backend\modules\referenceBooks\models\HotelReviewSearch as ReviewSearch;

class ReviewController extends Controller
{
    /**
     * @var ReviewService
     */
    private $reviewService;

    /**
     * @var $access Access
     */
    private $access;

    public function __construct($id, Module $module,
                                ReviewService $reviewService,array $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->reviewService = $reviewService;
        $this->access = new Access();
    }

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @perm('Просмотр отзывов (блог)')
     */
    public function actionIndex()
    {
        $this->access->accessAction();
        $searchModel = new ReviewSearch();
        $page = Yii::$app->user->identity->getSettings('review-for-hotel')['count-page'][0]??10;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$page);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user_settings' => Yii::$app->user->identity->getSettings('review-for-hotel'),
            'page' => $page,
            'access' => $this->access
        ]);
    }

    /**
     * @perm('Редактирование отзыва (блог)')
     */
    public function actionUpdate($id)
    {
        $this->access->accessAction();
        $review = $this->findModel($id);

        $review->scenario = 'update';
        if ($review->load(Yii::$app->request->post()) && $review->validate()) {
            try {

                $review->save();
                Yii::$app->session->setFlash('success', 'Обзор на отель отредоктирован');
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', [
            'model' => $review
        ]);
    }

    /**
     * @perm('Смена статуса отзыва (блог)')
     */
    public function actionStatusChange()
    {
        $this->access->accessAction();
        $post = Yii::$app->request->post();
        try{
            $this->reviewService->changeStatus($post['id'],$post['checked']);
            Yii::$app->session->setFlash('success',StatusHelper::infoFlash($post['checked'],'Отзыв'));

        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(Url::toRoute('/blog/review/index'));
    }

    /**
     * @perm('Удаление отзыва (блог)')
     */
    public function actionDelete($id)
    {
        $this->access->accessAction();
        try {
            $this->reviewService->remove($id);
            Yii::$app->session->setFlash('success', 'Отзыв на отель удален');
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }


    protected function findModel($id) : Review
    {
        if (($model = Review::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
