<?php

namespace backend\modules\users\administrators\controllers;

use backend\controllers\BaseController;
use backend\modules\users\roles\models\AuthItem;
use Yii;
use common\models\User;
use app\modules\users\administrators\models\AdministratorsSearch;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use common\controllers\AccessController;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

/**
 * AdministratorsController реализует CRUD-систему для модели Администраторов.
 *  @see https://www.yiiframework.com/doc/api/2.0/yii-web-controller
 */
class AdministratorsController extends BaseController
{
    /**
     * Метод устанавливает поведение классу
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-component#behaviors()-detail
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => AccessController::getAccessRules(Yii::$app->controller),
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (!AccessController::checkPermission($action->controller->route)) {
                throw new ForbiddenHttpException('Вам не разрешено производить данное действие.');
            }
            return parent::beforeAction($action);
        } else {
            return false;
        }
    }

    /**
     * Список всех моделей Администраторов.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdministratorsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Отображает одну модель Администратора.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException если модель не найдена.
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findUser($id),
        ]);
    }

    /**
     * Создает новую модель Администратора.
     * Если создание будет успешным, браузер будет перенаправлен на страницу списка администраторов.
     * @return mixed
     */
    public function actionCreate()
    {
        $auth = Yii::$app->authManager;

        $model = new User();
        $model->scenario = User::REGISTER_ADMINISTRATORS;
        
        $roles = ArrayHelper::map(AuthItem::find()->select('name')->where(['type' => 1])->andWhere(['!=', 'name', 'superAdmin'])->asArray()->all(),'name','name');

        if ($model->load(Yii::$app->request->post())) {

            $isValid = $model->validate();
            
            if ($isValid) {

                $data = Yii::$app->request->post();

                $model = new User();
                $model->first_name = ArrayHelper::getValue($data, 'User.first_name');
                $model->last_name = ArrayHelper::getValue($data, 'User.last_name');
                $model->username = ArrayHelper::getValue($data, 'User.username');
                $model->setPassword(ArrayHelper::getValue($data, 'User.password_hash'));
                $model->generateAuthKey();
                $model->email = ArrayHelper::getValue($data, 'User.email');
                $model->type = 0;
                $model->save();
                $role = ArrayHelper::getValue($data, 'AuthItem.role_name');

                if (isset($role) && !empty($role)) {
                    $auth->assign($auth->getRole($role), $model->id);
                    Yii::$app->session->setFlash('success', 'Вы успешно создали администратора.');
                    return $this->redirect($data['save']);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'roles' => $roles,
        ]);
    }

    /**
     * Обновляет существующую модель Администратора.
     * Если обновление выполнено успешно, браузер будет перенаправлен на страницу просмотра администратора.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException если модель не найдена.
     */
    public function actionUpdate($id)
    {
        $auth = Yii::$app->authManager;

        $model = $this->findUser($id);
        $model->scenario = User::UPDATE_ADMINISTRATORS;

        $roles = ArrayHelper::map(AuthItem::find()->select('name')->where(['type' => 1])->andWhere(['!=', 'name', 'superAdmin'])->asArray()->all(),'name','name');

        if ($model->load(Yii::$app->request->post())) {

            $isValid = $model->validate();
            
            if ($isValid) {

                $data = Yii::$app->request->post();

                $password = ArrayHelper::getValue($data,'User.new_password');
                $model->username = ArrayHelper::getValue($data,'User.username');

                if (isset($password) && !(strlen($password) == 0)){
                    $model->setPassword($password);
                }

                $model->email = ArrayHelper::getValue($data,'User.email');
                $model->save();

                $role = ArrayHelper::getValue($data, 'AuthItem.role_name');
            
                if (isset($role) && $role != '0') {
                    $auth->revokeAll($model->id);
                    $auth->assign($auth->getRole($role), $model->id);
                }

                Yii::$app->session->setFlash('success', 'Вы успешно обновили администратора.');
                return $this->redirect(['index']);
            }
        }

//        $roles = ArrayHelper::toArray($auth->getRoles());

        return $this->render('update', [
            'model' => $model,
            'roles' => $roles,
        ]);
    }

    /**
     * Удаляет существующую модель Администратора.
     * Если удаление выполнено успешно, браузер будет перенаправлен на страницу списка администраторов.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException если модель не найдена.
     */
    public function actionDelete($id)
    {
        $auth = Yii::$app->authManager;
        $this->findUser($id)->delete();
        $auth->revokeAll($id);
        Yii::$app->session->setFlash('success', 'Вы успешно удалили администратора.');
        return $this->redirect(['index']);
    }

    /**
     * Находит администратора на основе его первичного ключа.
     * Если модель не найдена, будет выброшено исключение HTTP 404.
     * @param integer $id
     * @return User модель пользователя/администратора.
     * @throws NotFoundHttpException если модель не найдена.
     */
    protected function findUser($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Страница не найдена');
    }


    /**
     * Удаляет выбраные модели администраторов.
     * Запрос на удаление происходит по Ajax.
     * @return array
     * @throws NotFoundHttpException если модель не найдена.
     */
    public function actionAllDelete()
    {
        if (Yii::$app->request->isAjax) {

            $data = Yii::$app->request->post();
            $auth = Yii::$app->authManager;

            foreach ($data as $item){
                $this->findUser($item)->delete();
                $auth->revokeAll($item);
            }

            Yii::$app->session->setFlash('success', 'Вы успешно удалили администратора/ов.');
            Yii::$app->response->format = Response::FORMAT_JSON;

            return [
                'message' => 'success',
            ];
        }
    }


    /**
     * Снимает роль с выбраного администратора.
     * @param integer $id ID администратора
     * @return Response
     */
    public function actionRoleRevoke($id)
    {
        $auth = Yii::$app->authManager;
        $auth->revokeAll($id);
        Yii::$app->session->setFlash('success', 'Вы успешно сняли роль с администратора.');
        return $this->redirect(['index']);
    }
}
