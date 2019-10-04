<?php

namespace backend\modules\settings\controllers;

use common\models\Lang;
use Yii;
use backend\controllers\BaseController;
use backend\modules\settings\models\Settings;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use common\controllers\AccessController;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use function GuzzleHttp\Promise\all;

class DefaultController extends BaseController {

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

    private function getLangsSettings() {
        return Lang::find()->all();
    }

    private function getSetting($alias) {
        $settings = Settings::find()->where(['name' => $alias])->asArray()->one();
        return (!empty($settings['body'])) ? unserialize($settings['body']) : null;
    }

    private function getContacts() {

        $model = Settings::find()
                ->select(['body', 'name', 'status', 'id'])
                ->where(['in', 'name', ['position', 'mail', 'phone']])
                ->asArray()
                ->all();
        return $model;
    }

    private function getCoordinateData() {
        $coordinate = Settings::find()->where(['in', 'name', ['lat', 'lng']])->asArray()->all();
        $coordinate = \yii\helpers\ArrayHelper::map($coordinate, 'name', 'body');
        return $coordinate;
    }

    public function actionIndex() {
        $set_lang = $this->getLangsSettings();
        $contact = $this->getContacts();

        $payment = $this->getSetting('payment');
        $delivery = $this->getSetting('delivery');

        $group = $this->getSetting('social-group');

        $model = new Settings();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $set_lang,
            'sort' => false,
            'pagination' => false
        ]);
        $ContactDataProvider = new ArrayDataProvider([
            'allModels' => $contact,
            'sort' => false,
            'pagination' => false
        ]);

        $paymentDataProvider = new ArrayDataProvider([
            'allModels' => $payment,
            'sort' => false,
            'pagination' => false
        ]);
        $deliveryDataProvider = new ArrayDataProvider([
            'allModels' => $delivery,
        ]);

        $GroupDataProvider = new ArrayDataProvider([
            'allModels' => $group,
            'sort' => false,
            'pagination' => false
        ]);

        $coordinate = $this->getCoordinateData();

        return $this->render('index', [
                    'defaultLanguage' => Yii::$app->params['settings']['defaultLanguage'],
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'contact' => $ContactDataProvider,
                    'payment' => $paymentDataProvider,
                    'delivery' => $deliveryDataProvider,
                    'group' => $GroupDataProvider,
                    'coordinate' => $coordinate
        ]);
    }

    public function actionUpdateRowLang() {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->post()) {
                $data = Yii::$app->request->post();
                $row_id = $data['row_id'];
                $index = $data['index'];
                $action = 'update';
                $langs = $this->getLangsSettings();
                $lang = $langs[$row_id];
                $model = new Lang();
                $model->name = $lang['name'];
                $model->alias = $lang['alias'];
                $model->status = $lang['status'];
                $model->currency = $lang['currency'];
                return $this->renderAjax('edit', ['model' => $model, 'index' => $index, 'key' => $row_id, 'action' => $action]);
            }
        }
    }

    public function actionUpdateStatus() {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->post()) {
                $data = Yii::$app->request->post();
                $record_id = $data['id'];
                $status = $data['checked'];
                $languages = $this->getLangsSettings();
                $language = $languages[$record_id];
                $language['status'] = $status;
                $language->save();
            }
        }
    }

    public function actionUpdateStatusDelivery() {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->post()) {
                $this->changeStatus(Yii::$app->request->post(), 'delivery');
            }
        }
    }

    public function actionUpdateStatusPayment() {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->post()) {
                $this->changeStatus(Yii::$app->request->post(), 'payment');
            }
        }
    }

    public function actionUpdateStatusSocial(){
        if(Yii::$app->request->isAjax){
            if(Yii::$app->request->post()){
                $this->changeStatus(Yii::$app->request->post(), 'social-group');
            }
        }
    }

    public function actionUpdateStatusContact(){
        if(Yii::$app->request->isAjax){
            $post = Yii::$app->request->post();
            Settings::updateAll(['status' => $post['checked']],['id' => $post['id']]);
        }
    }

    private function changeStatus($post, $alias) {
        $settings = Settings::find()->where(['name' => $alias])->one();
        $set = unserialize($settings->body);
        if (array_key_exists($post['id'], $set)) {
            $set[$post['id']]['status'] = (int) $post['checked'];
            $settings->body = serialize($set);
            $settings->update();
        }
    }

    public function actionSaveRowLang() {
        $model = new Settings();
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->post()) {
                $data = Yii::$app->request->post();
                $edit_data = $data['edit'];
                $record_id = $data['lang_id'];
                $status = $edit_data['status'];
                $action = $data['action'];
                foreach ($edit_data as $value) {
                    $value = str_replace(" ", "", $value);
                    if ($value == '') {
                        echo 'fail';
                        return;
                    }
                }
                $languages = $this->getLangsSettings();
                if ($action == 'update') {
                    $language = $languages[$record_id];
                    $language['status'] = $status;
                    $language['name'] = $edit_data['name'];
                    $language['alias'] = $edit_data['alias'];
                    $language['currency'] = $edit_data['currency'];
                    $languages[$record_id] = $language;
                } else if ($action == 'add') {
                    $language = new Lang();
                    $language['status'] = $status;
                    $language['name'] = $edit_data['name'];
                    $language['alias'] = $edit_data['alias'];
                    $language['currency'] = $edit_data['currency'];
                    array_push($languages, $language);
                }
                $language->save();
                $language->getErrors();
                echo 'ok';
            }
        }
    }

    public function actionDeleteRowLang() {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->post()) {
                $data = Yii::$app->request->post();
                $record_id = $data['row_id'];
                $languages = $this->getLangsSettings();
                $languages[$record_id]->delete();
                unset($languages[$record_id]);
                $count_langs = count($languages);
                echo $count_langs;
            }
        }
    }

    public function actionAddRowLang() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $languages = $this->getLangsSettings();
            $index = $data['index'];
            if (count($languages) == 0) {
                $key = 0;
            } else {
                end($languages);
                $key = key($languages) + 1;
            }
            $model = new Lang();
            $action = 'add';
            return $this->renderAjax('edit', ['model' => $model, 'key' => $key, 'index' => $index, 'action' => $action]);
        }
    }

    public function actionAddRow() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $languages = $this->getSetting($data['setting']);
            $index = $data['index'];
            if (count($languages) == 0) {
                $key = 0;
            } else {
                end($languages);
                $key = key($languages) + 1;
            }
            $model = new Settings();
            $action = 'add';
            return $this->renderAjax('edit-setting', [
                        'model' => $model,
                        'key' => $key,
                        'index' => $index,
                        'action' => $action,
                        'setting' => $data['setting']
            ]);
        }
    }

    public function actionSaveRow() {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->post()) {
                $data = Yii::$app->request->post();
                if (array_key_exists('_csrf-backend', $data['edit'])) {
                    unset($data['edit']['_csrf-backend']);
                }
                $settings = Settings::find()->where(['name' => $data['type']])->one();

                $arr = unserialize($settings->body);
                $last_id = max(array_column($arr, 'id'));
                $data['edit']['id'] = $last_id + 1;
                $data['edit']['status'] = 1;
                $data['edit']['position'] = (int) $data['edit']['position'];

                $max_position = max(array_column($arr, 'position'));
                $array = array_map(function($e) use ($data, $max_position) {
                    if ($e['position'] == (int) $data['edit']['position']) {
                        $e['position'] = (int) $max_position + 1;
                    }
                    return $e;
                }, $arr);

                array_push($array, $data['edit']);
                $settings->body = serialize($array);
                $settings->update();

                $data['position_max'] = $max_position + 1;
                return JSON::encode($data);
            }
        }
    }

    public function actionUpdate() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            if (!empty($data['type']) && !empty($data['id'])) {

                $setting = Settings::find()->where(['name' => $data['type']])->one();
                $setting->body = serialize(array_map(function($e) use ($data) {
                            if ($data['field'] == 'number') {
                                if ($e['id'] == $data['id']) {
                                    $e['position'] = (int) $data['body'];
                                }
                                if ($e['position'] == $data['body'] && !($e['id'] == $data['id'])) {
                                    $e['position'] = (int) $data['old_value'];
                                }
                            } else {
                                if ($e['id'] == $data['id']) {
                                    if($data['type'] == 'social-group'){
                                        $e['link'] = $data['body'];
                                    }else if($data['type'] == 'payment' || $data['type'] == 'delivery'){
                                        $e['name'] = $data['body'];
                                    }

                                }
                            }
                            return $e;
                        }, unserialize($setting->body)));

                $setting->update();

                if ($data['field'] == 'number') {
                    return JSON::encode($data);
                }
                return;
            }
            if ($data['body'] == "" || $data['body'] == '(не задано)')
                $data['body'] = NULL;
            $model = Settings::find()->where(['name' => $data['alias']])->one();

            $model->body = $data['body'];
            $model->update();
        }
    }

    public function actionSetCoordinate() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $settings = Settings::find()->where(['name' => $data['name']])->one();
            $settings->body = $data['body'];
            $settings->update();
        }
    }

}
