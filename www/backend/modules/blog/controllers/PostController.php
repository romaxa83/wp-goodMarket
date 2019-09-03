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
use backend\modules\blog\forms\PostForm;
use backend\modules\blog\type\MessageType;
use backend\modules\blog\services\PostService;
use backend\modules\blog\forms\search\PostSearch;
use backend\modules\blog\repository\PostRepository;
use backend\modules\blog\entities\PostLang;

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
    private $postLang;

    public function __construct(
        $id, Module $module,
        PostService $posts,
        PostRepository $postRepository,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->post_service = $posts;
        $this->postRepository = $postRepository;
        $this->postLang = new PostLang();
    }

    public function actionIndex()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'post' => $this->findModel($id)
        ]);
    }

    public function actionCreate()
    {
        $form = new PostForm();
        $post = Yii::$app->request->post();

        if ($form->load($post) && $form->validate()) {
            try {
                $form->title = $post['PostForm']['Language']['ru']['title'];
                $form->content = $post['PostForm']['Language']['ru']['content'];
                $form->description = $post['PostForm']['Language']['ru']['description'];
                
                $modelPost = $this->post_service->create($form);
                $resultat = $this->postLang->saveLang($post['PostForm']['Language'],$modelPost->id);

                Yii::$app->session->setFlash('success', 'Пост создан');
                return $this->redirect(['update?id='.$modelPost->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    public function actionUpdate($id)
    {
        //get base model
        $postModel = $this->postRepository->get($id);
        //get by related model with lang
        $relatedRecord = $postModel->getAllLangRow()->all();
        $form = new PostForm($postModel);
        //cycle for assembly languageData
        $data['Language'] = [];
        foreach ($relatedRecord as $oneLang) {
            //get by related alias lang
            $langSetting = $oneLang->getLang()->select('alias')->one();
            $data['Language'][$langSetting->alias]['title'] = $oneLang->title;
            $data['Language'][$langSetting->alias]['description'] = $oneLang->description;
            $data['Language'][$langSetting->alias]['content'] = $oneLang->content;
        }
        $form->languageData = $data;
        $post = Yii::$app->request->post();
        
        if ($form->load($post) && $form->validate()) {
            try {
                $this->post_service->edit($postModel->id, $form);
                Yii::$app->session->setFlash('success', 'Пост отредактирован');
                $resultat = $this->postLang->updateLang($post['PostForm']['Language'],$postModel->id);

                return $this->redirect(['update?id='.$modelPost->id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'post' => $postModel
        ]);
    }

    public function actionStatusChange()
    {
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

    public function actionViewMainPage()
    {
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

    public function actionSetPosition()
    {
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

    public function actionDelete($id)
    {
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
