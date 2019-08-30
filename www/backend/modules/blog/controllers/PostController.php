<?php

namespace backend\modules\blog\controllers;

use Yii;
use yii\base\Module;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use backend\modules\blog\entities\Post;
use backend\modules\user\useCase\Access;
use backend\modules\blog\forms\PostForm;
use backend\modules\blog\type\MessageType;
use backend\modules\blog\services\PostService;
use backend\modules\blog\forms\search\PostSearch;
use backend\modules\blog\repository\PostRepository;

class PostController extends Controller
{
    /**
     * @var PostService
     */
    private $post_service;
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var $access Access
     */
    private $access;

    public function __construct($id, Module $module,
                                PostService $posts,
                                PostRepository $postRepository,
                                array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->post_service = $posts;
        $this->postRepository = $postRepository;
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
     * @perm('Просмотр всех постов (блог)')
     */
    public function actionIndex()
    {
        $this->access->accessAction();
        $searchModel = new PostSearch();
        $page = Yii::$app->user->identity->getSettings('post')['count-page'][0]??10;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$page);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user_settings' => Yii::$app->user->identity->getSettings('post'),
            'page' => $page,
            'access' => $this->access
        ]);
    }

    /**
     * @perm('Просмотр поста (блог)')
     */
    public function actionView($id)
    {
        $this->access->accessAction();
        return $this->render('view', [
            'post' => $this->findModel($id),
            'access' => $this->access
        ]);
    }

    /**
     * @perm('Создать пост (блог)')
     */
    public function actionCreate()
    {
        $this->access->accessAction();
        $form = new PostForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->post_service->create($form);
                Yii::$app->session->setFlash('success', 'Пост создан');
                return $this->redirect(['index']);

            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }

        }
        return $this->render('create', [
            'model' => $form,
        ]);
    }

    /**
     * @perm('Редактировать пост (блог)')
     */
    public function actionUpdate($id)
    {
        $this->access->accessAction();
        $post = $this->postRepository->getWithSeo($id);

        $form = new PostForm($post);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->post_service->edit($post->id, $form);
                Yii::$app->session->setFlash('success', 'Пост отредактирован');
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', [
            'model' => $form,
            'post' => $post,
        ]);
    }

    /**
     * @perm('Изменить статус публикации поста (блог)')
     */
    public function actionStatusChange()
    {
        $this->access->accessAction();
        $post = Yii::$app->request->post();
        try{
            $status = $this->post_service->changeStatus($post['id'],$post['checked']);

            $this->setFlash($status);

        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(Url::toRoute('/blog/post/index'));
    }

    /**
     * @perm('Вывести пост на главную (блог)')
     */
    public function actionViewMainPage()
    {
        $this->access->accessAction();
        $post = Yii::$app->request->post();
        try{
            $status = $this->post_service->inMain($post['id'],$post['checked']);
            $this->setFlash($status);

        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(Url::toRoute('/blog/post/index'));
    }

    /**
     * @perm('Установить позицию поста (блог)')
     */
    public function actionSetPosition()
    {
        $this->access->accessAction();
        $post = Yii::$app->request->post();
        try{
            $status = $this->post_service->setPosition($post['post_id'],$post['position']);
            $this->setFlash($status);

        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(Url::toRoute('/blog/post/index'));
    }

    /**
     * @perm('Удалить пост (блог)')
     */
    public function actionDelete($id)
    {
        $this->access->accessAction();
        try {
            $this->post_service->remove($id);
            Yii::$app->session->setFlash('success', 'Пост удален');
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }

    protected function findModel($id) : Post
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    private function setFlash(MessageType $message)
    {
        if($message->getType() == 'error'){
            Yii::$app->session->setFlash('danger',$message->getMessage());
        } else {
            Yii::$app->session->setFlash('success',$message->getMessage());
        }
    }
}
