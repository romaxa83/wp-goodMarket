<?php

namespace backend\modules\order\controllers;

use backend\modules\category\models\CategoryLang;
use backend\modules\product\models\ProductLang;
use backend\modules\product\models\VProductLang;
use common\models\Lang;
use frontend\assets\AppAsset;
use yii\db\Exception;
use yii\helpers\StringHelper;
use backend\modules\order\models\HistoryStatusOrder;
use backend\modules\order\helpers\SettingsHelper;
use backend\modules\order\service\OrderService;
use backend\modules\settings\models\Settings;
use Behat\Gherkin\Exception\CacheException;
use common\models\Curl;
use common\models\Guest;
use Yii;
use backend\controllers\BaseController;
use yii\base\Module;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use common\service\CacheProductService;
use backend\modules\order\models\Order;
use backend\modules\order\models\OrderProduct;
use backend\modules\order\models\OrderSearch;
use common\models\User;
use backend\modules\product\controllers\ProductController;
use backend\modules\category\controllers\CategoryController;
use backend\modules\product\models\Product;
use backend\modules\product\models\VProduct;
use backend\modules\category\models\Category;
use backend\modules\stock\models\StocksProducts;
use backend\modules\stock\models\Stock;
use yii\web\Response;
use yii\bootstrap\ActiveForm;
use DateTime;
use common\controllers\AccessController;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use function GuzzleHttp\Promise\all;

class OrderController extends BaseController {

    const API_KEY = '478bfd6dc12220f3b32a997ff0493520';

    private $order_service;
    private $settings;

    public function __construct($id, Module $module, OrderService $order_service, array $config = []) {
        $this->order_service = $order_service;
        $this->settings = new SettingsHelper();
        parent::__construct($id, $module, $config);
    }

    public function behaviors()
    {
        $rules = [
            [
                'allow' => true,
                'actions' => ['guest-order', 'ajax-search-settlement', 'ajax-get-warehouses-by-settlement', 'ajax-get-warehouses', 'create-order', 'create-order-by-click', 'ajax-search-settlement', 'new-order-count'],
                'roles'=> ['@', '?']
            ]
        ];
        $rules = array_merge($rules, AccessController::getAccessRules(Yii::$app->controller));
        return [
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

     public function beforeAction($action) {
        if ($action->id == 'ajax-search-settlement' ||
                $action->id == 'ajax-get-warehouses' ||
                $action->id == 'new-order-count' ||
                $action->id == 'guest-order' ||
                $action->id == 'create-order-by-click' ||
                $action->id == 'create-order' ||
                $action->id == 'ajax-get-products-by-order' ||
                $action->id == 'ajax-get-warehouses-by-settlement'
        ) {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actionIndex() {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $settings = new SettingsHelper();
        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                    'user_settings' => Yii::$app->user->identity->getSettings('order'),
                    'delivery_list' => $this->settings->getAllList('delivery', true),
                    'payment_list' => $this->settings->getAllList('payment', true)
        ]);
    }

    public function actionCreate() {
        $model = new Order();
        $guest = new Guest();
        if (Yii::$app->request->post('save')) {
            $post = Yii::$app->request->post();
            $model->scenario = $this->getScenario('order', $post);
            $guest->scenario = $this->getScenario('guest', $post);
        } else {
            $model->scenario = Order::DELIVERY_COURIER_USER;
            $guest->scenario = Guest::REGISTER_ADMIN_IN_ORDER;
        }
        if (empty($this->getCategories())) {
            Yii::$app->session->setFlash('error', 'Нету ни одной активной категории');
            return $this->redirect(['/order/order']);
        }
        if ($model->load(Yii::$app->request->post())) {
            $data = Yii::$app->request->post();
            $user_status_id = $data['Order']['user_status'];
            if ($user_status_id == 2) {
                if ($guest->load(Yii::$app->request->post())) {
                    if (!$guest_id = $this->saveGuest($guest)) {
                        return $this->redirect(['/order/order/create']);
                    }
                    $data['Order']['user_id'] = $guest_id;
                }
            }

            $user_id = $data['Order']['user_id'];
            $status = ($user_status_id == 1) ? 'user_id' : 'guest_id';
            $data['Order'][$status] = $user_id;
            if (!$model = $this->saveOrder($model, $data)) {
                return $this->redirect(['/order/order/create']);
            }
            $model->date = date('Y-m-d H:i:s');
            $model->status = 1;
            if ($model->save()) {
                $model->sync = 1;
                $model->save();
                if (!OrderProduct::saveProducts($model->id, $data['products_data'])) {
                    return $this->redirect(['/order/order/create']);
                }
                Yii::$app->session->setFlash('success', 'Заказ усешно добавлен');
            } else {
                $this->deleteOrder($model->id);
                Yii::$app->session->setFlash('error', ArrayHelper::getColumn($model->errors, 0, false));
                return $this->redirect(['/order/order/create']);
            }
            return $this->redirect([$data['save']]);
        }
        $dataProvider = $this->createProductTable();
        $userList = ArrayHelper::map(User::find()->select(['id', 'username'])->asArray()->all(), 'id', 'username');
        $order_products_params = [
            'lang_list' => Lang::getSelect2List(),
            'dataProvider' => $dataProvider,
            'type' => 'edit',
            'order_summ' => ''
        ];
        $payment_method_list = $this->settings->getAllList('payment', true);
        $delivery_list = $this->settings->getAllList('delivery', true);
        return $this->render('form-order', [
            'model' => $model,
            'guest' => $guest,
            'userList' => $userList,
            'order_summ' => '',
            'field_visible' => true,
            'payment_method_list' => $payment_method_list,
            'delivery_list' => $delivery_list,
            'order_products_params' => $order_products_params]);
    }

    public function actionEdit($id) {
        $model = Order::findOne($id);
        if ($model->status != Order::STATUS_NEW) {
            Yii::$app->session->setFlash('warning', 'Только новый заказ можно редактировать');
            return $this->redirect(['/order/order']);
        }

        $guest = Guest::findOne($model->guest_id);
        if (Yii::$app->request->post('save')) {
            $post = Yii::$app->request->post();
            $post['Order']['user_status'] = (Order::findOne($id)->user_id == null) ? 2 : 1;
            $model->scenario = $this->getScenario('order', $post);
            if (!empty($guest)) {
                $guest->scenario = $this->getScenario('guest', $post);
            } else {
                $guest = new Guest();
            }
        } else {
            if (!empty($guest)) {
                $model->scenario = Order::DELIVERY_COURIER_GUEST;
                $guest->scenario = Guest::EDIT_GUEST_BACK;
            } else {
                $guest = new Guest();
                $model->scenario = Order::DELIVERY_COURIER_USER;
                $guest->scenario = 'default';
            }
        }
        if ($model->delivary == 2) {

            if ($guest->scenario == 'default') {
                $model->scenario = Order::DELIVERY_NP_USER;
            } else {
                $model->scenario = Order::DELIVERY_NP_GUEST;
            }
        }
        if (empty($this->getCategories())) {
            Yii::$app->session->setFlash('error', 'Нету ни одной активной категории');
            return $this->redirect(['/order/order']);
        }
        $field_visible = true;
        if ($model->load(Yii::$app->request->post())) {
            $data = Yii::$app->request->post();
            $user_status_id = ($model->user_id != null) ? 1 : 2;
            if ($user_status_id == 2) {
                if ($guest->load(Yii::$app->request->post())) {
                    if (!$guest_id = $this->saveGuest($guest)) {
                        return $this->redirect(['/order/order/edit?id=' . $id]);
                    }
                }
            }
            if (!$model = $this->saveOrder($model, $data)) {
                return $this->redirect(['/order/order/edit?id=' . $id]);
            }
            $prod_count = OrderProduct::find()->where(['order_id' => $id])->count();
            if (!($prod_count > 0)) {
                Yii::$app->session->setFlash('error', 'В заказе отсутствуют товары');
                return $this->redirect(['/order/order/edit?id=' . $id]);
            }
            if ($model->save()) {
                $model->sync = 1;
                Yii::$app->session->setFlash('success', 'Заказ усешно отредактирован');
                return $this->redirect([$data['save']]);
            } else {
                Yii::$app->session->setFlash('error', ArrayHelper::getColumn($model->errors, 0, false));
                return $this->redirect(['/order/order/edit?id=' . $id]);
            }
        }
        $userList = ArrayHelper::map(User::find()->select(['id', 'username'])->asArray()->all(), 'id', 'username');
        $order_summ = OrderProduct::getOrderCostStr($id);
        $dataProvider = $this->createProductTable(OrderProduct::getDataByOrderID($id));
        $order_products_params = [
            'lang_list' => Lang::getSelect2List(),
            'dataProvider' => $dataProvider,
            'type' => 'edit',
            'order_summ' => $order_summ
        ];
        $payment_method_list = $this->settings->getAllList('payment', true);
        $delivery_list = $this->settings->getAllList('delivery', true);
        return $this->render('form-order', [
            'model' => $model,
            'guest' => $guest,
            'userList' => $userList,
            'field_visible' => $field_visible,
            'order_products_params' => $order_products_params,
            'payment_method_list' => $payment_method_list,
            'delivery_list' => $delivery_list,
            'order_summ' => $order_summ]);
    }

    public function actionDelete($id) {
        $model = Order::findOne($id);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            OrderProduct::deleteAll(['order_id' => $id]);
            if ($model->delete()) {
                if ($model->guest_id != null) {
                    Guest::findOne(['id' => $model->guest_id])->delete();
                }
                $transaction->commit();
                Yii::$app->session->setFlash('success', 'Заказ успешно удален');
            } else {
                throw new \Exception(implode("<br />" , ArrayHelper::getColumn($model->errors,0,false)));
            }
        } catch (\Throwable $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['/order/order']);
    }

    private function getStockId($model, $id) {
        return $model->className()::find()->select('stock_id')->where(['id' => $id])->asArray()->one()['stock_id'];
    }

    private function getCategories() {
        $category_list = Category::find()->where(['publish' => 1, 'publish_status' => 1])->asArray()->all();
        return $category_list;
    }

    private function issetUser($user_id) {
        if (!User::findOne($user_id)) {
            return false;
        }
        return true;
    }

    private function checkKitSale($stock_id, $product_id, $products) {
        $stock_products_ids = StocksProducts::getStockProductsIDs($stock_id);
        $products_ids = array_column($products, 'product_id');
        if (empty(array_diff($stock_products_ids, $products_ids))) {
            return true;
        }
        return false;
    }

    private function getProductSale($product_id, $vproduct_id, $products) {
        $flag = false;
        $stock_id = StocksProducts::getStockByProduct($product_id, $vproduct_id);
        if ($stock_id != null) {
            $stock_type = Stock::findOne($stock_id)->type;
            if ($stock_type == 1) {
                $stock_products_ids = StocksProducts::getStockProductsIDs($stock_id);
                $products_ids = array_column($products, 'product_id');
                if (!in_array($product_id, $products_ids)) {
                    array_push($products_ids, $product_id);
                }
                if (empty(array_diff($stock_products_ids, $products_ids))) {
                    $flag = true;
                }
            } else {
                $flag = true;
            }
            if ($flag == true) {
                $sale = StocksProducts::getSale($product_id, $vproduct_id, $stock_id);
            } else {
                $sale = 0;
            }
            return $sale;
        }
        return 0;
    }

    private function getProductDetailData($category_id, $product_id, $vproduct_id, $count, $price, $product_price) {
        $poduct = [];
        $vproduct = [];
        if ($product_id != 0) {
            $product = Product::getProduct($product_id, $category_id);
            if (!empty($product)) {
                if ($vproduct_id != 0) {
                    $vproduct = VProduct::getVProductsByProduct($product_id)[$vproduct_id];
                    $product['variation'] = $vproduct['char_value'];
                }
                $product['summ'] = $count * $product_price;
                $product['sale_summ'] = $price * $count;
                $product['sale'] = (1 - $price / $product_price) * 100;
                $product['order_amount'] = $count;
                $product['price'] = $price;
            }
        }
        return $product;
    }

    private function getScenario($model_name, $post) {
        $post_order = $post['Order'];
        $order = 'default';
        $guest = 'default';
        if ($post_order['user_status'] == 2) {
            if ($post_order['delivary'] == 2) {
                $order = Order::DELIVERY_COURIER_GUEST;
            } else {
                $order = Order::DELIVERY_NP_GUEST;
            }
            if (Yii::$app->request->post('save') == 'add') {
                $guest = Guest::REGISTER_ADMIN_IN_ORDER;
            } else {
                $guest = Guest::EDIT_GUEST_BACK;
            }
        } else {
            if ($post_order['delivary'] == 2) {
                $order = Order::DELIVERY_COURIER_USER;
            } else {
                $order = Order::DELIVERY_NP_USER;
            }
        }
        $result = ['order' => $order, 'guest' => $guest];
        return $result[$model_name];
    }

    private function saveGuest(Guest $guest) {
        $result = false;
        if ($post = Yii::$app->request->post('Guest')) {
            foreach ($post as $key => $value) {
                $guest->$key = $value;
            }
            if (empty($guest->phone)) {
                $guest->phone = $guest->phone_one_click;
            }
            $result = $guest->save();
        }
        if ($result == false) {
            Yii::$app->session->setFlash('error', implode("<br />" , ArrayHelper::getColumn($guest->errors,0,false)));
            return false;
        }
        return $guest->id;
    }

    private function saveOrder($model, $data) {
        foreach ($data['Order'] as $key => $value) {
            $model->$key = $value;
        }
        if (!$this->issetUser($model->user_id) && $model->user_status == 1) {
            Yii::$app->session->setFlash('error', 'Не существует пользователя с таким id');
            return false;
        }
        if ($this->getSettlementByRef($model->city) != false) {
            $model->city = $this->getSettlementByRef($model->city);
        }
        if (!empty($model->street) && !empty($model->home)) {
            $model->address = $model->street . '|' . $model->home . '|' . $model->flat;
        }
        if (empty($model->address)) {
            Yii::$app->session->setFlash('error', 'Заполните адрес доставки');
            return false;
        }
        return $model;
    }

    private function deleteOrder($id) {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = Order::findOne($id);
            OrderProduct::deleteAll(['order_id' => $id]);
            if ($model->delete()) {
                if ($model->guest_id != null) {
                    Guest::findOne(['id' => $model->guest_id])->delete();
                }
                $transaction->commit();
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            $transaction->rollBack();
            return false;
        }
    }

    // В админке возвращает данные для select2 (категории) на основе выбранного языка
    public function actionAjaxGetCategories($lang_id) {
        if (Yii::$app->request->isAjax) {
            $category_list = Category::find()->select(['id'])->with(['categoryLang' => function ($query) use ($lang_id) {
                $query->where(['lang_id' => $lang_id]);
            }])->where(['publish' => 1])->asArray()->all();

            foreach ($category_list as $k => $v) {
                if (empty($v['categoryLang'])) {
                    unset($category_list[$k]);
                }
            }
            $category_list = ArrayHelper::getColumn($category_list, function ($element) {
                if (isset($element['categoryLang'][0])) {
                    return ['id' => $element['id'], 'text' => $element['categoryLang'][0]['name']];
                }
            });

            if (!empty($category_list)) {
                return Json::encode($category_list);
            } else {
                return Json::encode([['id' => 0, 'text' => 'Ничего не найдено']]);
            }
        }
    }

    // В админке возвращает данные для select2 (продукты) на основе выбранной категории и языка
    public function actionAjaxGetCategoryProducts($category_id, $lang_id) {
        if (Yii::$app->request->isAjax) {
            $product_list = Product::find()->select(['id'])->with(['productLang' => function ($query) use ($lang_id) {
                $query->where(['lang_id' => $lang_id]);
            }])->where(['publish' => 1, 'category_id' => $category_id])->asArray()->all();

            foreach ($product_list as $k => $v) {
                if (empty($v['productLang'])) {
                    unset($product_list[$k]);
                }
            }
            $product_list = ArrayHelper::getColumn($product_list, function ($element) {
                if (isset($element['productLang'][0])) {
                    return ['id' => $element['id'], 'text' => $element['productLang'][0]['name']];
                }
            });

            if (!empty($product_list)) {
                return Json::encode($product_list);
            } else {
                return Json::encode([['id' => 0, 'text' => 'Ничего не найдено']]);
            }
        }
    }

    private function getCategoryID($product_id) {
        return Product::find()->select('category_id')->where(['id' => $product_id])->one()->category_id;
    }

    // создает $dataProvider для products-table view
    private function createProductTable($data = []) {
        $order_products = [];
        if (!empty($data)) {
            $products = Product::find()->select(['id', 'amount', 'category_id'])->where(['in', 'id', ArrayHelper::getColumn($data, 'product_id')])
                ->with([
                    'productLang' => function ($query) {
                        $query->select('product_id, lang_id, name, price');
                    }])
                ->with([
                    'categoryLang' => function ($query) {
                        $query->select('category_id, lang_id, name');
                    }])
                ->with('vproducts.vProductLang')
                ->asArray()->all();
            $products = ArrayHelper::index($products, 'id');
            $products = CategoryLang::indexLangBy($products);
            $products = ProductLang::indexLangBy($products);
            $products = VProduct::indexBy($products);
            $products = VProductLang::indexLangBy($products);
            $default_lang_id = Lang::getDefaultLangID();
            foreach ($data as $k => $v) {
                    $pid = $v['product_id'];
                if (isset($data[$k]['vproduct_id']) && $data[$k]['vproduct_id'] != 0) {
                    $order_products[$k]['amount'] = $products[$pid]['vproducts'][$data[$k]['vproduct_id']]['amount'];
                    $order_products[$k]['variation'] = $products[$pid]['vproducts'][$data[$k]['vproduct_id']]['char_value'];
                    $order_products[$k]['vproduct_id'] = $data[$k]['vproduct_id'];
                } else {
                    $order_products[$k]['amount'] = $products[$pid]['amount'];
                    $order_products[$k]['variation'] = 'Не выбрано';
                    $order_products[$k]['vproduct_id'] = 0;
                }
                $order_products[$k]['product_id'] = $data[$k]['product_id'];
                $order_products[$k]['category'] = isset($products[$pid]['categoryLang'][$data[$k]['lang_id']]['name']) ? $products[$pid]['categoryLang'][$data[$k]['lang_id']]['name'] : $products[$pid]['categoryLang'][$default_lang_id]['name'];
                $order_products[$k]['product'] = isset($products[$pid]['productLang'][$data[$k]['lang_id']]['name']) ? $products[$pid]['productLang'][$data[$k]['lang_id']]['name'] : $products[$pid]['productLang'][$default_lang_id]['name'];
                $order_products[$k]['count'] = $data[$k]['count'];
            }
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => $order_products,
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);
        $dataProvider->getModels();
        return $dataProvider;
    }

    public function actionAjaxReloadProductsTable() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post('data');
            $dataProvider = $this->createProductTable($data);
            $order_summ = OrderProduct::getOrderCostStr(0, $data);
            $response['products_table'] = $this->renderPartial('products-table', [
                'dataProvider' => $dataProvider,
                'type' => 'edit',
                'order_summ' => $order_summ]);
            $response['order_summ'] = $order_summ;
            return Json::encode($response);
        }
    }

    // В админке возвращает данные для select2 (вариация) на основе выбранного продукта
    public function actionAjaxGetProductVariations() {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->request->post()) {
                $data = Yii::$app->request->post();
                $v_product_list = VProduct::getVProductsByProduct($data['product_id']);
                if (!empty($v_product_list)) {
                    $v_products = ArrayHelper::getColumn($v_product_list, function ($element) {
                                return ['id' => $element['id'], 'text' => $element['char_value']];
                            });
                    $v_products[0] = ['id' => 0, 'text' => 'Без вариации'];
                    return Json::encode($v_products);
                }
            }
        }
        return Json::encode([['id' => 0, 'text' => 'Ничего не найдено']]);
    }

    public function actionAjaxSearchSettlement() {
        if (isset($_GET['q'])) {
            $addresses = $this->searchSettlement($_GET['q']);
            if (!empty($addresses)) {
                $addresses = ArrayHelper::getColumn($addresses, function($element) {
                            return ['id' => $element["Ref"],
                                'text' => $element["Present"]];
                        });
                return Json::encode($addresses);
            }
            return Json::encode([['id' => 0, 'text' => 'Ничего не найдено']]);
        }
        return Json::encode([['id' => 0, 'text' => 'Ничего не найдено']]);
    }

    public function actionAjaxSearchSettlementBack($q = null, $id = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (isset($q)) {
            $addresses = $this->searchSettlement($q);
            if (!empty($addresses)) {
                $addresses = ArrayHelper::getColumn($addresses, function($element) {
                            return ['id' => $element["Ref"],
                                'text' => $element["Present"]];
                        });
                $out['results'] = $addresses;
            } else {
                $out['results'] = ['id' => 0, 'text' => 'Ничего не найдено'];
            }
        } else {
            $out['results'] = ['id' => 0, 'text' => 'Ничего не найдено'];
        }
        return $out;
    }

    public function getSettlementByRef($ref) {
        $result = false;
        $api_params = '{
                        "modelName": "AddressGeneral",
                        "calledMethod": "getSettlements",
                        "methodProperties": {
                            "Ref": "' . $ref . '"
                        },
                        "apiKey": "' . self::API_KEY . '"
                    }';
        $settlement_data = $this->apiNovaposhtaConnect($api_params);
        $errors = $settlement_data->errors;
        $warnings = $settlement_data->warnings;
        if (empty($errors) && empty($warnings)) {
            $settlement_data = ArrayHelper::toArray($settlement_data)["data"][0];
            $address = $settlement_data["SettlementTypeDescription"] . ' ' . $settlement_data["Description"] . ', ' . $settlement_data["RegionsDescription"] . ', ' . $settlement_data["AreaDescription"];
            $result = $address;
        }
        return $result;
    }

    public function actionAjaxGetWarehousesBySettlement($settlement) {
        if (Yii::$app->request->isAjax) {
            if (!empty($settlement)){
                $settlement_data = $this->searchSettlement($settlement);
                $warehouses = $this->getWarehouses($settlement_data[0]['Ref']);
                if (!empty($warehouses)) {
                    $warehouses = ArrayHelper::getColumn($warehouses, function($element) {
                        return ['id' => $element["Description"],
                            'text' => $element["Description"]];
                    });
                    return Json::encode($warehouses);
                }
            }
            return Json::encode([['id' => 0, 'text' => 'Ничего не найдено']]);
        }
    }

    public function searchSettlement($city_name) {
        $address_data = [];
        $api_params = '{
                        "apiKey": "' . self::API_KEY . '",
                        "modelName": "Address",
                        "calledMethod": "searchSettlements",
                        "methodProperties": {
                            "CityName": "' . $city_name . '",
                            "Limit": 35
                        }
                    }';
        $settlements_data = $this->apiNovaposhtaConnect($api_params);
        if (!empty($settlements_data)) {
            $settlements_data = ArrayHelper::toArray($settlements_data)["data"][0];
            $count = $settlements_data["TotalCount"];
            if ($count > 0) {
                $address_data = $settlements_data["Addresses"];
            }
        }
        return $address_data;
    }

    public function actionAjaxGetWarehouses() {
        $wh_ref = Yii::$app->request->post('id');
        $warehouses = $this->getWarehouses($wh_ref);
        if (!empty($warehouses)) {
            $warehouses = ArrayHelper::getColumn($warehouses, function($element) {
                        return ['id' => $element["Description"],
                            'text' => $element["Description"]];
                    });
            return Json::encode($warehouses);
        }
        return Json::encode([['id' => 0, 'text' => 'Ничего не найдено']]);
    }

    public function getWarehouses($wh_ref) {
        $warehouses_data = [];
        $api_params = '{
                        "modelName": "AddressGeneral",
                        "calledMethod": "getWarehouses",
                        "methodProperties": {
                            "Language": "ru",
                            "SettlementRef": "' . $wh_ref . '"
                        },
                        "apiKey": "' . self::API_KEY . '"
                    }';
        $warehouses_data = $this->apiNovaposhtaConnect($api_params);
        if (!empty($warehouses_data)) {
            $warehouses_data = ArrayHelper::toArray($warehouses_data)["data"];
        }
        return $warehouses_data;
    }

    public function apiNovaposhtaConnect($api_params = null) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.novaposhta.ua/v2.0/json/",
            CURLOPT_RETURNTRANSFER => True,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $api_params,
            CURLOPT_HTTPHEADER => array("content-type: application/json"),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $result = "cURL Error #:" . $err;
        } else {
            $result = $response;
        }
        return json_decode($result);
    }

    public function actionCreateOrder() {
        if (Yii::$app->request->isAjax) {
            $message = 'Не удалось добавить заказ';
            $post = Yii::$app->request->post();
            $order = new Order();
            $order->status = $order::STATUS_NEW;
            $order->city = $post['city'];
            $order->payment_method = $post['payment'];
            $order->comment = $post['comment'] ?: null;
            $order->delivary = $post['delivery'];
            $order->date = date('Y-m-d H:i:s');
            $order->address = $post['office'];
            $order->phone = $post['phone'];
            if ($post['delivery'] == $order::DELIVERY_COURIER) {
                $flat = !empty($post['flat']) ? '|' . $post['flat'] : '';
                $order->address = $post['street'] . '|' . $post['house'] . $flat;
            }
            if ($post['user_status'] == 'user') {
                $order->user_id = $post['user_id'];
            } else {
                $order->guest_id = $post['user_id'];
            }
            if ($post['user_status'] == 'user') {
                $count_product = (int)Yii::$app->db->createCommand("SELECT COUNT(*) as count FROM cart_items WHERE user_id = {$order->user_id} ")->queryScalar();
            } else {
                $count_product = isset($post['cart']) ? count($post['cart']) : 0;
            }
            if ($count_product > 0) {
                if ($order->save()) {
                    $order_id = $order->id;
                    if ($post['user_status'] == 'user') {
                        $this->order_service->moveProductForUser($post['user_id'], $order->id);
                        $this->order_service->clearCart($post['user_id']);
                        $answer = ['status' => 'success', 'message' => 'Ваш заказ принят'];
                    } else {
                        $this->order_service->moveProductForGuest($post['cart'], $order_id);
                        $answer = ['status' => 'delete-cart', 'message' => 'Ваш заказ принят'];
                    }
                    return JSON::encode($answer);
                }
            } else {
                $message = 'Оформления заказа без товаров запрещено';
            }
        }
        return JSON::encode(['status' => 'error', 'message' => $message]);
    }

    public function actionCreateOrderByClick() {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            $order = new Order();
            $guest = new Guest();
            $guest->phone = $post['phone'];
            $guest->save();
            $order->status = $order::STATUS_ONE_CLICK;
            $order->payment_method = $order::PAYMENT_UNKNOWN;
            $order->delivary = $order::DELIVERY_UNKNOWN;
            $order->date = date('Y-m-d H:i:s');
            $order->phone = $post['phone'];
            $order->guest_id = $guest->id;
            if ($order->save()) {
                if(empty($post['cart'][0])){
                    return JSON::encode(['status' => 'error', 'message' => 'Оформления заказа без товаров запрещено']);
                }
                if ($post['product_count'] == 'cart_product_modal') {
                    $cart = $post['cart'][0];
                } elseif ($post['product_count'] == 'one_product') {
                    if (!empty($post['cart'][2])) {
                        $vproduct_id = VProduct::find()->select('stock_id')->where(['product_id' => OrderProduct::getStockId(new Product(), $post['cart'][0])])
                                        ->andFilterWhere(['like', 'char_value', trim($post['cart'][2])])->asArray()->one();
                        $post['cart'][2] = $vproduct_id['stock_id'];
                    }
                    $cart = [$post['cart']];
                } else {
                    $cart = $post['cart'];
                }
                $this->order_service->moveProductForGuest($cart, $order->id);
            }
            if ($post['product_count'] == 'cart_product' || $post['product_count'] == 'cart_product_modal') {
                if ($post['user_id']) {
                    $this->order_service->clearCart($post['user_id']);
                    return JSON::encode(['status' => 'success', 'message' => 'Ваш заказ был оформлен.Наш оператор перезвонит вам для уточнения деталей']);
                }
                return JSON::encode(['status' => 'delete-cart']);
            }
            return JSON::encode(['status' => 'success', 'message' => 'Ваш заказ был оформлен.Наш оператор перезвонит вам для уточнения деталей']);
        }
    }

    /**
     * Оформление заказа от Гостя
     * @return string
     */
    public function actionGuestOrder() {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            $guest = Guest::find()->select('id')->andWhere(['phone' => $post['Guest']["phone"]])->orWhere(['email' => $post['Guest']["email"]])->one();
            if (empty($guest)) {
                $guest = new Guest();
            }
            $guest->scenario = Guest::FRONT_ORDER_PERSONAL;
            if ($guest->load($post) && $guest->validate()) {
                ($guest->first_name != $post['Guest']["first_name"]) ? $guest->last_name = $post['Guest']["first_name"] : null;
                ($guest->last_name != $post['Guest']["last_name"]) ? $guest->last_name = $post['Guest']["last_name"] : null;
                ($guest->email != $post['Guest']["email"]) ? $guest->email = $post['Guest']["email"] : null;
                ($guest->phone != $post['Guest']["phone"]) ? $guest->phone = $post['Guest']["phone"] : null;
                if ($guest->save(false)) {
                    $response = ['/order/index', 'guest' => $guest->id];
                }
            } else {
                $resp['errors'] = $guest->errors;
                $response = ['/order/index'];
            }
            $resp['url'] = Yii::$app->urlManagerFrontend->createUrl($response);
            return Json::encode($resp);
        }
    }

    /* метод возвращает кол-во новых заказов */

    public function actionNewOrderCount() {
        if (Yii::$app->request->isAjax) {
            if (Order::find()->where(['status' => Order::STATUS_NEW])->exists()) {
                return Order::find()->where(['status' => Order::STATUS_NEW])->count();
            }
            return false;
        }
    }

    public function actionGetCountOrder() {
        $dateStart = new DateTime('-1 month');
        $dateEnd = new DateTime();
        $order = Order::find()
                ->select(['SUBSTRING(date,1,10) as date', 'COUNT(case `status` when 1 then 1 end) as new', 'COUNT(*) as value'])
                ->asArray()
                ->where(['between', 'date', $dateStart->format('Y-m-d H:i:s'), $dateEnd->format('Y-m-d H:i:s')])
                ->groupBy('day(date)')
                ->all();
        return Json::encode($order);
    }

    public function actionAjaxGetProductsByOrder($order_id) {
        if (Yii::$app->request->isAjax) {
            $request = OrderProduct::find()->where(['order_id' => $order_id])->asArray()->all();
            $request = ArrayHelper::getColumn($request, function($element) {
                        return [
                            'product_id' => $element['product_id'],
                            'vproduct_id' => $element['vproduct_id'],
                            'lang_id' => $element['lang_id'],
                            'category_id' => $this->getCategoryID($element['product_id']),
                            'price' => $element['price'],
                            'product_price' => $element['product_price'],
                            'count' => $element['count'],
                            'currency' => $element['currency']
                        ];
                    });
            return Json::encode($request);
        }
    }

    public function actionAjaxShowProductData() {
        if (Yii::$app->request->isAjax) {
            if ($post = Yii::$app->request->post('product')) {
                $product = $this->getProductDetailData($post['category_id'], $post['product_id'], $post['vproduct_id'], $post['count'], $post['price'], $post['product_price']);

                return $this->renderPartial('product-info', ['product' => $product]);
            }
        }
        return false;
    }

    public function actionShowOrderProducts($id) {
        $dataProvider = $this->createProductTable(OrderProduct::getDataByOrderID($id));
        $order_products_params = [
            'category_list' => ArrayHelper::getColumn($this->getCategories(), 'name'),
            'dataProvider' => $dataProvider,
            'type' => 'view',
            'order_summ' => OrderProduct::getOrderCostStr($id),
            'lang_list' => Lang::getSelect2List(),
        ];
        return $this->render('product-view', [
            'order_products_params' => $order_products_params,
            'userList' => ArrayHelper::map(User::find()->select(['id', 'username'])->asArray()->all(), 'id', 'username')
        ]);
    }

    public function actionValidation($id = 0) {
        $order = new Order();
        $guest = new Guest();
        if (Yii::$app->request->isAjax) {
            if ($post = Yii::$app->request->post()) {
                if (!array_key_exists('user_status', $post['Order'])) {
                    if ($id != 0) {
                        $post['Order']['user_status'] = (Order::findOne($id)->user_id == null) ? 2 : 1;
                    }
                }
                $order->scenario = $this->getScenario('order', $post);
                $guest->scenario = $this->getScenario('guest', $post);
                $guest->load($post);
                $order->load($post);
                \Yii::$app->response->format = Response::FORMAT_JSON;
                return json_encode(ActiveForm::validate($guest, $order));
                // \Yii::$app->end();
            }
        }
    }

    public function actionAjaxGetProductPrice($order_id = 0) {
        if (Yii::$app->request->isAjax) {
            if ($post = Yii::$app->request->post()) {
                if ($post['products'] == 'empty') {
                    $post['products'] = [];
                }

                $product = Product::find()->select(['id'])->where(['id' => $post['product_id']])->with('productLang')->with('vproducts.vProductLang')->asArray()->all();
                $product = ArrayHelper::index($product, 'id');
                $product = ProductLang::indexLangBy($product, 'lang_id');
                $product = VProduct::indexBy($product);
                $product = VProductLang::indexLangBy($product);

                if ($post['vproduct_id'] > 0) {
                    $product_price = isset($product[$post['product_id']]['vproducts'][$post['vproduct_id']]['vProductLang'][$post['lang_id']]['price'])
                    && !empty($product[$post['product_id']]['vproducts'][$post['vproduct_id']]['vProductLang'][$post['lang_id']]['price'])
                    ? $product[$post['product_id']]['vproducts'][$post['vproduct_id']]['vProductLang'][$post['lang_id']]['price']
                    : $product[$post['product_id']]['vproducts'][$post['vproduct_id']]['vProductLang'][Lang::getDefaultLangID()]['price'];

                    $currency = isset($product[$post['product_id']]['vproducts'][$post['vproduct_id']]['vProductLang'][$post['lang_id']]['currency'])
                    && !empty($product[$post['product_id']]['vproducts'][$post['vproduct_id']]['vProductLang'][$post['lang_id']]['currency'])
                    ? $product[$post['product_id']]['vproducts'][$post['vproduct_id']]['vProductLang'][$post['lang_id']]['currency']
                    : $product[$post['product_id']]['vproducts'][$post['vproduct_id']]['vProductLang'][Lang::getDefaultLangID()]['currency'];
                } else {
                    $product_price = isset($product[$post['product_id']]['productLang'][$post['lang_id']]['price'])
                    && !empty($product[$post['product_id']]['productLang'][$post['lang_id']]['price'])
                    ? $product[$post['product_id']]['productLang'][$post['lang_id']]['price']
                    : $product[$post['product_id']]['productLang'][Lang::getDefaultLangID()]['price'];

                    $currency = isset($product[$post['product_id']]['productLang'][$post['lang_id']]['currency'])
                    && !empty($product[$post['product_id']]['productLang'][$post['lang_id']]['currency'])
                    ? $product[$post['product_id']]['productLang'][$post['lang_id']]['currency']
                    : $product[$post['product_id']]['productLang'][Lang::getDefaultLangID()]['currency'];
                }

                // Добавить модуль акций и пересмотреть
                //$sale = $this->getProductSale($post['product_id'], 0, $post['products']);
                //$sale = $this->getProductSale($post['product_id'], $post['vproduct_id'], $post['products']);
                $sale = 0;
                $price = $product_price - ($sale * $product_price) / 100;

                $data = ['product_price' => $product_price, 'price' => $price, 'currency' => $currency];
                return Json::encode($data);
            }
        }
        return false;
    }

    public function actionAjaxSaveProduct($order_id) {
        if (Yii::$app->request->isAjax) {
            if ($post = Yii::$app->request->post()) {
                $new_prod = $post['new_prod'];
                $products = Json::decode($post['products']);
                $order_product = OrderProduct::find()->where([
                    'order_id' => $order_id,
                    'product_id' => $new_prod['product_id'],
                    'vproduct_id' => (isset($new_prod['vproduct_id'])) ? $new_prod['vproduct_id'] : null])->one();
                if (is_null($order_product)) {
                    $order_product = new OrderProduct();
                    $action = 'createOrderProduct';
                    array_push($products, $new_prod);
                } else {
                    $action = 'updateOrderProduct';
                }
                unset($new_prod['category_id']);
                foreach ($new_prod as $key => $value) {
                    $order_product->$key = $value;
                }
                $order_product->order_id = $order_id;
                if ($order_product->save()) {
                    $products_db = OrderProduct::find()->select('product_id, vproduct_id, lang_id, count, price, product_price, currency')
                        ->where(['order_id' => $order_id])->asArray()->all();
                    $products = array_map(function($arr1, $arr2) {
                        return array_merge($arr1, $arr2);
                    }, $products, $products_db);
                    $products = ($products != false) ? $products : [];
                    return Json::encode($products);
                }
            }
        }
        return false;
    }

    public function actionAjaxDeleteProduct($order_id) {
        if (Yii::$app->request->isAjax) {
            if ($post = Yii::$app->request->post()) {
                $order_product = OrderProduct::find()->where([
                    'order_id' => $order_id,
                    'product_id' => $post['index'][0],
                    'vproduct_id' => $post['index'][1]
                ])->one();
                $order_product->delete();
                return Json::encode(true);
            }
        }
        return false;
    }

    public function actionAjaxDeleteOrder($order_id) {
        return $this->deleteOrder($order_id);
    }

    public function actionAjaxSetKitSale() {
        if (Yii::$app->request->isAjax) {
            if ($post = Yii::$app->request->post()) {
//                $order_id = $post['order_id'];
//                $product = $post['product'];
                $product_list = $post['product_list'];
//                return Json::encode($this->setKitSale($product, $product_list, $order_id));
                return Json::encode($product_list);
            }
        }
        return false;
    }

    private function setKitSale(array $product, array $product_list, $order_id = 0) {
        $product_price = $product['product_price'];
        $price = $product['price'];
        if ($product_price > $price) {
            $stock_id = StocksProducts::getStockByProduct($product['product_id'], $product['vproduct_id']);
            $sale_products_ids = StocksProducts::getStockProductsIDs($stock_id);
            if ($this->checkKitSale($stock_id, $product['product_id'], $product_list)) {
                $sale = StocksProducts::getSale($product['product_id'], $product['vproduct_id'], $stock_id);
                if ($order_id > 0) {
                    \Yii::$app->db->createCommand('UPDATE orders_products SET price= (product_price*' . (1 - $sale / 100) . ') WHERE order_id=' . $order_id)->execute();
                }
            } else {
                $sale = 0;
            }
            for ($i = 0; $i < count($sale_products_ids); $i++) {
                for ($j = 0; $j < count($product_list); $j++) {
                    if ($product_list[$j]['product_id'] == $sale_products_ids[$i]) {
                        $product_list[$j]['price'] = $product_list[$j]['product_price'] * (1 - $sale / 100);
                        break;
                    }
                }
            }
        }
        return $product_list;
    }

    public function actionChangeAttribut() {
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            $model = Order::findOne($post['id']);
            $model->{$post['field']} = $post['value'];
            $model->save();
        }
    }

}
