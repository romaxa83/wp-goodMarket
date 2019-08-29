<?php

namespace backend\modules\product\controllers;

use Yii;
use backend\controllers\BaseController;
use yii\helpers\ArrayHelper;
use common\models\Curl;
use backend\modules\product\models\Product;
use backend\widgets\langwidget\LangWidget;
use backend\modules\category\models\Category;
use dosamigos\transliterator\TransliteratorHelper;
use backend\modules\product\models\ProductSearch;
use backend\modules\product\models\Manufacturer;
use yii\helpers\Json;
use backend\modules\filemanager\models\Mediafile;
use backend\widgets\SeoWidget;
use backend\modules\product\models\Characteristic;
use backend\modules\product\models\Group;
use backend\modules\product\models\ProductCharacteristic;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use backend\modules\stock\models\StocksProducts;
use backend\modules\stock\models\Stock;
use backend\modules\product\models\VProductSearch;
use backend\modules\product\models\VProduct;
use backend\modules\exportxml\models\ParseShop;
use common\controllers\AccessController;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use backend\modules\product\models\ProductLang;

class ProductController extends BaseController {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => AccessController::getAccessRules(Yii::$app->controller),
            ],
        ];
    }

    public function beforeAction($action) {
        if (parent::beforeAction($action)) {
            if (!AccessController::checkPermission($action->controller->route)) {
                throw new ForbiddenHttpException('Вам не разрешено производить данное действие.');
            }
            return parent::beforeAction($action);
        } else {
            return false;
        }
    }

    public function actionIndex() {
        $this->view->title = 'Продукты';
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'user_settings' => Yii::$app->user->identity->getSettings('product')
        ]);
    }

    public function actionCreate() {
        $this->view->title = 'Создание продукта';
        $searchModel = new VProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $options = [
            'model' => new Product(),
            'product' => new Product(),
            'characteristic' => new Characteristic(),
            'characteristic_list' => [],
            'product_characteristic' => new ProductCharacteristic(),
            'dataProvider' => $dataProvider
        ];
        return $this->render('form', $options);
    }

    public function actionUpdate() {
        $id = Yii::$app->request->get('id');

        $model = $product = Product::find()->where(['id' => $id])->with('category')->with('productLang.lang')->one();
        if ($product->stock_publish == 0) {
            Yii::$app->session->setFlash('error', 'Продукт отключен на складе');
            return $this->redirect('index');
        }

        $characteristic = new Characteristic();
        $product_characteristic = new ProductCharacteristic();

        $provider = Product::find()->where(['vendor_code' => $product->vendor_code])->with('category')->all();
        $category = ArrayHelper::map(Category::find()->asArray()->all(), 'id', 'name');
        $manufacturer = ArrayHelper::map(Manufacturer::find()->asArray()->where(['status' => 1])->all(), 'id', 'name');
        $group = ArrayHelper::map(Group::find()->where(['status' => 1])->all(), 'id', 'name');

        $data['Language'] = [];
        foreach ($product->productLang as $v) {
            foreach ($v as $k1 => $v1) {
                $data['Language'][$v->lang->alias][$k1] = $v1;
            }
        }
        $product->languageData = $data;
        if (empty($product->alias)) {
            $product->alias = preg_replace("/[^a-z0-9-]/", '', str_replace(' ', '-', mb_strtolower(TransliteratorHelper::process($product->productLang[0]->name, '?', 'en'))));
            $product->languageData[0]['language'] = 'ru';
        }

        $searchModel = new VProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            $prod = Product::findOne($product->id);
            $data['Product']['gallery'] = $data['Product']['gallery_serialize'];
            $prod->attributes = $data['Product'];
            if ($prod->validate()) {
                if ($prod->save()) {
                    foreach ($data['Product']['Language'] as $key => $item) {
                        $lang = ProductLang::find()->where(['product_id' => $prod->id, 'lang_id' => $item['lang_id']])->one();
                        $lang->attributes = $item;
                        if ($lang->validate()) {
                            $lang->save();
                        }
                    }
                    SeoWidget::save($prod->id, 'product', $data['SEO']);
                }
            }
            Yii::$app->session->setFlash('success', 'Продукт успешно отредактирован');
            return $this->redirect([$data['save']]);
        }


        // Пересмотреть после оптимизации модуля Акции BEGIN
//        if (!empty($stock_data = StocksProducts::getProductStocks($id))) {
//            for ($i = 0; $i < count($stock_data); $i++) {
//                $vproduct_id = $stock_data[$i]['vproduct_id'];
//                if ($vproduct_id != 0) {
//                    $var = $vp[$vproduct_id]['char_value'];
//                } else {
//                    $var = null;
//                }
//                $sale = $stock_data[$i]['sale'];
//                $stock_id = $stock_data[$i]['stock_id'];
//                $stock = Stock::findOne($stock_id);
//                $sale_price = $model->price * (1 - $sale / 100);
//                $sp_id = $stock_data[$i]['id'];
//                $stock_data[$i] = ['stock_id' => $stock_id, 'sp_id' => $sp_id, 'var' => $var, 'title' => $stock->title, 'type' => $stock->type, 'sale' => $sale, 'sale_price' => $sale_price];
//            }
//            $stock_exist = true; 
//        } else {
//            $stock_data = null;
//            $stock_exist = false;
//        }
//        $stockDataProvider = new ArrayDataProvider([
//            'allModels' => $stock_data
//        ]);
//        $stockDataProvider->getModels();
        //Пересмотреть после оптимизации модуля Акции END

        $options = [
            'id' => $id,
            'model' => $model,
            'product' => $product,
            'characteristic_list' => [],
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'category' => $category,
            'provider' => $provider,
            'manufacturer' => $manufacturer,
            'group' => $group,
            'characteristic' => $characteristic,
            'product_characteristic' => $product_characteristic,
            // Пересмотреть
            //'stockDataProvider' => $stockDataProvider,
            //'stock_data' => $stock_data,
            //'stock_exist' => $stock_exist,
        ];
        return $this->render('form', $options);
    }

    public function actionUpdateStatus() {
        $data = Yii::$app->request->post();
        Product::updateAll(['publish' => $data['checked']], ['=', 'id', $data['id']]);
    }

    public function actionShowGalleryItem() {
        $id = Yii::$app->request->post('id');
        $media = Mediafile::find()->select(['url'])->asArray()->where(['id' => $id])->one();
        return Json::encode($media);
    }

    public function actionAjaxShowGalleryItem() {
        $item = [];
        $id = Yii::$app->request->post('id');
        $product = Product::find()->asArray()->select(['gallery'])->where(['id' => $id])->one();
        if (!empty($product['gallery'])) {
            $product['gallery'] = Json::decode($product['gallery']);
            $file = Mediafile::find()->select(['url', 'id'])->asArray()->where(['in', 'id', $product['gallery']])->all();
            $file = ArrayHelper::index($file, 'id');
            foreach ($product['gallery'] as $k => $v) {
                $item[$k] = [
                    'id' => $file[$v]['id'],
                    'url' => $file[$v]['url']
                ];
            }
        }
        return Json::encode($item);
    }

    public function actionAjaxAddManufacturer() {
        $data = Yii::$app->request->post();
        if (empty($data['name'])) {
            return Json::encode(['type' => 'error', 'msg' => 'Поле не может быть пустым']);
        }
        if (Manufacturer::find()->where(['name' => $data['name']])->one()) {
            return Json::encode(['type' => 'error', 'msg' => 'Производитель уже существует']);
        }
        $manufacturer = Manufacturer::find()->where(['id' => $data['id']])->one();
        if ($manufacturer === NULL) {
            $manufacturer = new Manufacturer();
        }
        $manufacturer->name = $data['name'];
        $manufacturer->status = 1;
        $manufacturer->slug = preg_replace("/[^a-z0-9-]/", '', str_replace(' ', '-', mb_strtolower(TransliteratorHelper::process($data['name'], '?', 'en'))));
        $manufacturer->save();
        return Json::encode(['type' => 'success', 'id' => $manufacturer->id]);
    }

    public function actionAjaxDeleteManufacturer() {
        $id = Yii::$app->request->post('id');
        $manufacturer = Manufacturer::findOne($id);
        $manufacturer->status = 0;
        $manufacturer->update();
    }

    public function actionAjaxAddProductGroup() {
        $data = Yii::$app->request->post();
        if (empty($data['group'])) {
            return Json::encode(['type' => 'error', 'name' => 'group', 'msg' => 'Поле не может быть пустым']);
        }
        if (Group::find()->where(['name' => $data['group']])->one()) {
            return Json::encode(['type' => 'error', 'name' => 'group', 'msg' => 'Группа уже существует']);
        }
        $product_group = Group::find()->where(['id' => $data['id']])->one();
        if ($product_group === NULL) {
            $product_group = new Group();
        }
        $product_group->name = $data['group'];
        $product_group->status = 1;
        $product_group->save();
        return Json::encode(['type' => 'success', 'id' => $product_group->id, 'value' => $product_group->name]);
    }

    public function actionAjaxDeleteProductGroup() {
        $id = Yii::$app->request->post('id');
        ProductCharacteristic::deleteAll(['group_id' => $id]);
        Characteristic::deleteAll(['group_id' => $id]);
        Group::findOne($id)->delete();
        return Json::encode(['product-group_id', 'characteristic-name']);
    }

    public function actionAjaxDeleteProductCharacteristic() {
        $id = Yii::$app->request->post('id');
        ProductCharacteristic::deleteAll(['characteristic_id' => $id]);
        Characteristic::findOne($id)->delete();
        return Json::encode(['characteristic-name']);
    }

    private function getProductCharacteristic($id) {
        $request = ProductCharacteristic::find()->select('product_characteristic.*, characteristic.*')
                ->leftJoin('characteristic', 'product_characteristic.characteristic_id = characteristic.id')
                ->where(['or', ['product_characteristic.product_id' => $id], ['product_import_id' => $id]])
                ->asArray()
                ->all();
        return $request;
    }

    public function actionAjaxGetProductCharacteristic() {
        $id = Yii::$app->request->post('id');
        $dataProvider = new ActiveDataProvider([
            'query' => ProductCharacteristic::find()->select(''
                            . '`product_characteristic`.`id` AS `id`, '
                            . '`group`.`name` AS `group_name`, '
                            . '`characteristic`.`name` AS `characteristic_name`,'
                            . '`product_characteristic`.`product_import_id` AS `product_import_id`,'
                            . '`product_characteristic`.`value` AS `product_characteristic_value`, '
                            . '`characteristic`.`type` AS `characteristic_type`')->asArray()
                    ->leftJoin('characteristic', 'product_characteristic.characteristic_id = characteristic.id')
                    ->leftJoin('group', 'product_characteristic.group_id = group.id')
                    ->where(['or', ['product_characteristic.product_id' => $id], ['product_import_id' => $id]])
                    ->orderBy(['product_characteristic.group_id' => 'ASC', 'characteristic.id' => 'ASC']),
            'pagination' => FALSE,
            'sort' => FALSE,
        ]);
        $render = $this->renderPartial('characteristic', [
            'dataProvider' => $dataProvider
        ]);
        return Json::encode($render);
    }

    public function actionAjaxGetProductCharacteristicList() {
        $id = Yii::$app->request->post('id');
        $characteristic_list = Characteristic::find()->asArray()->where(['status' => 1])->andWhere(['group_id' => $id])->all();
        $characteristic_list = ArrayHelper::index($characteristic_list, 'id');
        return Json::encode($characteristic_list);
    }

    public function actionAjaxAddProductCharacteristic() {
        $data = Yii::$app->request->post();
        if (empty($data['name'])) {
            return Json::encode(['type' => 'error', 'name' => 'name', 'msg' => 'Поле не может быть пустым']);
        }
        if (empty($data['type'])) {
            return Json::encode(['type' => 'error', 'name' => 'type', 'msg' => 'Тип не может быть пустым']);
        }
        if (Characteristic::find()->where(['name' => $data['name']])->one()) {
            return Json::encode(['type' => 'error', 'name' => 'name', 'msg' => 'Характеристика уже существует']);
        }
        $product_сharacteristic = Characteristic::find()->where(['id' => $data['id']])->one();
        if ($product_сharacteristic === NULL) {
            $product_сharacteristic = new Characteristic();
        }
        $product_сharacteristic->group_id = $data['group_id'];
        $product_сharacteristic->name = $data['name'];
        $product_сharacteristic->type = $data['type'];
        $product_сharacteristic->status = 1;
        $product_сharacteristic->save();
        return Json::encode(['type' => $product_сharacteristic->type, 'id' => $product_сharacteristic->id, 'value' => $product_сharacteristic->name]);
    }

    public function actionAjaxSaveProductCharacteristic() {
        $data = Yii::$app->request->post();
        $product_characteristic = new ProductCharacteristic();
        if ($data['edit'] != 0) {
            $product_characteristic = ProductCharacteristic::findOne($data['edit']);
        }
        $product_characteristic->attributes = $data;
        $product_characteristic->save();
    }

    private function updateCharacteristicsTable($product_id) {
        $dataProvider = new ArrayDataProvider([
            'allModels' => $order_products,
            'pagination' => [
                'pageSize' => 10,
            ]
        ]);
        $dataProvider->getModels();
    }

    public function actionAjaxDeleteCharacteristic() {
        if (Yii::$app->request->isAjax) {
            ProductCharacteristic::findOne(Yii::$app->request->post('id'))->delete();
        }
    }

    public function actionAjaxGetCharacteristicParams() {
        if (Yii::$app->request->isAjax) {
            $id = Yii::$app->request->post('id');
            if ($id) {
                $prod_char = ProductCharacteristic::findOne($id);
                $char = Characteristic::findOne($prod_char->characteristic_id);
                return Json::encode(['group' => $prod_char->group_id, 'characteristic' => $prod_char->characteristic_id, 'value' => $prod_char->value, 'type' => $char->type]);
            }
        }
        return false;
    }

    public function actionAjaxGetProductCharacteristicInput() {
        $characteristic_id = Yii::$app->request->post('characteristic_id');
        $characteristic = Characteristic::findOne($characteristic_id);
        $product_сharacteristic = new ProductCharacteristic();
        $render = $this->renderPartial('type', [
            'type' => $characteristic->type,
            'product_characteristic' => $product_сharacteristic
        ]);
        return Json::encode($render);
    }

    public function actionSetPriceVproduct() {
        $data = Yii::$app->request->post();
        if ($data['price'] < 0 || !is_numeric($data['price'])) {
            $data['price'] = 0;
        }
        $vproduct = VProduct::find()->where(['id' => $data['stock_id'], 'product_id' => $data['product_id']])->one();
        $vproduct->price = $data['price'];
        $vproduct->save();
        return number_format($vproduct->price, 2, '.', '');
    }

    public function actionShowGalleryTemplate() {
        if (Yii::$app->request->isAjax) {
            return $this->renderFile('@backend/modules/product/views/product/product-pictures.php');
        }
    }

    /* Проверено */

    public function actionAjaxGetMediaIdAll($id) {
        if (Yii::$app->request->isAjax) {
            $request = VProduct::find()->select('media_id')->where(['<>', 'media_id', $id])->asArray()->all();
            return Json::encode(array_column($request, 'media_id'));
        }
    }

    /* Проверено */

    public function actionAjaxSaveVProduct() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            VProduct::updateAll(['media_id' => $data['media_id']], ['=', 'id', $data['stock_id']]);
            $media = Mediafile::find()->select(['id', 'url'])->where(['id' => $data['media_id']])->one();
            return Json::encode($media);
        }
    }

    /* Проверено */

    public function actionAjaxUpdateVProductStatus() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            VProduct::updateAll(['publish' => $data['publish']], ['=', 'id', $data['stock_id']]);
        }
    }

    /*
     * Deprecated
     */

    static function getVProductList($render = FALSE) {
        // пингуем сервер не работоспасобность
        $ping = Curl::curl('GET', '/api/ping');
        if ($ping['status'] !== 200) {
            return die('Ошибка сервера: ' . $ping['status']);
        }

        // удаляем кеш если надо
        if ($render == TRUE) {
            if (Yii::$app->cache->exists('vproduct_list_key')) {
                foreach (Yii::$app->cache->get('vproduct_list_key') as $v) {
                    if (Yii::$app->cache->exists('vproduct_list_' . $v)) {
                        Yii::$app->cache->delete('vproduct_list_' . $v);
                    }
                }
                Yii::$app->cache->delete('vproduct_list_key');
            }
            if (Yii::$app->cache->exists('vproduct_list_status')) {
                Yii::$app->cache->delete('vproduct_list_status');
            }
        }
        if (!Yii::$app->cache->exists('vproduct_list_status')) {
            $products = Curl::curl('GET', '/api/getVProducts');

            if ($products['status'] !== 200) {
                return die('Ошибка сервера: ' . $products['status']);
            }
            self::setCacheVData($products['body']);
            Yii::$app->cache->set('vproduct_list', $products['body']);
        }
        return Yii::$app->cache->get('vproduct_list');
    }

    /*
     * Deprecated
     */

    private static function setCacheVData($products) {
        $data = [];
        foreach ($products as $k => $v) {
            foreach ($v as $k1 => $v1) {
                $data[$k][$v1['product_id']] = $v1;
            }
        }
        foreach ($data as $k => $v) {
            Yii::$app->cache->set('vproduct_list_' . $k, $v);
        }
        Yii::$app->cache->set('vproduct_list_key', array_keys($data));
        Yii::$app->cache->set('vproduct_list_status', TRUE);
    }

    /*
     * Deprecated
     */

    private static function assembleShopsProducts() {
        $shop_cache = [];
        $shops = ParseShop::find()->asArray()->all();
        $shops_id = array_column($shops, 'id');
        $products = Product::find()->asArray()->all();
        $products = ArrayHelper::index($products, 'import_id');
        /* Перебираем кеш каждого магазина */
        if (!empty($products)) {
            foreach ($shops_id as $id) {
                $shop_prods = Yii::$app->cache->get('products_shop_' . $id);
                if (!empty($shop_prods)) {
                    $shop_prods = array_intersect_key($shop_prods, $products);
                    $shop_cache += $shop_prods;
                }
            }
        }
        return $shop_cache;
    }

    /*
     * Deprecated
     */

    public static function attachFieldsDB(array $products) {
        $fields = Yii::$app->db->createCommand("
            SELECT CONCAT('`',table_name, '`.`', column_name, '` AS `', table_name, '_', column_name, '`') AS fields
            FROM `information_schema`.`columns` 
            WHERE `table_schema` = DATABASE() AND `table_name` in ('product', 'category', 'seo_meta', 'filemanager_mediafile', 'manufacturer')")->queryAll();
        $temp = [];
        foreach ($fields as $k => $v) {
            $temp[] = $v['fields'];
        }
        $sp_product = Product::find()->select(implode(', ', $temp) . ' ')->asArray()
                ->leftJoin('category', 'category.stock_id = product.category_id')
                ->leftJoin('seo_meta', 'seo_meta.page_id = product.seo_id')
                ->leftJoin('filemanager_mediafile', 'filemanager_mediafile.id = product.media_id')
                ->leftJoin('manufacturer', 'manufacturer.id = product.manufacturer_id')
                ->all();
        $language = ArrayHelper::map(LangWidget::getActiveLanguageData(['alias', 'lang']), 'alias', 'lang');
        foreach ($products as $k => $v) {
            foreach ($sp_product as $k1 => $v1) {
                if ($v['id'] == $v1['product_stock_id'] || $v['id'] == $v1['product_import_id']) {
                    foreach ($language as $k2 => $v2) {
                        if ($k2 == $v1['product_language']) {
                            $products[$k]['fields'][$k2] = $v1;
                        }
                    }
                }
            }
        }
        (!isset($products[$k]['fields'])) ? $products[$k]['fields'] = [] : FALSE;
        return $products;
    }

    /*
     * Deprecated
     */

    public static function attachFieldDB(array $product, $conditional = '') {
        $fields = Yii::$app->db->createCommand("
        SELECT CONCAT('`',table_name, '`.`', column_name, '` AS `', table_name, '_', column_name, '`') AS fields
            FROM `information_schema`.`columns` 
            WHERE `table_schema` = DATABASE() AND `table_name` in ('product', 'category', 'seo_meta', 'filemanager_mediafile', 'manufacturer')")->queryAll();
        foreach ($fields as $k => $v) {
            $temp[] = $v['fields'];
        }
        $sp_product = Product::find()->select(implode(', ', $temp) . ' ')->asArray()
                ->leftJoin('category', 'category.stock_id = product.category_id')
                ->leftJoin('seo_meta', 'seo_meta.page_id = product.seo_id')
                ->leftJoin('filemanager_mediafile', 'filemanager_mediafile.id = product.media_id')
                ->leftJoin('manufacturer', 'manufacturer.id = product.manufacturer_id')
                ->where($conditional)
                ->one();
        $language = ArrayHelper::map(LangWidget::getActiveLanguageData(['alias', 'lang']), 'alias', 'lang');
        foreach ($language as $k2 => $v2) {
            if ($k2 == $sp_product['product_language']) {
                $product['fields'][$k2] = $sp_product;
            }
        }
        return $product;
    }

    /*
     * Deprecated
     */

    static function updateProductsCache() {
        /* собираем весь кеш продуктов */
        $product_cache_baza = self::getBazaCache();
        $product_cache_import = self::assembleShopsProducts();
        $products_cache = $product_cache_baza + $product_cache_import;
        if (empty($products_cache))
            return false;
        $products_cache = self::attachFieldsDB($products_cache); //прикрепляем данные с таблицы (исп. всего 1 запрос)
        $product_cache_import = [];
        $product_cache_baza = [];
        $is_updated = true;

        /* переразбитие продуктов по своим кешам */
        foreach ($products_cache as $prod_cache) {
            if ($prod_cache['type'] != 'baza') {
                $product_cache_import[$prod_cache['shop_id']][$prod_cache['id']] = $prod_cache;
            } else {
                $product_cache_baza[] = $prod_cache;
            }
        }
        /* перезапись в кеши */
        foreach ($product_cache_import as $shop_id => $prods_import) {
            $is_updated = $is_updated && Yii::$app->cache->set('products_shop_' . $shop_id, $prods_import);
        }
        $is_updated = $is_updated && Yii::$app->cache->set('product_baza', $product_cache_baza);
        return $is_updated;
    }

    /*
     * Deprecated
     */

    static function getProductList($render = false, $category_id = null) {
        /* Сборка продуктов с базы и импорта */
        $product_cache_baza = [];
        if (!Yii::$app->cache->exists('product_list_baza') || empty(Yii::$app->cache->get('product_list_baza')) || $render) {
            $product_cache_baza = self::getBazaCache();
            Yii::$app->cache->set('product_list_baza', $product_cache_baza);
        } else {
            $product_cache_baza = Yii::$app->cache->get('product_list_baza');
        }
        $product_cache_import = self::assembleShopsProducts();
        $products = $product_cache_baza + $product_cache_import;

        /* Сводим все ключи категории в один */
        foreach ($products as $k => $v) {
            if (isset($v['category_id'])) {
                unset($products[$k]['category_id']);
                $id_category = $v['category_id'];
                unset($v['category_id']);
            } else {
                $id_category = $v['id_category'];
            }
            $products[$k]['id_category'] = $id_category;
        }
        if ($render) {
            /* Пикрепление данных с таблицы */
            $products = self::attachFieldsDB($products);
        }

        /* Разбиение по категориям, если надо */
        if (!is_null($category_id)) {
            $products = self::splitByCategories($products);
            $products = (isset($products[$category_id])) ? $products[$category_id] : [];
        }
        return ArrayHelper::index($products, 'id');
    }

    /*
     * Deprecated
     */

    private static function getBazaCache() {
        // пингуем сервер не работоспособность
        $ping = Curl::curl('GET', '/api/ping');
        if ($ping['status'] !== 200) {
            return [];
        }
        //Получаем продукты
        $data = Curl::curl('GET', '/api/getProducts');
        if ($data['status'] !== 200) {
            return [];
        }
        $products = $data['body'];
        $prods = [];
        foreach ($products as $v) {
            $prods[$v['id']] = $v;
            $prods[$v['id']]['type'] = 'baza';
            $prods[$v['id']]['price_old'] = $v['trade_price'];
        }
        return $prods;
    }

    /*
     * Deprecated
     */

    private static function splitByCategories(array $products) {
        $data = [];
        foreach ($products as $v) {
            if (array_key_exists('category_id', $v)) {
                $data[$v['category_id']][$v['id']] = $v;
            } else {
                $data[$v['id_category']][$v['id']] = $v;
            }
        }
        return $data;
    }

    /*
     * Deprecated
     */

    public static function issetDefault(array $product) {
        $def_fields_value = Yii::$app->params['import_settings']['def_fields_value'];
        $message = '';
        switch ($product) {
            case ($product['id_category'] == $def_fields_value['category_id']):
                $message = 'Не установлена категория';
                break;
            case ($product['product_name'] == $def_fields_value['product_name'] ):
                $message = 'Не установлено имя продукта';
                break;
            case ($product['trade_price'] == $def_fields_value['price']):
                $message = 'Установлена нулевая цена';
                break;
            case ($product['fields']['ru']['product_description'] == $def_fields_value['description']):
                $message = 'Не установлено описание товара';
                break;
            case ($product['fields']['ru']['manufacturer_name'] == $def_fields_value['manufacturer']):
                $message = 'Не установлен производитель товара';
                break;
        }
        if (!empty($message)) {
            return $message;
        }
        return false;
    }

}
