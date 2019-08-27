<?php

namespace backend\modules\users\controllers;

use backend\controllers\BaseController;
use common\models\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Response;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use function GuzzleHttp\Psr7\str;
use yii\httpclient\Client;
use common\controllers\AccessController;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

/**
 * Контроллер по умолчанию для модуля `users`
 */
class MainController extends BaseController
{
    public function behaviors()
    {
        return [
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
     * Поиск пользователя по его номеру телефона.
     * @param null $q номер телефона
     * @param null $id по умолчанию
     * @return array
     */
    public function actionPhone($q = null, $id = null) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query();
            $query->select('id, phone AS text')
                ->from('user')
                ->where(['like', 'phone', $q])
                ->andWhere(['type' => 1])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();

            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => User::find($id)->phone];
        }
        return $out;
    }

    /**
     * Позволяет администратору авторизоваться под выбраным пользователем.
     * @param integer $id Выбраный пользователь
     * @return mixed
     * @throws NotFoundHttpException если модель не найдена.
     */
    public function actionLogin($id)
    {
        $admin_id = Yii::$app->user->id;
        Yii::$app->user->logout();
        Yii::$app->session->set('admin_id', $admin_id);
        $user = $this->findUser($id);
        if ($user->type == 0) {
            Yii::$app->user->login($user);
            if ($id == 1) {
                Yii::$app->session->remove('admin_id');
            }
            return $this->goBack();
        } elseif ($user->type == 1) {

            Yii::$app->user->login($user);
            if ($id == 1) {
                Yii::$app->session->remove('admin_id');
            }
            return $this->redirect(Yii::$app->urlManager->createUrl('./../../frontend/web/'));
        }
    }

    /**
     * Находит пользователя на основе его id.
     * Если модель не найдена, будет выброшено исключение HTTP 404.
     * @param integer $id
     * @return User модель роли.
     * @throws NotFoundHttpException если модель не найдена.
     */
    protected function findUser($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Поиск города в API Новая Почта.
     * @param null $q название города
     * @param null $id по умолчанию
     * @return array
     */
    public function actionCityNP($q = null, $id = null) {
        Yii::$app->response->format = Response::FORMAT_JSON;
//        $out = ['results' => ['id' => '', 'text' => '']];
        $out['results'] = [];
        if (!is_null($q)) {
            $client = new Client(['baseUrl' => 'https://api.novaposhta.ua/v2.0/json/']);
            $response = $client->createRequest()
                ->setFormat(Client::FORMAT_JSON)
                ->setData([
                    "apiKey" => "e160e9371d3053141f499931c6af0f23",
                    "modelName" => "Address",
                    "calledMethod" => "searchSettlements",
                    "methodProperties"=> [
                        "CityName" => $q,
                        "Limit" => 5
                    ]
                ])
                ->send();

            $responseData = $response->getData();
            $data = $responseData['data'][0]['Addresses'];
            foreach ($data as $item){

                array_push($out['results'], ['id' => $item['MainDescription'].','.$item['Region'].','.$item['Area'], 'text' => $item['Present']]);
            }
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => User::find($id)->phone];
        }
        return $out;
    }

    /**
     * Поиск города
     * @param null $q
     * @param null $id
     * @return array
     * @throws \yii\db\Exception
     */
    public function actionCity($q = null, $id = null) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $out['results'] = [];
        if (!is_null($q)) {
            $query = new Query();
            $query->select('city_name_ru, city_name_en, city_name_ua, country_name_ru, country_name_en, country_name_ua, region_name_ru, region_name_en, region_name_ua')
                ->from('city')
                ->leftJoin('country','country.id = city.id_country')
                ->leftJoin('regions','regions.id = city.id_region')
                ->where(['or', ['like', 'city.city_name_ru', $q], ['like', 'city.city_name_ua', $q]])
                ->limit(10);
            $command = $query->createCommand();
            $data = $command->queryAll();

            foreach ($data as $item){

                array_push($out['results'], ['id' => $item['city_name_ru'].','.$item['region_name_ru'].','.$item['country_name_ru'], 'text' => $item['city_name_ru'].', '.$item['region_name_ru'].', '.$item['country_name_ru']]);
            }
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => User::find($id)->phone];
        }
        return $out;
    }

    /**
     * Обновление названий городов с выбраным языком
     * @throws \yii\db\Exception
     */
    public function actionUpdateGeo(){
        ini_set('max_execution_time', 0);

        $language = 'ru';
        $key = '';
        $country = 'Укриана';

        $cities = (new \yii\db\Query())
            ->select('id, city_name_ru')
            ->from('city')
            ->where(['id_country' => 220])
            ->all();

        foreach ($cities as $city ){
            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl('https://maps.googleapis.com/maps/api/geocode/json')
                ->setData(['address' => ''.$city['city_name_ru'].', '.$country, 'language' => $language, 'key' => $key])
                ->send();
            if ($response->isOk) {
                $city_names[$city['id']] = $response->getData()['results'][0]['address_components'][0]['long_name'];
            }
            sleep(1);
        }

        foreach ($city_names as $k => $v){

            Yii::$app->db->createCommand()->update('city', ['city_name_'.$language => $v], 'id = '.$k)->execute();
            sleep(1);
        }

        $this->d('Поздравляю у вас актуальная база город с '.$language.' названиями');
    }

    public function actionAddSettings()
    {
        if(Yii::$app->request->post()){
            $post = Yii::$app->request->post();
            $user = User::findOne($post['user_id']);
            $user->addSetting($post['model'],$post['type'],$post['attr']);
        }
    }

    public function actionRemoveSettings()
    {
        if(Yii::$app->request->post()){
            $post = Yii::$app->request->post();
            $user = User::findOne($post['user_id']);
            $user->removeSetting($post['model'],$post['type'],$post['attr']);
        }
    }

}
