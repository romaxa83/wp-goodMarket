<?php

namespace backend\modules\import\controllers;

use Yii;
use backend\modules\import\models\ParseShop;
use backend\modules\import\models\ParseShopSearch;
use backend\controllers\BaseController;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;
use backend\modules\filemanager\models\Mediafile;
use common\controllers\AccessController;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

class ImportController extends BaseController {

    public $keyParentCategory;

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => AccessController::getAccessRules(Yii::$app->controller),
                'denyCallback' => function($rule, $action) {
                    throw new ForbiddenHttpException('Вам не разрешено производить данное действие.');
                }
            ],
        ];
    }

    public function beforeAction($action) {
        if (parent::beforeAction($action)) {
            if (!AccessController::checkPermission($action->controller->route)) {
                throw new ForbiddenHttpException('Вам не разрешено производить данное действие.');
            }
            if ($action->id == 'load-edit-settings') {
                $this->enableCsrfValidation = false;
            }
            return parent::beforeAction($action);
        } else {
            return false;
        }
    }

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        $this->view->title = 'Импорт данных';
        $this->view->params['breadcrumbs'][] = $this->view->title;
        $searchModel = new ParseShopSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'user_settings' => Yii::$app->user->identity->getSettings('import')
        ]);
    }

    public function actionLog() {
        $this->view->title = 'Логи импорта';
        $this->view->params['breadcrumbs'][] = $this->view->title;
        $logPath = Yii::getAlias('@backend') . '/modules/import/assets/logs/';
        $logFile = array_diff(scandir($logPath), ['.', '..', '.gitkeep']);
        $log = [];
        foreach ($logFile as $key => $value) {
            $arr = [];
            $fileValue = file($logPath . $value);
            foreach ($fileValue as $keyValueLog => $valueLogFile) {
                $arr[$keyValueLog] = $valueLogFile;
            }
            $log[$key]['name'] = substr($value, 0, -4);
            $log[$key]['value'] = $arr;
        }
        return $this->render('log-list', compact('log'));
    }

    public function actionClearLog() {
        $logPath = Yii::getAlias('@backend') . '/modules/import/assets/logs/';
        $logFile = array_diff(scandir($logPath), ['.', '..', '.gitkeep']);
        foreach ($logFile as $value) {
            unlink($logPath . $value);
        }
        return $this->redirect('log');
    }

    public function actionAddShop() {
        $shop = new ParseShop();
        return $this->render('_form', compact('shop'));
    }

    public function actionEdit($id) {
        $model = $this->findModel($id);
        if (($model->prod_process == ParseShop::IN_PROCESS) || ($model->update_process == ParseShop::IN_PROCESS)) {
            Yii::$app->session->setFlash('error', 'Магазин обрабатывается другим процессом');
            return $this->redirect('index');
        } else if ($model->prod_process != ParseShop::LOADED) {
            Yii::$app->session->setFlash('error', 'Продукты магазина еще не добавлены');
            return $this->redirect('index');
        }
        return $this->render('_form', [
                    'shop' => $model
        ]);
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);
        if (($model->prod_process != ParseShop::LOADED) || ($model->edit_process == ParseShop::IN_PROCESS)) {
            Yii::$app->session->setFlash('error', 'Магазин обрабатывается другим процессом');
            return $this->redirect('index');
        } else if ($model->prod_process != ParseShop::LOADED) {
            Yii::$app->session->setFlash('error', 'Продукты магазина еще не добавлены');
            return $this->redirect('index');
        }
        $model->update_process = ParseShop::HIGH_PRIORITY;
        $model->save();
        Yii::$app->session->setFlash('success', 'Магазин поставлен в приоритет выше для обновления');
        return $this->redirect('index');
    }

    /* возвращает данные из файла (принимает номер магазина и название файла) */

    private function getData($shop_number, $file) {
        if (file_exists($path = Yii::$app->basePath . '/modules/import/assets/shops/shop_' . $shop_number . '/' . $file)) {
            return JSON::decode(file($path)[0]);
        }
        throw new \DomainException('Файл по пути ( ' . $path . ' ) отсутствует.');
    }

    public function actionDelete($id) {
        $model = $this->findModel($id);
        if (($model->prod_process != ParseShop::LOADED) || ($model->edit_process == ParseShop::IN_PROCESS)) {
            Yii::$app->session->setFlash('error', 'Магазин обрабатывается другим процессом');
            return $this->redirect('index');
        }
        $model->prod_process = 2;
        $model->update();
        $shopFolder = Yii::getAlias('@backend') . '/modules/exportxml/assets/shops/shop_' . $model->id;
        if (file_exists($shopFolder)) {
            $this->removeDirectory($shopFolder);
        }
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Магазин успешно удален');
            return $this->redirect('index');
        }
    }

    protected function removeDirectory($path) {
        $files = glob($path . '/*');
        foreach ($files as $file) {
            is_dir($file) ? removeDirectory($file) : unlink($file);
        }
        rmdir($path);
    }

    public function getViewConfig($data_category, $field, $currency) {
        $response['configCategory'] = $this->renderAjax('config-category', $data_category);
        $response['configField'] = $this->renderAjax('config-field', ['field' => $field]);
        $response['currency'] = $currency;
        return $response;
    }

    private function writeImportData(array $value, array $tags) {
        $value_json = Json::encode($value);
        $tags_json = Json::encode($tags);
        $absolutePath = Yii::$app->basePath . '/modules/import/assets/xml';
        if (!file_exists($absolutePath)) {
            mkdir($absolutePath, 0777, true);
        }
        /* записываем список значений в файл */
        $filename = $absolutePath . '/import_attr.json';
        $fp = fopen($filename, "w+");
        file_put_contents($filename, $value_json);
        fclose($fp);
        /* записываем список идентификаторов в файл */
        $filename = $absolutePath . '/import_tags.json';
        $fp = fopen($filename, "w+");
        file_put_contents($filename, $tags_json);
        fclose($fp);
    }

    private function getXmlArray($url) {
        $curl = curl_init($url);
        $param = Yii::$app->getModule('import')->params;
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $param['import_settings']['ssl']);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $param['import_settings']['ssl']);
        $data = curl_exec($curl);
        curl_close($curl);
        if (empty($data)) {
            return ['status' => 'error', 'text' => 'Файл не был получен, проверте ссылку'];
        }
        $extension = Mediafile::getCurlType($url);
        if ($extension != 'text/xml' && $extension != 'application/xml' && $extension != 'xml' && $extension != 'yml') {
            return ['status' => 'error', 'text' => 'Файл не имеет формат xml'];
        }
        $parser = xml_parser_create();
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, $data, $value, $tags);
        xml_parser_free($parser);
        if (empty($tags) || empty($value)) {
            return ['status' => 'error', 'text' => 'Файл пустой , проверте ссылку'];
        }
        /* Отбираем теги, у которых есть хотя бы одно значение */
        foreach (array_keys($tags) as $tag) {
            $tags[$tag] = array_intersect($tags[$tag], array_keys($value));
        }
        foreach ($tags['category'] as $v) {
            $category[] = $value[$v];
        }
        $field = [];
        foreach ($tags['param'] as $v) {
            $field['param'][trim($value[$v]['attributes']['name'])] = $value[$v]['attributes']['name'];
        }
        foreach (array_keys($tags) as $v) {
            if ($v == 'offer') {
                foreach (array_keys($value[reset($tags[$v])]['attributes']) as $offer_key) {
                    $other_fields[$offer_key] = $offer_key;
                }
            }
            $other_fields[$v] = $v;
        }
        $currency = [];
        foreach ($tags['currency'] as $v) {
            $currency['id'] = $value[$v]['attributes']['id'];
            $currency['value'] = $value[$v]['attributes']['rate'];
        }
        $all_fields = array_merge($other_fields, $field);
        $category = $this->formattedCategoryArray($category ?? []);
        return ['status' => 'success', 'value' => $value, 'tags' => $tags, 'categories' => $category, 'fields' => $all_fields, 'currency' => $currency];
    }

    public function actionLoadSettings() {
        if (Yii::$app->request->isAjax) {
            $url = Yii::$app->request->post('url');
            $data_xml = $this->getXmlArray($url);
            if ($data_xml['status'] == 'success') {
                $this->writeImportData($data_xml['value'], $data_xml['tags']);
                Yii::$app->cache->set('categoryXml', $data_xml['categories']);
                $data_category = ['category' => $data_xml['categories']];
                $render['configCategory'] = $this->renderPartial('config-category', ['data_category' => $data_category]);
                $render['configField'] = $this->renderPartial('config-field', ['field' => $data_xml['fields'], 'chooseFields' => null]);
                $render['currency'] = $data_xml['currency'];
                $render['characterVal'] = [];
                $answer = ['status' => 'success', 'data' => $render];
            } else {
                $answer = ['status' => 'error', 'text' => $data_xml['text']];
            }
            return Json::encode($answer);
        }
    }

    public function actionLoadEditSettings() {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            $shop_id = $post['ParseShop']['id'];
            $model = $this->findModel($shop_id);
            $url = $model->link;
            $data_xml = $this->getXmlArray($url); //получаем спарсенные поля с магазина
            if ($data_xml['status'] == 'success') {
                $this->writeImportData($data_xml['value'], $data_xml['tags']); //записываем атрибуты
                Yii::$app->cache->set('categoryXml', $data_xml['categories']);

                /* Собираем поля для выборки категорий */
                $chooseCategory = $this->getData($model->id, 'category_prosto.json');
                $data_category = ['category' => $data_xml['categories'], 'chooseCategory' => $chooseCategory];
                $render['configCategory'] = $this->renderAjax('config-category', ['data_category' => $data_category]);

                /* Собираем поля для выборки настроек данных продукта */
                $main_fields = $this->getData($model->id, 'main_fields.json');
                $additional_fields = $this->getData($model->id, 'additional_fields.json');
                $chooseFields = array_merge($main_fields, $additional_fields);
                $render['configField'] = $this->renderAjax('config-field', ['field' => $data_xml['fields'], 'chooseFields' => $chooseFields]);
                $render['currency'] = $data_xml['currency'];
                $characterVal = $this->getData($model->id, 'characters_shop.json');
                $render['characterVal'] = array_intersect($characterVal, $data_xml['fields']['param']);
                $answer = ['status' => 'success', 'data' => $render];
            } else {
                $answer = $data_xml;
            }
            return JSON::encode($answer);
        }
    }

    private function formattedCategoryArray(array $category_xml = []) {
        $category = [];
        //set id to index element for check isset parent
        $indexed_category = \yii\helpers\ArrayHelper::index($category_xml, function ($item) {
                    return $item['attributes']['id'] ?? 'undefined';
                });
        foreach ($indexed_category as $key => $oneCategory) {
            if (isset($oneCategory['attributes']['parentId']) && isset($indexed_category[$oneCategory['attributes']['parentId']])) {
                $category[$key]['name'] = $oneCategory['value'];
                $category[$key]['parent_id'] = $oneCategory['attributes']['parentId'];
            } else {
                $category[$key]['name'] = $oneCategory['value'];
                $category[$key]['parent_id'] = 0;
            }
        }
        return $this->createNesting($category);
    }

    private function createNesting($mas, $parent_id = 0) {
        $data = [];
        foreach ($mas as $k => $v) {
            if ($v['parent_id'] == $parent_id) {
                $data[$k]['parent'] = $v;
                $data[$k]['child'] = $this->createNesting($mas, $k);
            }
        }
        return $data;
    }

    protected function findModel($id) {
        if (($model = ParseShop::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
