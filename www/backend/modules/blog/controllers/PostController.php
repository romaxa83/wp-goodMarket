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
use backend\widgets\langwidget\LangWidget;

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
            'model' => $this->findModel($id)
        ]);
    }

    public function actionCreate()
    {
        $form = new PostForm();
        $langModel = new PostLang();
        $post = Yii::$app->request->post();

        if(Yii::$app->request->isPost){
            if ($form->load($post) && $form->validate() && LangWidget::validate($langModel,$post)) {
                try {
                    $postModel = $this->post_service->create($form);
                    $langModel->saveLang($post['PostLang'],$postModel->id);
    
                    Yii::$app->session->setFlash('success', 'Пост создан');
                    return $this->redirect(['update?id=' . $postModel->id]);
                } catch (\DomainException $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('error', $e->getMessage());
                }
            }else{
                $langModel->languageData = $post['PostLang'];
            }
        }

        return $this->render('create', [
            'model' => $form,
            'langModel' => $langModel
        ]);
    }

    public function actionUpdate($id)
    {
        $form = new PostForm($this->findModel($id));
        $langModel = new PostLang();

        foreach ($form->_post['manyLang'] as $indexRow => $oneLang) {
            $langAlias = $form->_post['aliasLang'][$indexRow]->alias;
            
            $langModel->languageData[$langAlias]['title'] = $oneLang->title;
            $langModel->languageData[$langAlias]['description'] = $oneLang->title;
            $langModel->languageData[$langAlias]['content'] = $oneLang->title;
        }
        $post = Yii::$app->request->post();
        
        if ($form->load($post) && $form->validate()) {
            try {
                $this->post_service->edit($id, $form);
                $this->postLang->updateLang($post['PostLang'],$id);

                Yii::$app->session->setFlash('success', 'Пост отредактирован');
                return $this->redirect(['update?id=' . $id]);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }

        return $this->render('update', [
            'model' => $form,
            'langModel' => $langModel
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
        if (($model = Post::find()->where(['id' => $id])->with(['manyLang','aliasLang','categoryTitle'])->one()) !== null) {
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
