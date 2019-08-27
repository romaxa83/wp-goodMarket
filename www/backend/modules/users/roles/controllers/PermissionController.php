<?php

namespace backend\modules\users\roles\controllers;

use backend\controllers\BaseController;
use Yii;
use yii\helpers\Json;
use backend\modules\users\roles\models\AuthItem;
use backend\modules\users\roles\models\PermissionSearch;
use common\controllers\AccessController;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use common\helpers\RouteHelper;
use yii\data\ArrayDataProvider;
use backend\modules\users\roles\models\PermissionActions;

/**
 * PermissionController реализует CRUD-систему для модели Разрешения.
 * BaseController::access() Метод для доступов
 */
class PermissionController extends BaseController
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
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => AccessController::getAccessRules(Yii::$app->controller),
                'denyCallback' => function($rule, $action) {
                   throw new ForbiddenHttpException('Вам не разрешено производить данное действие.');
                }
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
     * Список всех моделей Разрешений.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PermissionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Отображает одну модель Разрешений
     * @param string $name
     * @return mixed
     * @throws NotFoundHttpException если модель не найдена.
     */
    public function actionView($name)
    {

        return $this->render('view', [
            'model' => $this->findModel($name),
        ]);
    }

    /**
     * Создает новую модель Разрешения.
     * Если создание будет успешным, браузер будет перенаправлен на страницу списка разрешений.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuthItem();
        $model->scenario = AuthItem::SCENARIO_PERMISSION;
        $route_helper = new RouteHelper();
        $route_list = $route_helper->getRouteList();
        $route_table = $route_helper->getRoutesTable($route_list);

        $lists['module'] = array_unique(array_column($route_table, 'module'));
        $lists['submodule'] = [];
        $lists['controller'] = [];
        $lists['action'] = [];
        
        $permission_routes = [];
        $data_provider = new ArrayDataProvider([
            'allModels' => $route_helper->getRoutesTable($permission_routes)
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $name = ArrayHelper::getValue(Yii::$app->request->post(),'AuthItem.name');
            $desc = ArrayHelper::getValue(Yii::$app->request->post(),'AuthItem.description');
            $data = ArrayHelper::getValue(Yii::$app->request->post(),'AuthItem.data');
            $auth = Yii::$app->authManager;

            $createPermission = $auth->createPermission($name);
            $createPermission->description = $desc;
    
            if($auth->add($createPermission)){
                $routes = Yii::$app->request->post('routes');
                $routes = json_decode($routes);
                if(!empty($routes)){
                    PermissionActions::insertRoutes($routes, $name);
                }
                Yii::$app->session->setFlash('success', 'Вы успешно создали разрешение.');
            }else{
                Yii::$app->session->setFlash('error', 'Не удалось создать разрешение.');
            }
            return $this->redirect(['index']);
        }
        
        return $this->render('create', [
            'model' => $model,
            'structure_elements' => $lists,
            'dataProvider' => $data_provider,
            'permission_routes' => $permission_routes,
        ]);
    }

    /**
     * Обновляет существующую модель Разрешения.
     * Если обновление выполнено успешно, браузер будет перенаправлен на страницу просмотра разрешения.
     * @param string $name
     * @return mixed
     * @throws NotFoundHttpException если модель не найдена.
     */
    public function actionUpdate($name)
    {
        $model = $this->findModel($name);
        $model->scenario = AuthItem::SCENARIO_PERMISSION;

        $route_helper = new RouteHelper();
        $route_list = $route_helper->getRouteList();
        $route_table = $route_helper->getRoutesTable($route_list);

        $permission_routes = PermissionActions::find()->where(['perm_name' => $name])->asArray()->all();
        $permission_routes = ArrayHelper::getColumn($permission_routes, 'action');

        $lists['module'] = array_unique(array_column($route_table, 'module'));
        $lists['submodule'] = [];
        $lists['controller'] = [];
        $lists['action'] = [];

        $data_provider = new ArrayDataProvider([
            'allModels' => $route_helper->getRoutesTable($permission_routes)
        ]);

        if ($model->load($permission = Yii::$app->request->post()) && $model->validate()) {
            $name_new = ArrayHelper::getValue(Yii::$app->request->post(),'AuthItem.name');
            $desc = ArrayHelper::getValue(Yii::$app->request->post(),'AuthItem.description');
            $data = ArrayHelper::getValue(Yii::$app->request->post(),'AuthItem.data');
            $auth = Yii::$app->authManager;

            $updatePermission = $auth->getPermission($name);
            $updatePermission->name = $name_new;
            $updatePermission->description = $desc;

            if($auth->update($name, $updatePermission)){
                PermissionActions::deleteAll(['perm_name' => $name]);
                $routes = Yii::$app->request->post('routes');
                $routes = Json::decode($routes);
                if(!empty($routes)){
                    PermissionActions::insertRoutes($routes, $name);
                }
            }
            Yii::$app->session->setFlash('success', 'Вы успешно отредактировали разрешение.');
            return $this->redirect(['view', 'name' => $model->name]);
        }

        return $this->render('update', [
            'dataProvider' => $data_provider,
            'model' => $model,
            'permission_routes' => $permission_routes,
            'structure_elements' => $lists,
        ]);
    }

    /**
     * Удаляет существующую модель Разрешения.
     * Если удаление выполнено успешно, браузер будет перенаправлен на страницу списка разрешений.
     * @return mixed
     */
    public function actionDelete()
    {
        $auth = Yii::$app->authManager;
        $permission_name = Yii::$app->request->get('name');

        PermissionActions::deleteAll(['perm_name' => $permission_name]);
        $auth->remove($auth->getPermission($permission_name));

        Yii::$app->session->setFlash('success', 'Вы успешно удалили разрешение.');
        return $this->redirect(['index']);
    }

    /**
     * Находит разрешение на основе его названия.
     * Если модель не найдена, будет выброшено исключение HTTP 404.
     * @param string $name
     * @return AuthItem модель разрешения.
     * @throws NotFoundHttpException если модель не найдена.
     */
    protected function findModel($name)
    {
        if (($model = AuthItem::findOne($name)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Страница не найдена');
    }

    public function actionAjaxGetRoute(){
        if(Yii::$app->request->isAjax){

            $route = Yii::$app->request->post('route');
            $route = trim($route, '/');
            $route_helper = new RouteHelper();
            $route_list = $route_helper->getRouteList();
            $route_table = $route_helper->getRoutesTable($route_list);
            $lists = $route_helper->viewHierarchyForRoute($route_table, $route, 0, []);

            return Json::encode($lists);
        }
    }

    public function actionAjaxLoadRoutesTable(){
        if(Yii::$app->request->isAjax){

            $routes = Yii::$app->request->post('routes');
            $route_helper = new RouteHelper();
            
            $dataProvider = new ArrayDataProvider([
                'allModels' => $route_helper->getRoutesTable($routes)
            ]);

            return $this->renderPartial('actions_table', ['dataProvider' => $dataProvider]);
        }
    }
}
