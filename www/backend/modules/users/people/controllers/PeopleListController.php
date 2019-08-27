<?php

namespace backend\modules\users\people\controllers;

use backend\controllers\BaseController;
use backend\modules\order\helpers\SettingsHelper;
use backend\modules\order\models\Order;
use common\models\Favorites;
use common\models\LoginForm;
use common\models\Wishes;
use common\service\CacheProductService;
use Yii;
use common\models\User;
use backend\modules\users\people\models\PeopleSearch;
use yii\base\DynamicModel;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use common\controllers\AccessController;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\base\Module;
use backend\modules\users\roles\models\AuthItem;

/**
 * PeopleListController реализует CRUD-систему для модели Пользователь.
 */
class PeopleListController extends BaseController
{
    private $product_service;

    public function __construct($id, Module $module,CacheProductService $product_service, array $config = [])
    {
        $this->product_service = $product_service;
        parent::__construct($id, $module, $config);
    }

    /**
     * Метод устанавливает поведение классу
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-component#behaviors()-detail
     */
    public function behaviors()
    {
        $rules = [
            [
                'allow' => true,
                'actions' => ['edit-personal-data'],
                'roles'=> ['@', '?']
            ]
        ];

        $rules = array_merge($rules, AccessController::getAccessRules(Yii::$app->controller));

        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
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
        if ($action->id == 'edit-personal-data') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }


    /**
     * Список всех моделей Пользователей
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PeopleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'user_settings' => Yii::$app->user->identity->getSettings('user')
        ]);
    }

    /**
     * Отображает одну модель Пользователя.
     * @param integer $id идентификатор Пользователя
     * @return mixed
     * @throws NotFoundHttpException если модель не найдена.
     */
    public function actionView($id)
    {
        $settings = new SettingsHelper();
        $orders = $this->product_service->getDataForOrder($id);
       // var_dump($orders);die();
        $orderDataProvider = new ArrayDataProvider([
            'allModels' => $orders
        ]);
        $orderDataProvider->getModels();
        return $this->render('view', [
            'model' => $this->findUser($id),
            'wishes' => $this->product_service->getProductNameForWish(Wishes::find()->where(['user_id' => $id])->asArray()->all()),
            'favorites' => $this->product_service->getProductNameForFavorites(Favorites::find()->where(['user_id' => $id])->asArray()->one()),
            'orderDataProvider' => $orderDataProvider,
            'delivary_list' => $settings->getAllList('delivery',true),
            'payment_list' => $settings->getAllList('payment',true),
        ]);
    }

    /**
     * Создает новую модель Пользователя.
     * Если создание будет успешным, браузер будет перенаправлен на страницу списка пользователей.
     * @return string|Response
     * @throws \yii\base\Exception
     */
    public function actionCreate()
    {
        $user = new User();
        $user->scenario = User::REGISTRATION_USER;
        $auth = Yii::$app->authManager;

        $roles = ArrayHelper::map(AuthItem::find()->select('name')->where(['type' => 1])->andWhere(['!=', 'name', 'superAdmin'])->asArray()->all(),'name','name');

        if ($user->load($data = Yii::$app->request->post())) {
            $user->phone = (integer)preg_replace("/[^0-9]/", '', ArrayHelper::getValue($data, 'User.phone'));

            $isValid = $user->validate();

            if ($isValid) {

                $data = Yii::$app->request->post();
                $user->first_name = ArrayHelper::getValue($data, 'User.first_name');
                $user->last_name = ArrayHelper::getValue($data, 'User.last_name');
                $user->email = ArrayHelper::getValue($data, 'User.email');
                $user->setPassword(ArrayHelper::getValue($data, 'User.password_hash'));
                $user->generateAuthKey();
                $user->type = 1;

                if($user->save()){ 
                    Yii::$app->session->setFlash('success', 'Вы успешно создали пользователя.');
                    return $this->redirect($data['save']);
                }

                Yii::$app->session->setFlash('error', 'Не удалось создать пользователя.');
                return $this->redirect($data['save']);
            }
        }

        return $this->render('create', [
            'user' => $user,
        ]);

    }

    /*
     * метод обновляет данные пришедшии из виджета personal-data
     */

    /**
     * @param integer $id ID - пользователя
     * @return string Сообщение об окончании изменений ЛД
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\base\Exception
     * @throws \yii\db\StaleObjectException
     */
    public function actionEditPersonalData($id)
    {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            $user = $this->findUser($id);
            if (isset($post['User']['new_password']) || isset($post['User']['old_password'])) {

                $old_password = $post['User']['old_password'];
                $new_password = $post['User']['new_password'];
                $new_password_repeat = $post['User']['new_password_repeat'];

                $model = DynamicModel::validateData(compact('old_password', 'new_password', 'new_password_repeat'), [
                    ['old_password', 'required', 'message' => 'Поле "Старый пароль" не может быть пустым'],
                    ['new_password', 'required', 'message' => 'Поле "Новый пароль" не может быть пустым'],
                    ['new_password_repeat', 'required', 'message' => 'Поле "Подтвердите пароль" не может быть пустым'],
                    ['new_password_repeat','compare','compareAttribute' => 'new_password', 'message' => 'Пароли не совпадают'],
                    ['new_password', 'match', 'pattern' => '/^[a-zA-Z0-9_-]{4,18}$/', 'message' => 'Пароль далжен состоять из английских символов и цифр и быть длинее 4 знаков'],
                ]);

                if ($post['User']['old_password'] != '' || !empty($post['User']['old_password'])){
                    if (!$user->validatePassword($old_password)) $model->addError('old_password','Неверно введен старый пароль');
                }
                if ($model->hasErrors()) {
                    return JSON::encode(['password' => $model->errors]);
                }

                $user->setPassword(Html::encode($post['User']['new_password']));
                $user->update();
                $from = Yii::$app->params;
                Yii::$app->mailer->compose()
                    ->setFrom($from['adminEmail'])
                    ->setTo($user->email)
                    ->setSubject('Ваш новый пароль в PROSTO&VIGODNO')
                    ->setHtmlBody("<p>Смена пароля в PROSTO&VIGODNO</p><p>Ваш новый пароль: " . $post['User']['new_password'] . "</p>")
                    ->send();
                return JSON::encode([
                    'status' => 'success',
                    'message' => 'Новый пароль сохранен и был отправлен на вашу почту'
                ]);

            } else {

                $first_name = Html::encode($post['User']['first_name']);
                $last_name = Html::encode($post['User']['last_name']);
                $email = Html::encode($post['User']['email']);
                $phone = Html::encode($post['User']['phone']);

                $model = DynamicModel::validateData(compact('first_name', 'last_name', 'email', 'phone'), [
                    ['first_name', 'required', 'message' => 'Поле "Имя" не может быть пустым'],
                    ['last_name', 'required', 'message' => 'Поле "Фамилия" не может быть пустым'],
                    ['email', 'required', 'message' => 'Поле "E-mail" не может быть пустым'],
                    ['email', 'email', 'message' => 'Поле "E-mail" не корректно'],
                ]);

                if (strlen(preg_replace("/[^0-9]/", '', $phone)) != 12){
                    $model->addError('phone', 'Введите корректно номер телефона');
                }

                $id_validate = User::find()->select('id')->where(['email' => $email])->asArray()->one();

                if ($id_validate != null && $id_validate['id'] != $id){
                    $model->addError('email', 'Такой email занят');
                }

                if ($model->hasErrors()) {
                    return JSON::encode(['personal' => $model->errors]);
                }

                $user->first_name = $first_name;
                $user->last_name = $last_name;
                $user->email = $email;
                $user->phone = $phone;
            }
            if ($user->update()) {
                return JSON::encode([
                    'status' => 'success',
                    'message' => 'Данные были изменены'
                ]);
            }
        }
    }

    /**
     * Обновляет существующую модель Пользователя.
     * Если обновление выполнено успешно, браузер будет перенаправлен на страницу просмотра пользователя.
     * @param integer $id идентификатор Пользователя
     * @return string|Response
     * @throws NotFoundHttpException если модель не найдена.
     * @throws \yii\base\Exception
     */
    public function actionUpdate($id)
    {
        $auth = Yii::$app->authManager;
        $user = $this->findUser($id);
        $user->scenario = User::UPDATE_USER_ADMINISTRATOR;

        if ($user->load(Yii::$app->request->post())) {

            $data = Yii::$app->request->post();
            $isValid = $user->validate();

            if($isValid){

                $password = ArrayHelper::getValue($data, 'User.new_password');
                
                if (!(strlen($password) == 0)) {
                    $user->scenario = User::REGISTER_USER_ADMINISTRATOR;
                    $user->validate();
                }

                if (isset($password)) {
                    $user->setPassword($password);
                    $user->generateAuthKey();
                }

                $user->username = ArrayHelper::getValue($data, 'User.username');
                $user->first_name = ArrayHelper::getValue($data, 'User.first_name');
                $user->last_name = ArrayHelper::getValue($data, 'User.last_name');
                $user->email = ArrayHelper::getValue($data, 'User.email');
                $user->phone = preg_replace("/[^0-9]/", '', ArrayHelper::getValue($data, 'User.phone'));

                if($user->save(false)){
                    Yii::$app->session->setFlash('success', 'Вы успешно отредактировали пользователя');
                    return $this->redirect($data['save']);
                }
            }

            Yii::$app->session->setFlash('error', 'Не удалось отредактировать пользователя');
            return $this->redirect($data['save']);
        }

        return $this->render('update', [
            'user' => $user,
        ]);
    }

    /**
     * Удаляет существующую модель Пользователя.
     * Если удаление выполнено успешно, браузер будет перенаправлен на страницу списка пользователей.
     * @param integer $id идентификатор Пользователя
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $user = $this->findUser($id);
        if($user->delete()){
            Yii::$app->session->setFlash('success', 'Вы успешно удалили пользователя/ей');
        }else{
            Yii::$app->session->setFlash('error', 'Не удалось удалить пользователя');
        }
        return $this->redirect(['index']);
    }

    /**
     * Находит пользователя на основе его id.
     * Если модель не найдена, будет выброшено исключение HTTP 404.
     * @param integer $id идентификатор Пользователя
     * @return User модель роли.
     * @throws NotFoundHttpException если модель не найдена.
     */
    protected function findUser($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Пользователя с таким ID = ' . $id . ', не существует');
    }

    /**
     * Удаляет выбраные модели пользователей.
     * Запрос на удаление происходит по Ajax.
     * @return array
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionAllDelete()
    {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            foreach ($data as $item) {
                $user = $this->findUser($item);
                $transactions = Transaction::find()->where(['user_id' => $user->id])->all();
                foreach ($transactions as $transaction) {
                    $transaction->delete();
                }
                $user->delete();
            }
            Yii::$app->session->setFlash('success', 'Вы успешно удалили пользователя/ей');
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'message' => 'success',
            ];
        }
    }
    /*
     * Метод проверяет на уникальность email
     */
    public function actionConfirmEmail()
    {
        if (Yii::$app->request->isAjax){
            $email = Yii::$app->request->post('email');
            if(User::find()->where(['email' => Html::encode($email)])->exists()){

                return JSON::encode([
                    'status' => 'error',
                    'message' => 'Такая почта уже зарегестрирована в системе',
                ]);
            }

            return JSON::encode([
                'status' => 'success',
                'message' => 'Почта уникальная',
            ]);
        }
    }
}
