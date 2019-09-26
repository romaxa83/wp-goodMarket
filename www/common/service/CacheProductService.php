<?php

namespace common\service;

use function GuzzleHttp\default_ca_bundle;
use Yii;
use Exception;
use yii\helpers\ArrayHelper;
use common\helpers\ProductsHelper;
use backend\modules\order\models\Order;
use backend\modules\product\models\Product;
use Behat\Gherkin\Exception\CacheException;
use backend\modules\product\models\VProduct;
use backend\modules\category\models\Category;
use backend\modules\order\models\OrderProduct;
use backend\modules\filemanager\models\Mediafile;
use backend\modules\stock\models\StocksProducts;
use backend\modules\product\controllers\ProductController;

class CacheProductService {
    /*
     * Метод принимает массив записей (список желания) по определеному пользователю
     * и возвращает этот массив со всеми данными продуктов
     */

    public function getProductDataForWish($wishes) {
        if(!empty($wishes)){
            foreach ($wishes as $i => $one) {
                $arr_id = ProductsHelper::productsIdUnser($one['products_id']);
                $products = $this->getProducts($arr_id);
                $wishes[$i]['product_data'] = $products;
                $count = 0;
                foreach ($products as $prod) {
                    $category = Category::find()->where(['stock_id' => $prod['category_id']])->asArray()->one();
                    $wishes[$i]['product_data'][$count]['category_alias'] = $category['alias'];
                    $wishes[$i]['product_data'][$count]['category_name'] = $this->getCategoryName($category['stock_id']);
                    if ($prod['stock_id'] != null) $wishes[$i]['product_data'][$count]['product_name'] = $this->getProductName($prod['stock_id'], $prod['category_id']);
                    if ($prod['stock_id'] == null) $wishes[$i]['product_data'][$count]['product_name'] = $this->getProductName($prod['import_id'], $prod['category_id']);
                    $wishes[$i]['product_data'][$count]['media'] = $this->getProductMedia($prod['media_id']);

                    if ($this->isProductSale($prod['stock_id'])) {
                        $wishes[$i]['product_data'][$count]['sale'] = $this->getProductSale($prod['stock_id']);
                    }
                    $count++;
                }
            }
        }
        return $wishes ?? [];
    }

    /*
     * Метод принимает массив записей (список желания) по определеному пользователю
     * и возвращает этот массив с подмассивом - product_name,где ключ id - продукта,а значение его название (взятое из кеша)
     */

    public function getProductNameForWish($wishes) {
//        $this->isProductCache();
        foreach ($wishes as $i => $one) {
            $arr_id = ProductsHelper::productsIdUnser($one['products_id']);
            $products = $this->getProducts($arr_id);
            $count = 0;
            foreach ($products as $prod) {
//                $wishes[$i]['product_name'][$arr_id[$count]] = $this->getProductName($prod['stock_id'], $prod['category_id']);
                if ($prod['stock_id'] != null) $wishes[$i]['product_name'][$arr_id[$count]] = $this->getProductName($prod['stock_id'], $prod['category_id']);
                if ($prod['stock_id'] == null) $wishes[$i]['product_name'][$arr_id[$count]] = $this->getProductName($prod['import_id'], $prod['category_id']);
                $count++;
            }
        }
        return $wishes;
    }

    /*
     * Метод принимает массив записей (избраное) по определеному пользователю
     * и возвращает этот массив со всеми данными продуктов
     */

    public function getProductDataForFavorites($favorites,$limit= null,$offset = null) {
        $arrayFavoriteId = ProductsHelper::productsIdUnser($favorites['products_id']);
        $arr_id = array_slice(empty($arrayFavoriteId) ? [] : $arrayFavoriteId,($offset < 0) ? 0 : $offset,$limit);
        $products = $this->getProducts($arr_id);
        $favorites['product_data'] = $products;
        $count = 0;
        foreach ($products as $prod) {
            $category = Category::find()->where(['stock_id' => $prod['category_id']])->asArray()->one();
            $favorites['product_data'][$count]['category_alias'] = $category['alias'];
            $favorites['product_data'][$count]['category_name'] = $this->getCategoryName($category['stock_id']);
            if ($prod['stock_id'] != null) $favorites['product_data'][$count]['product_name'] = $this->getProductName($prod['stock_id'], $prod['category_id']);
            if ($prod['stock_id'] == null) $favorites['product_data'][$count]['product_name'] = $this->getProductName($prod['import_id'], $prod['category_id']);
            $favorites['product_data'][$count]['media'] = $this->getProductMedia($prod['media_id']);

            if ($this->isProductSale($prod['stock_id'])) {
                $favorites['product_data'][$count]['sale'] = $this->getProductSale($prod['stock_id']);
            }
            $count++;
        }
        return $favorites;
    }

    /*
     * Метод принимает массив записей (избраное) по определеному пользователю
     * и возвращает этот массив с подмассивом - product_name,где ключ id - продукта,а значение его название (взятое из кеша)
     */

    public function getProductNameForFavorites($favorites) {
//        $this->isProductCache();
        $arr_id = ProductsHelper::productsIdUnser($favorites['products_id']);
        $products = $this->getProducts($arr_id);
        $count = 0;
        foreach ($products as $prod) {
            $favorites['product_name'][$arr_id[$count]] = $this->getProductName($prod['stock_id'], $prod['category_id']);
            $count++;
        }
        return $favorites;
    }

    public function getProductsForSearch($product_name, $favorites, $sort_product, $limit, $offset) {
        $product_cache = ProductController::getProductList();
        $filter_product = array_filter($product_cache, function($element) use ($product_name){
            if(isset($element['fields']) && $element['publish_status'] == 1 && mb_stristr($element['product_name'],$product_name)){
                return $element;
            }
        });
        $products = ArrayHelper::index(array_slice($filter_product, $offset * $limit, $limit),'id');
        if ($favorites) {
            $arr_fav = ProductsHelper::productsIdUnser($favorites);
        }
        if (!empty($products)) {
            if ($sort_product == 'price-up') {
                usort($products, function ($a, $b) {
                    return ((int) $a['fields']['ru']['product_price'] - (int) $b['fields']['ru']['product_price']);
                });
            } elseif ($sort_product == 'price-down') {
                usort($products, function ($a, $b) {
                    return -((int) $a['fields']['ru']['product_price'] - (int) $b['fields']['ru']['product_price']);
                });
            } else {
                usort($products, function ($a, $b) {
                    return -((int) $a['fields']['ru']['product_rating'] - (int) $b['fields']['ru']['product_rating']);
                });
            }
            $products_id = array_column($products, 'id');
            $product_sale = ArrayHelper::index(StocksProducts::find()->where(['in', 'product_id', $products_id])->asArray()->all(), 'product_id');
            if (!empty($product_sale)) {
                foreach ($products as $id => $one) {
                    if (array_key_exists($one['id'], $product_sale)) {
                        $products[$id]['product_sale'] = $product_sale[$one['id']];
                    }
                    if ($favorites) {
                        if (in_array($one['fields']['ru']['product_id'], $arr_fav)) {
                            $products[$id]['is_favorites'] = 1;
                        }
                    }
                }
            }
            $products['all_count'] = count($filter_product);
        }
        return $products;
    }

    /**
     * Метод возвращает товары по конкретной категории (используеться для фильтров),
     * @var string $alias_category
     * @var null|string $favorites
     * @var null|string $limit
     * @var null|string $offset
     * @var null|string $sort
     * @var null|string $range_price
     * @var null|array $manufacturer_check
     * @var null|string $color_check
     *
     * @return null|array
     *
     */

    public function getProductDataForCategory(
        $alias_category,
        $favorites = null,
        $limit = null,
        $offset = null,
        $sort = null,
        $range_price = null,
        $manufacturer_check = null,
        $color_check = null
    ) {
        if(in_array('no products',$this->getImportIds($manufacturer_check))){
            return [0 => ['all_count' => '0']];
        }

        $category = Category::find()->where(['alias' => $alias_category])->one();
        $products_cache = ProductController::getProductList(false, $category->stock_id);
        $prod_cache_keys = array_keys($products_cache); 
        $category_name = $this->getCategoryName($category->stock_id);
        $products = Product::find()
            ->where(['category_id' => $category->stock_id])
            ->andFilterWhere($this->getIdIsColor($color_check, $category->stock_id))
            ->andFilterWhere($this->getManufacturer($manufacturer_check))
            ->andFilterWhere($this->getRangePrice($range_price))
            ->andFilterWhere($this->getImportIds($manufacturer_check))
            ->andWhere(['publish' => 1])
            ->andWhere(['or', ['in', 'stock_id', $prod_cache_keys], ['in', 'import_id', $prod_cache_keys]])
            ->limit($limit)
            ->offset($offset)
            ->orderBy($this->getOrderBy($sort))
            ->asArray()
            ->all();
        $all_count = Product::find()
            ->where(['category_id' => $category->stock_id])
            ->andFilterWhere($this->getIdIsColor($color_check, $category->stock_id))
            ->andFilterWhere($this->getManufacturer($manufacturer_check))
            ->andFilterWhere($this->getRangePrice($range_price))
            ->andFilterWhere($this->getImportIds($manufacturer_check))
            ->andWhere(['publish' => 1])
            ->andWhere(['or', ['in', 'stock_id', $prod_cache_keys], ['in', 'import_id', $prod_cache_keys]])
            ->orderBy($this->getOrderBy($sort))
            ->asArray()
            ->count();
        if ($favorites) {
            $arr_fav = ProductsHelper::productsIdUnser($favorites);
        }
        foreach ($products as $i => $product) {
            $primary_id = ($product['stock_id']==null)?$product['import_id']:$product['stock_id'];
            if(!in_array($primary_id, $prod_cache_keys)){continue;}
            $products[$i]['product_name'] = $this->getProductName($primary_id, $category->stock_id);
            $products[$i]['category_name'] = $category_name;
            $products[$i]['category_alias'] = $alias_category;
            $products[$i]['product_vendor_code'] = $this->getVendorCode($primary_id, $category->stock_id);
            $products[$i]['media'] = $this->getProductMedia($product['media_id']);
            $product['price_old'] = $this->getOldPrice($primary_id, $category->stock_id);
            // if ($this->isProductSale($product['stock_id'])) {
            //     $product['sale'] = $this->getProductSale($product['stock_id']);
            // }
            $products[$i]['sale'] = getSale($product['price'], $product['price_old']);
            if ($favorites) {
                if (in_array($product['id'], $arr_fav)) {
                    $products[$i]['is_favorites'] = 1;
                }
            }
        }
        $products_clear = array_values($products);
        if(!empty($products_clear)){
            $products_clear[0]['all_count'] = $all_count;
        }
        return $products_clear;
    }

    /**
     * @var array $manufacturer_check
     * @return array|null
     */
    private function getImportIds($manufacturer_check)
    {
        if($manufacturer_check){
            $middle_arr = [];
            foreach ($manufacturer_check as $key => $item) {
                if($key !== 'manufacture'){
                    $middle_arr[] = $item;
                }
            }
            $result = [];
            array_walk_recursive($middle_arr,function($one) use (&$result){
                $result[] = $one;
            });

            if($result){
                return ['in','import_id',array_unique($result)];
            } elseif (!($result) && $this->isKeyInDataForFilter($manufacturer_check,'manufacture')){
                return [null];
            } else {
                return ['no products'];
            }
        }
        return [null];
    }

    //метод для фильтрации по цвету
    private function getIdIsColor($color_check, $category_stock_id) {
        if ($color_check) {
            $vproducts = Product::find()->where(['category_id' => $category_stock_id])
                    ->joinWith('vproducts')
                    ->andFilterWhere($this->getVproductQuery($color_check))
                    ->asArray()
                    ->all();
            return ['in', 'id', array_column($vproducts, 'id')];
        }
        return [null];
    }

    private function getVproductQuery($color_check) {
        $arr = [];
        $arr[0] = 'or';
        foreach ($color_check as $key => $one) {
            $arr[$key + 1][0] = 'like';
            $arr[$key + 1][1] = 'char_value';
            $arr[$key + 1][2] = $one;
        }
        return $arr;
    }

    //метод для сортировки
    private function getOrderBy($sort_alias) {
        switch ($sort_alias) {
            case 'price-up':
                return 'price ASC';
            case 'price-down':
                return 'price DESC';
            case 'rating':
                return 'rating DESC';
            default:
                return null;
        }
    }

    //метод для фильтрации по цене
    private function getRangePrice($range_price) {
        if ($range_price !== null && $range_price !== '') {
            $range = explode('|', $range_price);
            return ['between', 'price', $range['0'], $range['1']];
        }
        return [null];
    }

    /**
     * метод для фильтрации по производителю
     * @var array|null $manufacturer_check
     * @return array
     */
    private function getManufacturer($manufacturer_check) {
        if ($this->isKeyInDataForFilter($manufacturer_check,'manufacture')) {
            return ['in', 'manufacturer_id', $manufacturer_check['manufacture']];
        }
        return [null];
    }

    /*
     * Метод принимает алиас товара и возвращает данные этого товара,
     */

    public function getProductData($alias, $favorites = null) {
        $this->isCategoryCache();
        $product = Product::find()->with('manufacturer')->where(['alias' => $alias])->asArray()->one();
        $primary_id = ($product['stock_id']==null)?$product['import_id']:$product['stock_id'];
        $category = Category::find()->where(['stock_id' => $product['category_id']])->asArray()->one();
        $product['category_alias'] = $category['alias'];
        $product['category_name'] = $this->getCategoryName($category['stock_id']);
        $product['product_name'] = $this->getProductName($primary_id, $category['stock_id']);
        $product['media'] = $this->getProductMedia($product['media_id']);
        $product['vproducts'] = $this->getVProduct($product['stock_id'], $category['stock_id']);
        $product['gallery'] = $this->getProductGallery($product['media_id'], $product['gallery']);
        $product['code'] = $this->getVendorCode($primary_id, $category['stock_id']);
        $product['price_old'] = $this->getOldPrice($primary_id, $category['stock_id']);
        $product['amount'] = $this->getProductParam($primary_id, $category['stock_id'], 'amount');
        $product['min_amount'] = $this->getProductParam($primary_id, $category['stock_id'], 'min_amount');
        $product['sale'] = getSale($product['price'], $product['price_old']);
        if ($favorites) {
            if (in_array($product['id'], ProductsHelper::productsIdUnser($favorites))) {
                $product['is_favorites'] = 1;
            }
        }
        return $product;
    }

    public function getProductDataID($product_id, $favorites = null, $stock_id = null) {
        $product = Product::find()->where(['id' => $product_id])->asArray()->one();
        if ($stock_id) {
            $product = Product::find()->where(['or',['stock_id' => $product_id],['import_id' => $product_id]])->asArray()->one();
        }
        $category = Category::find()->where(['stock_id' => $product['category_id']])->asArray()->one();
        $primary_id = ($product['stock_id']) ? $product['stock_id'] : $product['import_id'];
        $product['product_name'] = $this->getProductName($primary_id, $category['stock_id']);
        if ($this->isProductSale($product['stock_id'])) {
            $product['sale'] = $this->getProductSale($product['stock_id']);
        }
        return $product;
    }

    /*
     * Метод возвращает n-кол-во товар для рубрики рекомендовано
     * принимает id-категории,и кол-во возвращаемых записей,и id-продукта,
     * который нужно исключить из выборки
     */

    public function getRecommendedProducts($category_id, $limit, $product_id, $favorites = null) {
        $product_list = ProductController::getProductList(false, $category_id);
        $prod_cache_keys = array_keys($product_list);
        $category = Category::find()->where(['stock_id' => $category_id])->asArray()->one();
        $products = Product::find()
                        ->where(['not in', 'id', $product_id])
                        ->andWhere(['category_id' => $category_id, 'publish'=>1])
                        ->andWhere(['or', ['in', 'stock_id', $prod_cache_keys], ['in', 'import_id', $prod_cache_keys]])
                        ->orderBy('RAND()')
                        ->limit($limit)->asArray()->all();
        foreach ($products as $i => $product) {
            $primary_id = ($product['stock_id']==null)?$product['import_id']:$product['stock_id'];
            $products[$i]['category_alias'] = $category['alias'];
            $products[$i]['category_name'] = $this->getCategoryName($category['stock_id']);
            $products[$i]['product_name'] = $this->getProductName($primary_id, $category['stock_id']);
            $products[$i]['media'] = $this->getProductMedia($product['media_id']);

            if ($favorites) {
                if (in_array($product['id'], ProductsHelper::productsIdUnser($favorites))) {
                    $products[$i]['is_favorites'] = 1;
                }
            }
        }

        return $products;
    }

    /*
     * Метод принимает алиас категории и возвращает данные этой категории
     */

    public function getCategoryData($alias) {
        $this->isCategoryCache();
        $category_cache = \Yii::$app->cache->get('category_list');
        $category = Category::find()->where(['alias' => $alias])->asArray()->one();
        $category['category_name'] = $category_cache[$category['stock_id']]['name'] ?? 'Не определено';
        $category = $this->selectActiveEntities($category_cache, new Category())[$category['stock_id']];
        return $category;
    }

    /*
     * Метод возвращает данный товаров и заказов по определеному пользователю
     * принимает id-пользователя,так же можно указать limit и offset для постраничного вывода
     */

    public function getDataForOrder($user_id, $limit = null, $offset = null) {
        $orders = Order::find()->where(['user_id' => $user_id])->orderBy(['id' => SORT_DESC])->limit($limit)->offset($offset)->asArray()->all();
        foreach ($orders as $i => $order) {
            $order_products = OrderProduct::find()->where(['order_id' => $order['id']])->asArray()->all();
            $orders[$i]['order_products'] = $order_products;
            $orders[$i]['cost'] = 0;
            foreach ($order_products as $j => $one) {
                $product_data = $this->getProductDataID($one['product_id'], null, true);
                if ($one['vproduct_id'] != null) {
                    $orders[$i]['order_products'][$j]['vproduct_data'] = $this->getVProduct($one['product_id'], $product_data['category_id'], true)[$one['vproduct_id']];
                }
                $orders[$i]['order_products'][$j]['product_data'] = $product_data;
                $cost = ProductsHelper::getAllPrice($one['price'], $one['count']);
                $orders[$i]['order_products'][$j]['cost'] = $cost;
                $orders[$i]['cost'] += $cost;
            }
        }

        return $orders;
    }

    private function getProducts($arr_id) {
        return Product::find()->where(['in', 'id', $arr_id])->asArray()->all();
    }

    private function isProductCache($category_id) {
        if (!\Yii::$app->cache->exists('product_list_' . $category_id)) {
            return false;
        }
        return true;
    }

    private function isCategoryCache() {
        if (empty(ProductController::getProductList())) {
            throw new CacheException('В кеше отсутствуют данные товаров');
        }
    }

    private function isVProductCache() {
        if (!\Yii::$app->cache->exists('vproduct_list')) {
            throw new CacheException('В кеше отсутствуют данные вариативных товаров');
        }
    }

    private function isProductSale($stock_id, $vproduct_id = null) {
        if (StocksProducts::find()->where(['product_id' => $stock_id])->andWhere(['status' => 1])->exists()) {
            return true;
        }
        return false;
    }

    public function getProdName($stock_id, $category_id) {
        return $this->getProductName($stock_id, $category_id);
    }

    private function getProductName($stock_id, $category_id)
    {
        return $this->getProductParam($stock_id, $category_id, 'product_name');
    }

    private function getOldPrice($stock_id, $category_id){
        return $this->getProductParam($stock_id, $category_id, 'price_old');
    }

    private function getCode($stock_id, $category_id) {
        return $this->getProductParam($stock_id, $category_id, 'vendor_code');
    }
    
    private function getVendorCode($stock_id, $category_id) {
        return $this->getProductParam($stock_id, $category_id, 'vendor_code');
    }

    private function getVProduct($product_id, $category_id, $not_publish = null) {
        if (isset(\Yii::$app->cache->get('vproduct_list_' . $category_id)[$product_id])) {
            $publish = ['publish' => 1];
            if ($not_publish) {
                $publish = [null];
            }
            $vproduct = ArrayHelper::index(VProduct::find()->where(['product_id' => $product_id])->andFilterWhere($publish)->asArray()->all(), 'stock_id');
            $vproduct_cache = ArrayHelper::index(\Yii::$app->cache->get('vproduct_list_' . $category_id)[$product_id]['items'], 'id');

            return array_intersect_key($vproduct_cache, $vproduct);
        }
        return false;
    }

    private function getCategoryName($stock_id) {
        if ($stock_id == null) {
            throw new Exception('Некорректный параметр');
        }
        return \Yii::$app->cache->get('category_list')[$stock_id]['name'];
    }

    private function getProductMedia($media_id) {
        return Mediafile::find()->where(['id' => $media_id])->asArray()->one();
    }

    private function getProductSale($stock_id) {
        return StocksProducts::find()->where(['product_id' => $stock_id])
                        ->andWhere(['status' => 1])
                        ->orderBy(['sale' => SORT_DESC])->limit(1)->asArray()->one();
    }

    private function getProductSaleOrder($stock_id, $vproduct_id) {
        return StocksProducts::find()
                        ->where(['product_id' => $stock_id])
                        ->where(['vproduct_id' => $vproduct_id])
                        ->andWhere(['status' => 1])
                        ->asArray()->one()['sale'];
    }

    private function filterAmount($arr = []) {

        foreach ($arr as $k => $v) {
            if (!($arr[$k]['amount'] > 0)) {
                unset($arr[$k]);
            }
        }
        return $arr;
    }

    private function selectActiveEntities($entity_cache, $model, $condition = [])
    {
        $products = ArrayHelper::index($model->className()::find()->where(['publish' => 1])->andWhere($condition)->asArray()->all(),'stock_id');
        $entity_cache = ArrayHelper::index($entity_cache, 'id');
        return array_intersect_key($entity_cache,$products);
    }

    public function getStockId($model, $id) {
        return $model->className()::find()->select('stock_id')->where(['id' => $id])->asArray()->one()['stock_id'];
    }

    public function getSaleProductsByStock($stock_id) {
        $products = [];
        $categories_ids = [];
        $product_list = [];
        $prod_vprod_ids = StocksProducts::getStockProductsID($stock_id);
        $prod_ids = array_values(array_unique(array_column($prod_vprod_ids, 'product_id')));
        for ($i = 0; $i < count($prod_ids); $i++) {
            array_push($categories_ids, $this->getCategoryID($prod_ids[$i]));
        }
        $categories_ids = array_unique($categories_ids);
        for ($i = 0; $i < count($categories_ids); $i++) {
            $product_list = array_merge($product_list, ProductController::getProductList(false, $categories_ids[$i]));
        }
        $product_list = ArrayHelper::index($product_list, 'id');
        $product_list = $this->selectActiveEntities($product_list, new Product());
        for ($i = 0; $i < count($prod_vprod_ids); $i++) {
            $product_id = $prod_vprod_ids[$i]['product_id'];
            $vproduct_id = $prod_vprod_ids[$i]['vproduct_id'];
            if ($vproduct_id != 0) {
                $char_value = $this->getVProduct($product_id, $this->getCategoryID($product_id))[$vproduct_id]['char_value'];
                if (!array_key_exists('variations', $product_list[$product_id])) {
                    $product_list[$product_id]['variations'] = '';
                }
                $product_list[$product_id]['variations'] .= $char_value . ' ,';
            } else {
                $product_list[$product_id]['variations'] = '';
            }
        }
        for ($i = 0; $i < count($prod_ids); $i++) {
            $vars = substr($product_list[$prod_ids[$i]]['variations'], 0, -1);
            if (!empty($vars)) {
                $product_list[$prod_ids[$i]]['variations'] = '(' . $vars . ')';
            }
            array_push($products, $product_list[$prod_ids[$i]]);
        }
        return $products;
    }

    private function getVProductsByProduct($category_stock_id, $product_stock_id) {
        $this->isVProductCache();
        $v_product_list = Yii::$app->cache->get('vproduct_list_' . $category_stock_id);
        if (!array_key_exists($product_stock_id, $v_product_list)) {
            return [];
        }
        $v_product_list = $v_product_list[$product_stock_id];
        $condition = ['product_id' => $product_stock_id];
        $v_product_list = $this->selectStockActiveEntities($v_product_list['items'], new VProduct(), $condition);
        return $v_product_list;
    }

    private function getProductsByCategory($id) {
        $this->isProductCache($id);
        $product_list = ProductController::getProductList(false, $id);
        $product_list = $this->selectStockActiveEntities($product_list, new Product(), ['category_id' => $id]);
        return $product_list;
    }

    private function getProduct($product_id, $category_id) {
        $product_list = $this->getProductsByCategory($category_id);
        if (!array_key_exists($product_id, $product_list)) {
            return [];
        }
        return $product_list[$product_id];
    }

    private function getProductParam($stock_id, $category_id, $param){
        $product_list = ProductController::getProductList(false, $category_id);
        if (($stock_id === null) || !isset($product_list[$stock_id]) || ($category_id === null)) {
            return false;
        }
        return isset($product_list[$stock_id][$param]) ? $product_list[$stock_id][$param] : null;
    }

    private function getCategories() {
        $this->isCategoryCache();
        $category_list = Yii::$app->cache->get('category_list');
        $category_list = $this->selectStockActiveEntities($category_list, new Category());
        return $category_list;
    }

    private function getProductDetailData($category_id, $product_id, $vproduct_id, $count, $price, $product_price) {
        $poduct = [];
        $vproduct = [];
        if ($product_id != 0) {
            $product = $this->getProduct($product_id, $category_id);
            if (!empty($product)) {
                if ($vproduct_id != 0) {
                    $vproduct = $this->getVProductsByProduct($category_id, $product_id)[$vproduct_id];
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

    public function getOrderCost($id = 0, $products = []) {
        if ($id != 0) {
            $products = OrderProduct::getDataByOrderID($id);
        } else {
            if (empty($products)) {
                return false;
            }
        }
        for ($i = 0, $order_summ = 0; $i < count($products); $i++) {
            $count = $products[$i]['count'];
            $order_summ += $count * $products[$i]['price'];
        }
        return $order_summ;
    }

    private function selectStockActiveEntities($entity_list, $model, $condition = []) {
        $entities = [];
        $entity_data = $model->className()::find()->select('stock_id')->where(['publish' => 1])->andWhere($condition)->asArray()->all();
        $entity_data = ArrayHelper::index($entity_data, 'stock_id');
        $entity_list = ArrayHelper::index($entity_list, 'id');
        return array_intersect_key($entity_list, $entity_data);
    }

    private function getCategoryID($product_id) {
        return Product::find()->select('category_id')->where(['stock_id' => $product_id])->one()->category_id;
    }

    private function getProductGallery($id, $gallery) {
        $data = [];
        $gallery = \yii\helpers\Json::decode($gallery);
        if ($gallery !== NULL) {
            array_unshift($gallery, $id);
        } else {
            $gallery[] = $id;
        }
        $media = Mediafile::find()->select(['id', 'url', 'alt', 'description'])->asArray()->where(['in', 'id', $gallery])->all();
        $media = ArrayHelper::index($media, 'id');
        foreach ($gallery as $k => $v) {
            if (isset($media[$v])) {
                $data[$k] = $media[$v];
            }
        }
        return $data;
    }

    /**
     * проверяет данные (по фильтру),для коррекного построения запроса
     * @var null|array $data
     * @var string $key
     * @return bool
     */
    private function isKeyInDataForFilter($data,$key)
    {
        return !(empty($data)) && isset($data[$key]) && $data[$key] !== null;
    }

}
