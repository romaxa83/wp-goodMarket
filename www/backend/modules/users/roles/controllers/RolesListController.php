<?php

namespace backend\modules\users\roles\controllers;

use backend\controllers\BaseController;
use backend\modules\users\roles\models\RolesListSearch;
use Yii;
use backend\modules\users\roles\models\AuthItem;
use common\controllers\AccessController;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * RolesListController реализует CRUD-систему для модели Роли.
 */
class RolesListController extends BaseController
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
     * Список всех моделей Ролей.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RolesListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Отображает одну модель Роли
     * @param string $name
     * @return mixed
     * @throws NotFoundHttpException если модель не найдена.
     */
    public function actionView($name)
    {
        $roles = AuthItem::find()->where(['name' => $name])->joinWith('authItemChildren')->one();
        return $this->render('view', [
            'roles' => $roles,
        ]);
    }

    /**
     * Создает новую модель Роли.
     * Если создание будет успешным, браузер будет перенаправлен на страницу списка ролей.
     * @return mixed
     */
    public function actionCreate()
    {
        $auth = Yii::$app->authManager;
        $permissions = ArrayHelper::toArray($auth->getPermissions());

        $permissions_name = array_map(
            function($item){
                return $item['name'];
            }, $permissions);

        $model = new AuthItem();
        $model->scenario = AuthItem::SCENARIO_ROLE;
        $post = Yii::$app->request->post();

        if ($model->load($post) && $model->validate()) {

            $name = ArrayHelper::getValue(Yii::$app->request->post(),'AuthItem.name');
            $desc = ArrayHelper::getValue(Yii::$app->request->post(),'AuthItem.description');

            $auth = Yii::$app->authManager;
            $createRole = $auth->createRole($name);
            $createRole->description = $desc;
            $auth->add($createRole);

            if (isset($post['role_permissions'])){
                foreach ($post['role_permissions'] as $item){
                    if (!empty($item)){
                        $auth->addChild($createRole, $auth->getPermission($item));
                    }
                }
            }

            Yii::$app->session->setFlash('success', 'Вы успешно создали роль.');
            return $this->redirect([$post['save']]);
        }

        return $this->render('create', [
            'model' => $model,
            'permissions' => $permissions_name,
        ]);
    }

    /**
     * Обновляет существующую модель Роли.
     * Если обновление выполнено успешно, браузер будет перенаправлен на страницу списка ролей.
     * @param string $name
     * @return mixed
     * @throws NotFoundHttpException если модель не найдена.
     */
    public function actionUpdate($name)
    {
        $model = $this->findModel($name);
        $model->scenario = AuthItem::SCENARIO_ROLE;
        $auth = Yii::$app->authManager;

        $permissions = ArrayHelper::toArray($auth->getPermissions());
        $permissions = $this->getPermissionName($permissions);
        $role_permissions = ArrayHelper::toArray($auth->getPermissionsByRole($name));
        $role_permissions = $this->getPermissionName($role_permissions);
        //dd($permissions);
        if ($model->load($post = Yii::$app->request->post()) && $model->validate()) {

            $name_new = ArrayHelper::getValue(Yii::$app->request->post(),'AuthItem.name');
            $desc = ArrayHelper::getValue(Yii::$app->request->post(),'AuthItem.description');

            $updateRole = $auth->getRole($name);
            $updateRole->name = $name_new;
            $updateRole->description = $desc;
            $auth->update($name, $updateRole);
            $auth->removeChildren($updateRole);

            if (isset($post['role_permissions'])) {
                foreach ($post['role_permissions'] as $item) {
                    if ($item != '') {
                        $auth->addChild($updateRole, $auth->getPermission($item));
                    }
                }
            }

            Yii::$app->session->setFlash('success', 'Вы успешно отредактировали роль.');
            return $this->redirect(['index']);
        }
        
        return $this->render('update', [
            'model' => $model,
            'permissions' => $permissions,
            'role_permissions' => $role_permissions
        ]);
    }

    /**
     * Удаляет существующую модель Роли.
     * Если удаление выполнено успешно, браузер будет перенаправлен на страницу списка ролей.
     * @return mixed
     * @throws NotFoundHttpException если модель не найдена.
     */
    public function actionDelete()
    {
        $auth = Yii::$app->authManager;
        $role_name = Yii::$app->request->get('name');
        $this->revokeRoleFromUsers($role_name);
        $auth->removeChildren($auth->getRole($role_name));
        $auth->remove($auth->getRole($role_name));
        Yii::$app->session->setFlash('success', 'Вы успешно удалили роль.');
        return $this->redirect(['index']);
    }

    /**
     * Находит роль на основе ее названия.
     * Если модель не найдена, будет выброшено исключение HTTP 404.
     * @param string $name
     * @return AuthItem модель роли.
     * @throws NotFoundHttpException если модель не найдена.
     */
    protected function findModel($name)
    {
        if (($model = AuthItem::findOne($name)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * По названию роли получаем ID администраторов и снимаем с них эту роль.
     * @param $name
     * @return Response
     */
    public function actionUserRevoke($name)
    {
        $this->revokeRoleFromUsers($name);
        Yii::$app->session->setFlash('success', 'Вы успешно сняли роль с администраторов.');
        return $this->redirect(['index']);
    }

    private function revokeRoleFromUsers($name){
        $auth = Yii::$app->authManager;
        $assignments = $auth->getUserIdsByRole($name);
        foreach ($assignments as $assignment){
            $auth->revokeAll($assignment);
        }
    }

    /**
     * Удаляем выбраные модели ролей.
     * Запрос на удаление происходит по Ajax.
     * @return array
     */
    public function actionAllDelete()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $auth = Yii::$app->authManager;
            foreach ($data as $item){
                $this->revokeRoleFromUsers($item);
                $auth->remove($auth->getRole($item));
            }
            Yii::$app->session->setFlash('success', 'Вы успешно удалили роль/и.');
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'message' => 'success',
            ];
        }
    }

    private function getPermissionName($permissions){
        return  array_map(
                    function($item){
                        return $item['name'];
                    }, $permissions);
    }
}
