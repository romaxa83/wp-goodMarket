<?php

namespace console\controllers;

use yii\helpers\Inflector as SlugGenerator;
use yii\helpers\Console;
use yii\console\Controller;
use Yii;
use yii\helpers\ArrayHelper;
use backend\modules\seo\models\SeoMeta;
use backend\modules\product\controllers\ProductController;
use backend\modules\product\models\Product;
use backend\modules\import\models\ParseShop;
use backend\modules\import\models\ShopGroup;
use backend\modules\filemanager\models\Mediafile;
use backend\modules\product\models\Characteristic;
use backend\modules\product\models\ProductCharacteristic;
use backend\modules\category\models\Category;
use backend\modules\product\models\Manufacturer;
use backend\modules\import\service\ImportService;
use backend\widgets\SeoWidget;
use backend\modules\import\service\FilterAttr;
use backend\modules\product\models\ProductLang;
use backend\widgets\langwidget\LangWidget;

class SaveImportProductsController extends Controller {

    //массив хранить имя_поля => тип_поля
    const FIELD_TYPES = [
        'id' => 'key',
        'category_id' => 'integer',
        'vendor_code' => 'key',
        'img' => 'url',
        'available' => 'boolean',
        'product_name' => 'string_not_empty',
        'price' => 'price',
        'price_old' => 'price',
        'amount' => 'integer',
        'seo_header' => 'string',
        'seo_name' => 'string',
        'seo_keyword' => 'string',
        'seo_description' => 'string',
        'seo_text' => 'string',
        'manufacturer' => 'string_not_empty',
        'available' => 'boolean',
        'unit' => 'string'
    ];
    //кличи для сео 
    const SEO_KEYS = ['seo_header', 'seo_name', 'seo_keyword', 'seo_description', 'seo_text'];
    //массив хранить имя_сущности|атрибут => ключь который пришел из иморта
    const FIELDS_DB = [
        'manufacturer|name' => 'manufacturer',
        'seo|header' => 'seo_header',
        'seo|name' => 'seo_name',
        'seo|keywords' => 'seo_keywords',
        'seo|description' => 'seo_description',
        'seo|text' => 'seo_text',
        'product|description' => 'description',
        'product|price' => 'price',
        'product|vendor_code' => 'vendor_code',
        'product|import_id' => 'id',
        'product|publish' => 'available',
        'product|alias' => 'product_name',
        'product|category_id' => 'category_id'
    ];

    public $importService;
    //свойство с обьектом для изменения привязки категория->характеристика
    public $filter;
    public $filemanager_module;

    //public $category_cache;

    public function init() {
        parent::init();
        $this->importService = new ImportService();
        $this->filter = new FilterAttr();
        $this->filemanager_module = Yii::$app->getModule('filemanager');
    }

    /**
     * получения индефикатора магазина
     * @var string $action действие (update - обновления,edit - обновления после редактирования,create - сохранения импортных товаров)
     * @return integer 
     */
    public function GetShop($action = 'update') {
        $actions = [0 => 'update', 1 => 'edit', 2 => 'create'];
        $index = null;
        if (in_array($action, $actions)) {
            $index = $this->CheckStatusGetId($action);
        }
        return $index;
    }

    /**
     * логика получения индефикатора магазина (один магазин , один процесс)
     * @var string $action действие (update - обновления,edit - обновления после редактирования,create - сохранения импортных товаров)
     * @return integer 
     */
    private function CheckStatusGetId($action) {
        $index = null;
        $field_name = $this->getNameColumn($action);
        switch ($action) {
            case 'create':
                $index = ParseShop::find()->select('id')->where([$field_name => ParseShop::IN_PROCESS])->asArray()->one()['id'];
                if (is_null($index)) {
                    $index = ParseShop::find()->select('id')->where([$field_name => ParseShop::NOT_PROCESS])->asArray()->one()['id'];
                    if (!is_null($index)) {
                        $this->setInProcessStatus($field_name, $index);
                    }
                }
                break;
            case 'edit':
                $index = ParseShop::find()->select('id')->where(['and',
                            [$field_name => ParseShop::HIGH_PRIORITY],
                            ['!=', 'prod_process', ParseShop::IN_PROCESS],
                            ['!=', 'update_process', ParseShop::IN_PROCESS],
                            ['!=', 'update_process', ParseShop::HIGH_PRIORITY]
                        ])->asArray()->one()['id'];
                if (is_null($index)) {
                    $index = ParseShop::find()->select('id')->where(['and',
                                [$field_name => ParseShop::IN_PROCESS],
                                ['!=', 'prod_process', ParseShop::IN_PROCESS],
                                ['!=', 'update_process', ParseShop::IN_PROCESS],
                                ['!=', 'update_process', ParseShop::HIGH_PRIORITY]
                            ])->asArray()->one()['id'];
//                    if(!is_null($index)){
//                        $this->setHighPriorityStatus($field_name, $index);
//                    }
                }
                $index = ParseShop::find()->select('id')->where(['and',
                            [$field_name => ParseShop::IN_PROCESS],
                            ['!=', 'prod_process', ParseShop::IN_PROCESS],
                            ['!=', 'update_process', ParseShop::IN_PROCESS],
                            ['!=', 'update_process', ParseShop::HIGH_PRIORITY]
                        ])->asArray()->one()['id'];
                break;
            case 'update':
                $date_now = date('Y-m-d H:i:s');
                $index = ParseShop::find()->select('id')->where(['and',
                            [$field_name => ParseShop::IN_PROCESS],
                            ['=', 'prod_process', ParseShop::LOADED],
                            ['!=', 'edit_process', ParseShop::IN_PROCESS]
                        ])->asArray()->one()['id'];
                if (is_null($index)) {
                    $index = ParseShop::find()->select('id')->where(['and',
                                [$field_name => ParseShop::HIGH_PRIORITY],
                                ['=', 'prod_process', ParseShop::LOADED],
                                ['!=', 'edit_process', ParseShop::IN_PROCESS]
                            ])->orderBy(['date_to_update' => SORT_ASC])->asArray()->one()['id'];
                    if (is_null($index)) {
                        $index = ParseShop::find()->select('id')->where(['and',
                                    [$field_name => ParseShop::NOT_PROCESS],
                                    ['<=', 'date_to_update', $date_now],
                                    ['=', 'prod_process', ParseShop::LOADED],
                                    ['!=', 'edit_process', ParseShop::IN_PROCESS]
                                ])->orderBy(['date_to_update' => SORT_ASC])->asArray()->one()['id'];
                    }
                    if (!is_null($index)) {
                        $this->setInProcessStatus($field_name, $index);
                        $this->fillXmlFile($index);
                        $this->importService->divideProducts($index);
                    }
                }
                break;
        }
        return $index;
    }

    /**
     * выкачка xml файл в директорию магазина и получения значений тегов xml
     * @var integer $shop_id индефикатора магазина
     * @return array 
     */
    private function getXmlFile($shop_id) {
        $shop = ParseShop::findOne($shop_id);
        $url = $shop->link;
        $curl = curl_init($url);
        $param = Yii::$app->params;
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, $param['import_settings']['ssl']);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $param['import_settings']['ssl']);
        $data = curl_exec($curl);
        curl_close($curl);
        if (empty($data)) {
            return ['status' => 'error', 'text' => 'Файл не был получен, проверте ссылку'];
        }
        $parser = xml_parser_create();
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, $data, $value, $tags);
        xml_parser_free($parser);
        if (empty($tags)) {
            $this->importService->writeLogs('Файл пустой , проверте ссылку');
            return false;
        }
        return ['value' => $value, 'tags' => $tags];
    }

    private function setPrimaryStatus($process_name, $index) {
        ParseShop::updateAll([$process_name => ParseShop::NOT_PROCESS], ['=', 'id', $index]);
    }

    private function setLoadedStatus($process_name, $index) {
        ParseShop::updateAll([$process_name => ParseShop::LOADED], ['=', 'id', $index]);
    }

    private function setInProcessStatus($process_name, $index) {
        ParseShop::updateAll([$process_name => ParseShop::IN_PROCESS], ['=', 'id', $index]);
    }

    private function setHighPriorityStatus($process_name, $index) {
        ParseShop::updateAll([$process_name => ParseShop::HIGH_PRIORITY], ['=', 'id', $index]);
    }

    /**
     * запись значений xml в json файл в директорию магазина 
     * @var integer $shop_id индефикатора магазина
     * @return array массив значений тегов 
     */
    private function fillXmlFile($shop_id) {
        $data = $this->getXmlFile($shop_id);
        $pathValue = Yii::getAlias('@backend') . '/modules/import/assets/shops/shop_' . $shop_id . '/import_attr.json';
        $pathTags = Yii::getAlias('@backend') . '/modules/import/assets/shops/shop_' . $shop_id . '/import_tags.json';
        $this->importService->writeJson($data['value'], $pathValue);
        $this->importService->writeJson($data['tags'], $pathTags);
    }

    /**
     * конвертация имя процесса в имя действия
     * @var sting $process_name 
     * @return string 
     */
    private function getNameColumn($process_name) {
        if ($process_name == 'create') {
            return 'prod_process';
        }
        if ($process_name == 'update') {
            return 'update_process';
        }
        if ($process_name == 'edit') {
            return 'edit_process';
        }
    }

    /**
     * получения продуктов и логика окончания процесса  
     * @var string $process_name 
     * @return mixed 
     */
    private function getParseProducts($process_name) {
        $index = $this->GetShop($process_name);
        if (is_null($index)) {
            //если нету магазина для процесса 
            return false;
        }
        // Пересмотреть IF
        if ($process_name != 'create') {
            $div_keys = $this->importService->readJson(Yii::getAlias('@backend') . '/modules/import/assets/shops/shop_' . $index . '/div_keys.json');
            //если есть магазин и у него есть товары удаленые из xml 
            if (isset($div_keys['del']) && !empty($div_keys['del'])) {
                if ($this->trashProducts($index, $div_keys, Yii::$app->cache->get('products_shop_' . $index))) {
                    return false;
                }
            }
        }
        // Убрать петлю getNameColumn
        $column_status = $this->getNameColumn($process_name);
        $products = $this->parseProducts($index);
        if (empty($products)) {
            if ($column_status == 'update_process' || $column_status == 'edit_process') {
                if ($column_status == 'edit_process') {
                    $path = Yii::getAlias('@backend') . '/modules/import/assets/shops/shop_' . $index . '/characters_id_old.json';
                    $category_id_old = $this->importService->readJson($path);
                    if (($column_status == 'edit_process') && !empty($category_id_old)) {
                        Characteristic::deleteAll(['in', 'id', $category_id_old]);
                    }
                }
                $update_frequency = ParseShop::find()->select('update_frequency')->where(['id' => $index])->asArray()->one()['update_frequency'];
                $date_to_update = $this->importService->getInterval(date('Y-m-d H:i:s'), $update_frequency);
                ParseShop::updateAll(['date_to_update' => $date_to_update], ['=', 'id', $index]);
                $this->setPrimaryStatus($column_status, $index);
            } else {
                $this->setLoadedStatus($column_status, $index);
            }
            return $this->getParseProducts($process_name);
        }
        return $products;
    }

    /**
     * отбор тегов продуктов из xml
     * @var integer $shop_id 
     * @return mixed 
     */
    private function parseProducts($shop_id) {
        $open = false;
        $i = 0;
        $product = [];
        $limit = 50; //add config.import_settings
        if (!is_null($shop_id)) {
            /* Собираем данные продуктов */
            $filepath = Yii::getAlias('@backend') . '/modules/import/assets/shops/shop_' . $shop_id . '/import_attr.json';
            $data = $this->importService->readJson($filepath);
            if (!empty($data)) {
                foreach ($data as $key => $oneTag) {
                    if ($i <= $limit) {
                        if ($open) {
                            if ($oneTag['tag'] === 'offer' && $oneTag['type'] === 'close') {
                                $open = false;
                                continue;
                            }
                            $product[$i] = $this->formattedProductArray($oneTag, $product, $i);
                            unset($data[$key]);
                        }
                        if ($oneTag['tag'] === 'offer' && $oneTag['type'] === 'open') {
                            $open = true;
                            $i++;
                            if ($i <= $limit) {
                                $product[$i] = $this->formattedProductArray($oneTag, $product, $i);
                                unset($data[$key]);
                            }
                        }
                    }
                }
                /* Запаписываем остаток обратно в файл */
                $this->importService->writeJson($data, $filepath);
                return $product;
            }
        }
        return false;
    }

    /**
     * формирования продуктов из отобраных тегов 
     * @var array $array тег с его атрибутами 
     * @var array $product массив продуктов
     * @var integer $i ключ массива продуктов 
     * @return array
     */
    private function formattedProductArray($array, $product, $i) {
        if (isset($array['value'])) {
            if ($array['tag'] === 'param') {
                $product[$i]['param'][$array['attributes']['name']] = $array['value'];
            } else {
                $product[$i][$array['tag']] = $array['value'];
            }
        } else if (isset($array['attributes']) && $array['tag'] !== 'param') {
            foreach ($array['attributes'] as $key => $value) {
                $product[$i][$key] = $value;
            }
        }
        return $product[$i];
    }

    /*
     * Метод возвращает товар с указанной ценой (без разницы какой)
     */

    private function getProdWithPrice($price, array $products) {
        foreach ($products as $prod) {
            if ($prod['trade_price'] == $price) {
                return $prod;
            }
        }
        return false;
    }

    /*
     * Метод определяет самый выгодный товар 
     */

    private function defineProfitableProduct($vendor, $current_price, $available = 0) {
        $product_cache_xml = [];
        $shops = ParseShop::find()->asArray()->all();
        $shops_id = array_column($shops, 'id');
        foreach ($shops_id as $id) {
            $product_cache_xml += Yii::$app->cache->get('products_shop_' . $id);
        }
        if (!empty($product_cache_xml)) {
            $same_prod_list = array_filter($product_cache_xml, function($element) use($vendor) {
                return (($element['vendor_code'] === $vendor) && ($element['publish_status'] === 1));
            });
            if (!empty($same_prod_list)) {
                $min_price = min(array_column($same_prod_list, 'trade_price'));
                if (!(($current_price < $min_price) && (bool) $available)) {
                    return $this->getProdWithPrice($min_price, $same_prod_list);
                }
            }
        }
        return false;
    }

    // private function isProfitable(){
    //     if ($prod_xml['id'] == $prod_db['product|import_id']) {
    //         $profitable = true;
    //     } else {
    //         if($products_cache_import[$prod_db['product|import_id']]['publish_status'] == 1){
    //             $profitable = (($prod_xml['price'] !== 0) && ($prod_xml['price'] < $products_db[$vendor_code]['product|price']) && ($this->importService->isTrue($prod_xml['available'])));
    //         }else{
    //             $profitable = true;
    //         }
    //     }
    // }
    /*
     * Метод сбора данных продукта с json файлов
     */
    private function assembleProductsXml($index, array $products_xml) {
        $connectionData = $this->importService->getConnectionData($index);
        $additional_fields_flip = array_flip($connectionData['additional_fields']);
        $def_fields_value = Yii::$app->params['import_settings']['def_fields_value'];
        $products_cache_import = Yii::$app->cache->get('products_shop_' . $index);
        $products = [];
        foreach ($products_xml as $key => $value) {
            $product_index = $key;
            $product = [];
            /* запись основных параметров */
            $error = false;
            $prod_xml = $products_xml[$key];
            foreach ($connectionData['main_fields'] as $k => $v) {
                if (array_key_exists($v, $prod_xml)) {
                    $product[$k] = $prod_xml[$v];
                } else if (isset($prod_xml['param']) && array_key_exists($v, $prod_xml['param'])) {
                    $product[$k] = $prod_xml['param'][$v];
                } else {
                    if (isset($def_fields_value[$k]) && isset($products_cache_import[$product['id']])) {
                        $product[$k] = $def_fields_value[$k];
                    } else {
                        $error = true;
                        $error_text = "У продукта с индексом:" . $value['id'] . " отсутствует поле " . $v;
                        break;
                    }
                }
            }
            if ($error) {
                $this->importService->writeLogs('assembleProductsXml : ' . $error_text, ('productShop_' . $index . '.txt'));
                continue;
            }
            /* запись дополнительный параметров */
            foreach ($prod_xml as $k => $v) {
                if ($k == 'param') {
                    foreach ($prod_xml['param'] as $k1 => $v1) {
                        if (array_search($k1, $connectionData['additional_fields'])) {
                            $product[$additional_fields_flip[$k1]] = $v1;
                        }
                    }
                } else {
                    if (array_search($k, $connectionData['additional_fields'])) {
                        $product[$additional_fields_flip[$k]] = $v;
                    }
                }
            }
            /* сопоставление категорий */
            $category_id_xml = $products_xml[$product_index][$connectionData['main_fields']['category_id']] ?? 0;
            $category_id = $connectionData['combine_category'][$category_id_xml] ?? 0;
            if (empty($category_id)) {
                $category_id = 0;
            }
            $product['category_id'] = $category_id;
            /* проверка на тип параметров продукта */
            $prod = $product;
            $valid = false;
            foreach ($prod as $k => $v) {
                if (!empty($prod[$k]) && isset(self::FIELD_TYPES[$k])) {
                    $valid = $this->importService->checkType($v, self::FIELD_TYPES[$k]);
                    if (!$valid) {
                        if (isset($products_cache_import[$product['id']]) && isset($def_fields_value[$k])) {
                            $product[$k] = $def_fields_value[$k];
                        } else {
                            $error_text = "Значение поля " . $k . " не соответсвует типу " . self::FIELD_TYPES[$k] . " в продукте " . $value['id'];
                            $this->importService->writeLogs('assembleProductsXml : ' . $error_text, ('productShop_' . $index . '.txt'));
                            continue;
                        }
                    }
                }
            }
            $characteristics = [];
            if (isset($prod_xml['param'])) {
                /* запись значений характеристик продуктов */
                $common_fields = array_intersect(array_values($connectionData['characters_shop']), array_keys($prod_xml['param']));
                foreach ($common_fields as $k => $v) {
                    $characteristics[$v] = $prod_xml['param'][$v];
                }
            }
            $product['characteristics'] = $characteristics;
            $products[$product_index] = $product;
        }
        return $products;
    }

    /*
     * Метод сбора кэша
     */

    private function assembleCacheFrame($shop_name, $shop_id, array $product = []) {
        if (empty($product)) {
            return false;
        }
        $product_cache_frame = [
            'id' => null,
            'category_name' => null,
            'product_name' => null,
            'category_id' => 0,
            'trade_price' => null,
            'price_old' => null,
            'publish_status' => 1,
            'char_value' => null,
            'vendor_code' => null,
            'manufacturer' => null,
            'amount' => null,
            'unit' => null,
            'price1' => null,
            'price2' => null,
            'type' => $shop_name,
            'shop_id' => $shop_id,
            'fields' => []
        ];
        $cache_category_name = ArrayHelper::map(Category::find()->select(['category.id', 'category_lang.name'])->joinWith('categoryLang')->asArray()->all(), 'id', 'name');
        $product_cache_xml = $product_cache_frame;
        $intersect_keys = array_intersect(array_keys($product), array_keys($product_cache_xml));
        foreach ($intersect_keys as $i_k) {
            $product_cache_xml[$i_k] = $product[$i_k];
        }
        $category_id = $product['category_id'];
        $category_name = 'Категория';
        if (!empty($cache_category_name)) {
            if ($category_id != 0) {
                $category_name = $cache_category_name[$category_id];
            }
        } else {
            $this->importService->writeLogs('assembleCacheFrame : пустой кеш категорий', 'productShop_' . $shop_id . '.txt');
        }
        $product_cache_xml['id_category'] = $category_id;
        $product_cache_xml['category_name'] = $category_name;
        $product_cache_xml['trade_price'] = $product['price'];
        if (empty($product_cache_xml['price_old'])) {
            $product_cache_xml['price_old'] = $product['price'];
        }
        $product_cache_xml['publish_status'] = (int) $this->importService->isTrue($product['available']);
        if (empty($product_cache_xml['amount'])) {
            $product_cache_xml['amount'] = $product_cache_xml['publish_status'] ? 1 : 0;
        }
        return $product_cache_xml;
    }

    /*
     * Метод выводит схему связей для продуктов
     */

    private function getProducts() {
        $fields = Yii::$app->db->createCommand("
        SELECT CONCAT('`',table_name, '`.`', column_name, '` AS `', table_name, '|', column_name, '`') AS fields
            FROM `information_schema`.`columns` 
            WHERE `table_schema` = DATABASE() AND `table_name` in ('product', 'category', 'seo_meta', 'filemanager_mediafile', 'manufacturer')")->queryAll();
        foreach ($fields as $k => $v) {
            $temp[] = $v['fields'];
        }
        $products = Product::find()->select(implode(', ', $temp) . ' ')->asArray()
                ->leftJoin('category', 'category.stock_id = product.category_id')
                ->leftJoin('seo_meta', 'seo_meta.page_id = product.stock_id')
                ->leftJoin('filemanager_mediafile', 'filemanager_mediafile.id = product.media_id')
                ->leftJoin('manufacturer', 'manufacturer.id = product.manufacturer_id')
                ->where(['<>', 'product.import_id', 'null'])
                ->asArray()
                ->all();
        return $products;
    }

    /*
     * Метод для загрузки картинок импорта
     */

    private function loadImportImages($url, $routes, $rename, $thumbs, $prod_id, $seo = ['alt' => '', 'description' => '']) {
        if (!empty($url)) {
            $filemanager = new Mediafile();
            $filemanager->setTagIds(['product']);
            $load_result = $filemanager->saveParsingFile($url, $routes, $rename, $seo);
            if ($load_result['status']) {
                $media_id = $filemanager->id;
                $filemanager->createThumbs($routes, $thumbs);
                return $media_id;
            } else {
                $this->importService->writeLogs($load_result['message'] . ' у продукта ' . $prod_id);
            }
        }
        return false;
    }

    /*
     * Метод для удаления картинок импорта
     */

    private function removeImportImages(Mediafile $filemanager, $routes, $prod_id) {
        $deleted = $filemanager->deleteFile($routes);
        if ($deleted) {
            if ($filemanager->deleteThumbs($routes)) {
                if ($filemanager->deleteEssenceImg($filemanager->id, Yii::$app->params['media']['default_img'])) {
                    if ($filemanager->delete()) {
                        return true;
                    }
                }
                $this->importService->writeLogs("Не удалось удалить запись картинки с таблицы у продукта " . $prod_id);
            } else {
                $this->importService->writeLogs("Не удалось удалить тамбы картинки у продукта " . $prod_id);
            }
        } else {
            $this->importService->writeLogs("Не удалось удалить главное изображение в продукте " . $prod_id);
        }
        return false;
    }

    /*
     * Метод для перезагрузки картинок импорта
     */

    private function reloadImg($url, $routes, $rename, $thumbs, $mediafile, $prod_id) {
        $media_id = $this->saveDefImg($routes, $rename, $thumbs);
        if ($mediafile->filename !== 'not-images.png') {
            $deleted = $this->removeImportImages($mediafile, $routes, $prod_id);
            if (!$deleted) {
                return $media_id;
            }
        }
        $seo = [
            'alt' => $mediafile->alt,
            'description' => $mediafile->description
        ];
        $load_result = $this->loadImportImages($url, $routes, $rename, $thumbs, $prod_id, $seo);
        $media_id = (!$load_result) ? $media_id : $load_result;
        return $media_id;
    }

    /*
     * Метод для сохранения картинки по умолчанию
     */

    private function saveDefImg($routes, $rename, $thumbs) {
        $path = Yii::getAlias($routes['basePath']) . '/img';
        $filename = 'not-images.png';
        if (file_exists($path . '/' . $filename)) {
            $fm = Mediafile::find()->where(['filename' => $filename])->one();
            if ($fm === null) {
                $fm = new Mediafile();
                $fm->filename = $filename;
                $fm->type = 'image/png';
                $fm->size = filesize($path . '/' . $filename);
                $fm->url = '/img/' . $filename;
                if ($fm->save()) {
                    if ($fm->isImage()) {
                        $fm->createThumbs($routes, $thumbs);
                    }
                }
            }
            return $fm->id;
        } else {
            $this->importService->writeLogs("Отсутствует картинка по умолчанию");
        }
        return false;
    }

    /*
     * Метод для сравнения двух картинок
     */

    private function compareImages($url1, $url2) {
        return (hash_file('md5', $url1) === hash_file('md5', $url2));
    }

    /*
     * Метод ввывода данных на консоль (не работает из задач крон)
     */

    private function consoleInfo($flag, $info = null) {
        switch ($flag) {
            case 'start':
                $this->stdout('Стартовала ' . $info . ' |', Console::FG_GREEN);
                break;
            case 'fill':
                usleep(100000);
                $this->stdout('=', Console::FG_GREEN);
                break;
            case 'end':
                $this->stdout('> Процесс завершен' . PHP_EOL, Console::FG_GREEN);
                break;
            case 'count':
                $this->stdout('Обработано (' . $info . ')' . PHP_EOL, Console::FG_GREEN);
                break;
            case 'echo':
                $this->stdout($info . PHP_EOL, Console::FG_GREEN);
                break;
            default:
                throw new \DomainException('Неверно указан flag');
        }
    }

    /*
     * Метод для обновления полей таблицы
     */

    private function updateFields($update_fields, $prod_xml, $prod_db) {
        foreach ($update_fields as $k) {
            $table_name = substr($k, 0, stripos($k, "|"));
            $field_name = substr($k, stripos($k, "|") + 1);
            if ($field_name !== 'id') {
                if (in_array($k, array_keys(self::FIELDS_DB))) {
                    if (in_array(self::FIELDS_DB[$k], array_keys($prod_xml))) {
                        $insert_val = $prod_xml[self::FIELDS_DB[$k]];
                        if ($k == 'product|alias') {
                            $insert_val = SlugGenerator::generateTranslate($insert_val);
                            if (Product::find()->where(['alias' => $insert_val])->one() !== null)
                                continue;
                        }
                        if (is_string($insert_val)) {
                            $insert_val = '\'' . addcslashes($insert_val, "'") . '\'';
                        }
                        if ($prod_db[$table_name . '|id'] === null) {
                            \Yii::$app->db->createCommand('INSERT INTO ' . $table_name . ' (' . $field_name . ') VALUES (' . $insert_val . ')');
                        } else {
                            \Yii::$app->db->createCommand('UPDATE ' . $table_name . ' SET ' . $field_name . '= ' . $insert_val . ' WHERE id=' . $prod_db[$table_name . '|id'])->execute();
                        }
                        $prod_db[$k] = $insert_val;
                    }
                }
            }
        }
        /* Устанавливаем новый статус для публикации */
        $publish = (int) $this->importService->isPublish($prod_xml, ($prod_db['category|publish'] ?? 0));
        if (!is_int($insert_val)) {
            $publish ? $publish = 1 : $publish = 0;
        }
        \Yii::$app->db->createCommand('UPDATE product SET publish = ' . $publish . ' WHERE id = ' . $prod_db['product|id'])->execute();
        return $prod_db;
    }

    /*
     * Метод сопоставляет статус публикации с категорией
     */

    private function getCategoryStatus(array $category_prosto) {
        $category_ids = array_filter($category_prosto, function($element) {
            return !empty($element);
        });
        $category_ids = array_unique($category_ids);
        $this->consoleInfo('start', 'занесение статусов для категорий');
        $cat_count = 0;
        $category_status[0] = 0;
        foreach ($category_ids as $id) {
            $category_status[$id] = 0;
            $category = Category::find()->where(['stock_id' => $id])->one();
            if ($category != null) {
                $category_status[$id] = $category->publish;
            }
            $cat_count += 1;
            $this->consoleInfo('fill');
        }
        $this->consoleInfo('end');
        $this->consoleInfo('count', $cat_count);
        return $category_status;
    }

    /*
     * выключения товаров которые убрали из xml 
     */

    private function trashProducts($shop_id, array $div_keys, array $cache = []) {
        $limit = Yii::$app->params['import_settings']['limit_import'];
        $absolutePath = Yii::getAlias('@backend') . '/modules/import/assets/shops/shop_' . $shop_id;
        $answer = false;
        if (!empty($cache)) {
            $this->consoleInfo('start', 'обработка удаленных товаров');
            $i = 0;
            while (($i < $limit) && ($i <= count($div_keys['del']))) {
                $prod_id = array_shift($div_keys['del']);
                $vendor_code = $cache[$prod_id]['vendor_code'];
                $this->disable($vendor_code, $shop_id, $cache[$prod_id]);
                $this->consoleInfo('fill');
                $i++;
            }
            $this->consoleInfo('end');
            $this->importService->writeJson($div_keys, $absolutePath . '/div_keys.json');
            $answer = true;
        }
        return $answer;
    }

    /*
     * Метод для сохранения продукта
     */

    private function save(array $prod, array $shop_model, $category_status = 0, array $connectionData = []) {
        $product_index = $prod['id'];
        //конвертация в грн
        $prod['price'] *= $shop_model['currency_value'];
        //сбор кеша
        $assemble_cache_frame = $this->assembleCacheFrame($shop_model['name'], $shop_model['id'], $prod);
        //поиск идентичного товара от другого магазина
        $prod['alias'] = SlugGenerator::slug($prod['product_name']);
        $prod_replace = Product::find()->where(['vendor_code' => $prod['vendor_code']])->orWhere(['product_lang.alias' => $prod['alias']])->joinWith('productLang')->one();
        if (is_null($prod_replace)) {
            // Пересмотреть
            //идентичный товар не найден && сохранения "not found img"
//            $save_img_result = $this->saveDefImg($this->filemanager_module->routes, $this->filemanager_module->rename, $this->filemanager_module->thumbs);
//            if ($save_img_result) {
//                $prod['media_id'] = $save_img_result;
//            } else {
//                $this->importService->writeLogs("Не удалось загрузить стандартное изображение для продукта " . $prod['id'], 'SaveImportProduct.txt');
//                return false;
//            }
            $prod['media_id'] = 1;

            //сохраниние в характеристики
            $prod['group_id'] = ShopGroup::find()->where(['shop_id' => $shop_model['id']])->one()->group_id;
            if (array_key_exists('characteristics', $prod) && !empty($connectionData['characters_id'])) {
                foreach ($prod['characteristics'] as $k => $value) {
                    $prod_char_model = new ProductCharacteristic();
                    $prod_char_model->product_import_id = $prod['id'];
                    $group_id = $prod['group_id'];
                    $prod_char_model->group_id = $group_id;
                    $prod_char_model->characteristic_id = $connectionData['characters_id'][$k];
                    $prod_char_model->value = $value;
                    $prod_char_model->save();
                }
            }
            //сохранение связки категории - характеристики
            $this->filter->saveCategoryCharacteristics(null, $prod['category_id'], [], $this->filter->getProdChars($prod['id']));
            //сохранение производителя
            if (isset($prod['manufacturer'])) {
                $prod_manufacture_model = Manufacturer::find()->where(['name' => $prod['manufacturer']])->one();
                if ($prod_manufacture_model == null) {
                    $prod_manufacture_model = new Manufacturer();
                    $prod_manufacture_model->name = $prod['manufacturer'];
                    $prod_manufacture_model->slug = SlugGenerator::slug($prod['manufacturer']);
                    $prod_manufacture_model->status = 1;
                    $prod_manufacture_model->save();
                }
                $prod['manufacturer_id'] = $prod_manufacture_model->id;
            }
            //сохранение продукта
            $prod_model = new Product();
            $prod_model->category_id = $prod['category_id'];
            $prod_model->media_id = $prod['media_id'];
            $prod_model->manufacturer_id = $prod['manufacturer_id'];
            //$prod_model->import_id = $prod['id'];
            $prod_model->group_id = $prod['group_id'];
            $prod_model->vendor_code = $prod['vendor_code'];
            $prod_model->amount = 1;
            $prod_model->rating = 0;
            $prod_model->publish = (int) $this->importService->isPublish($prod, $category_status);
            if ($prod_model->save()) {
                $LW = LangWidget::getActiveLanguageData(['id', 'alias']);
                foreach ($LW as $item) {
                    $pl = new ProductLang();
                    $pl->product_id = $prod_model->id;
                    $pl->lang_id = $item['id'];
                    $pl->alias = $prod['alias'];
                    $pl->name = $prod['product_name'];
                    $pl->description = (isset($prod['description'])) ? $prod['description'] : "";
                    $pl->price = $prod['price'];
                    $pl->currency = 'UAH';
                    $pl->save();
                }
            } else {
                $this->importService->writeLogs("Не удалось сохранить продукт с id: " . $prod['id'], 'SaveImportProduct.txt');
                return false;
            }
            if (!empty($connectionData['seo_template'])) {
                //генерация сео
                foreach ($connectionData['seo_template'] as $keySeo => $oneSeoColumn) {
                    $seo[$keySeo] = SeoMeta::SeoGenerator($oneSeoColumn, $assemble_cache_frame);
                }
                foreach($LW as $item){
                    $resultSaveSeo = SeoWidget::save($prod_model->id, 'product', [$item['alias'] => $seo['Product']]);
                    //сохранение сео товару
                    if (empty($resultSaveSeo)) {
                        $this->importService->writeLogs("Не удалось сохранить seo продукта с id: " . $prod['id'], 'SaveImportProduct.txt');
                        return false;
                    }
                }
            }
            //загрузка картинки товара
            $load_img_result = $this->loadImportImages($prod['img'], $this->filemanager_module->routes, $this->filemanager_module->rename, $this->filemanager_module->thumbs, $prod['id'], $seo['Photo']);
            if ($load_img_result) {
                $prod_model->media_id = $load_img_result;
                if (!$prod_model->update()) {
                    $this->importService->writeLogs('обновления товара, после прикрепления оригинальной картинки товара не прошло', 'SaveImportProduct.txt');
                }
            } else {
                $this->importService->writeLogs('загрузка оригинальных картинки товара не удалась', 'SaveImportProduct.txt');
            }
        } else {
            if (($prod['price'] !== 0) && ($prod['price'] < $prod_replace->productLang[0]['price']) && ($this->importService->isTrue($assemble_cache_frame['publish_status']))) {
                //$this->importService->writeLogs('Найден выгодный товар. Текущий товар: ' . $prod_replace->import_id . ' Выгодный товар: ' . $prod['id'], 'SaveImportProduct.txt');
                $this->importService->writeLogs('Найден выгодный товар. Выгодный товар: ' . $prod['id'], 'SaveImportProduct.txt');
                //при нахождения выгодной цены у товара используем его
                //$prod_replace->import_id = $prod['id'];
                //$prod_replace->price = $prod['price'];
                //$prod_replace->save();
                ProductLang::updateAll(['price' => $prod['price']], ['=', 'product_id', $prod_replace['id']]);
            }
        }
//        //Сохранение в кеш
//        $shop_cache = [];
//        $product_cache_xml = ProductController::attachFieldDB($assemble_cache_frame, ['vendor_code' => $assemble_cache_frame['vendor_code']]);
//        $shop_cache[$product_index] = $product_cache_xml;
//        if (Yii::$app->cache->exists('products_shop_' . $shop_model['id'])) {
//            $shop_cache = $shop_cache + Yii::$app->cache->get('products_shop_' . $shop_model['id']);
//        }
//        if (!Yii::$app->cache->set('products_shop_' . $shop_model['id'], $shop_cache, true)) {
//            $this->writeLogs('Продукт не получилось занести в кеш', 'SaveImportProduct.txt');
//            $this->consoleInfo('echo', 'Кеш словил маслину');
//            return false;
//        }
        return true;
    }

    /*
     * Метод для редактирования продукта
     */

    private function edit(array $prod_xml, array $products_db, array $characters_id, ParseShop $shop_model) {
        $id = $prod_xml['id'];
        $products_cache_import = Yii::$app->cache->get('products_shop_' . $shop_model->id); //актуализируем кеш
        $vendor_code = $prod_xml['vendor_code'];
        $prod_xml['price'] *= $shop_model->currency_value; //конвертация в грн
        if (isset($products_db[$vendor_code])) {
            $prod_db = $products_db[$vendor_code];
            $product = Product::findOne($prod_db['product|id']);
            $category_id_old = $prod_db['product|category_id'];

            /* Проверяем является ли продукт в этом магазине выгодным */
            $profitable = false;
            if ($id == $prod_db['product|import_id']) {
                $profitable = true;
            } else {
                if ($products_cache_import[$prod_db['product|import_id']]['publish_status'] == 1) {
                    $profitable = (($prod_xml['price'] !== 0) && ($prod_xml['price'] < $prod_db['product|price']) && ($this->importService->isTrue($prod_xml['available'])));
                } else {
                    $profitable = true;
                }
            }
            if ($profitable) {
                $update_fields = array_keys($prod_db);

                /* Произволитель может быть общим для других товаров, поэтому исключем его из выборки и обновляем отдельно */
                unset($prod_db['manufacturer|name']);
                $prod_manufacture_model = Manufacturer::find()->where(['name' => $prod_xml['manufacturer']])->one();
                if ($prod_manufacture_model == null) {
                    $prod_manufacture_model = new Manufacturer();
                    $prod_manufacture_model->name = $prod_xml['manufacturer'];
                    $prod_manufacture_model->slug = SlugGenerator::generateTranslate($prod_manufacture_model->name);
                    $prod_manufacture_model->status = 1;
                    $prod_manufacture_model->save();

                    /* Пересохраняем id производителя */
                    $product->manufacturer_id = $prod_manufacture_model->id;
                    $product->save();
                }

                /* получаем id группы */
                if (is_null($product->group_id)) {
                    $shop_group = ShopGroup::find()->where(['shop_id' => $shop_model->id])->one();
                    $product->group_id = (!is_null($shop_group)) ? $shop_group->group_id : null;
                }

                /* определяем текущую картинку товара */
                $media_id = $prod_db['filemanager_mediafile|id'];
                $mediafile = Mediafile::findOne($media_id);
                if (!$this->compareImages($prod_xml['img'], Yii::getAlias('@backend') . '/web' . $mediafile->url)) {
                    $media_id = $this->reloadImg($prod_xml['img'], $this->filemanager_module->routes, $this->filemanager_module->rename, $this->filemanager_module->thumbs, $mediafile, $id);
                    if ($media_id) {
                        $product->media_id = $media_id;
                    }
                }

                $product->update();
                $prod_db = $this->updateFields($update_fields, $prod_xml, $prod_db); //обновляем данные, измененные пользователем
                $characters_old = $this->filter->getProdChars($prod_xml['id']); //получаем старые характеристики

                /* Обновление характеристик продукта */
                $group_id = $product->group_id;
                ProductCharacteristic::deleteAll(['group_id' => $group_id, 'product_import_id' => $id]);
                if (isset($prod_xml['characteristics']) && !empty($prod_xml['characteristics'])) {
                    foreach ($prod_xml['characteristics'] as $nm => $val) {
                        $char_model = new ProductCharacteristic();
                        $char_model->group_id = $group_id;
                        $char_model->characteristic_id = $characters_id[$nm];
                        $char_model->value = $val;
                        $char_model->product_import_id = $id;
                        $char_model->save();
                    }
                }
                $this->filter->saveCategoryCharacteristics($category_id_old, $prod_xml['category_id'], $characters_old, $this->filter->getProdChars($prod_xml['id'])); //сохранение связки категории - характеристики
            }
        }

        /* Обновление значений кеша */
        $products_cache_import[$id] = $this->assembleCacheFrame($shop_model->name, $shop_model->id, $prod_xml);
        $products_cache_import[$id] = ProductController::attachFieldDB($products_cache_import[$id], ['vendor_code' => $vendor_code]);
        Yii::$app->cache->set('products_shop_' . $shop_model->id, $products_cache_import);
    }

    /*
     * Метод для обновления продукта
     */

    private function update(array $prod_xml, array $products_db, array $shop_model, array $characters_id) {
        $vendor_code = $prod_xml['vendor_code'];
        //конвертация в грн
        $prod_xml['price'] *= $shop_model['currency_value'];
        //получения кеша
        $products_cache_import = Yii::$app->cache->get('products_shop_' . $shop_model['id']);
        //отсекаем пустые поля товара из базы даных
        if (isset($products_db[$vendor_code])) {
            $prod_db = $products_db[$vendor_code];
            $update_fields = array_filter($prod_db, function($element) {
                return empty($element);
            });
        } else {
            $this->importService->writeLogs('update не найден продукт : ' . $vendor_code, 'productShop_' . $shop_model['id'] . '.txt');
            return false;
        }
        //получаем ключи полей товара из базы даных
        $update_fields_key = array_keys($update_fields);
        $profitable = false;
        //проверка на существования товара от другого поставщика и на выгодную цену
        if ($prod_xml['id'] == $prod_db['product|import_id']) {
            $profitable = true;
        } else {
            if ($products_cache_import[$prod_db['product|import_id']]['publish_status'] == 1) {
                $profitable = (($prod_xml['price'] !== 0) && ($prod_xml['price'] < $products_db[$vendor_code]['product|price']) && ($this->importService->isTrue($prod_xml['available'])));
            } else {
                $profitable = true;
            }
        }
        //обновления данных в случае выгодного по цене товара
        if ($profitable) {
            array_push($update_fields_key, 'product|import_id');
            array_push($update_fields_key, 'product|price');
        }
        //обновления полей и данных связаных таблиц
        $prod_db = $this->updateFields($update_fields_key, $prod_xml, $prod_db);
        //oбновление характеристик продукта
        if (isset($prod_xml['characteristics']) && !empty($prod_xml['characteristics'])) {
            $characters_old = $this->filter->getProdChars($prod_db['product|import_id']);
            $group_id = $prod_db['product|group_id'];
            foreach ($prod_xml['characteristics'] as $nm => $val) {
                if (in_array($nm, array_keys($characters_id))) {
                    $char_model = ProductCharacteristic::find()->where(['group_id' => $group_id, 'characteristic_id' => $characters_id[$nm], 'product_import_id' => $prod_xml['id']])->one();
                    if ($char_model === null) {
                        $char_model = new ProductCharacteristic();
                        $char_model->group_id = $group_id;
                        $char_model->characteristic_id = $characters_id[$nm];
                        $char_model->value = $val;
                        $char_model->product_import_id = $prod_xml['id'];
                    } else {
                        if (empty($char_model->value)) {
                            $char_model->value = $val;
                        }
                    }
                    $char_model->save();
                }
            }
            $characters_new = $this->filter->getProdChars($prod_db['product|import_id']);
            $this->filter->saveCategoryCharacteristics($prod_db['product|category_id'], $prod_db['product|category_id'], $characters_old, $characters_new); //сохранение связки категории - характеристики
        }
        //собираем кеш товара
        $prod_cache_xml = $this->assembleCacheFrame($shop_model['name'], $shop_model['id'], $prod_xml);
        $prod_cache_import = $products_cache_import[$prod_xml['id']];
        foreach ($prod_cache_import as $field => $val) {
            if (empty($val) || $field == 'trade_price' || $field == 'price_old' || $field == 'publish_status') {
                $prod_cache_import[$field] = $prod_cache_xml[$field];
            }
        }
        //обновляем кеш
        $products_cache_import[$prod_xml['id']] = $prod_cache_import;
        $products_cache_import[$prod_xml['id']] = ProductController::attachFieldDB($products_cache_import[$prod_xml['id']], ['import_id' => $prod_xml['id']]);
        Yii::$app->cache->set('products_shop_' . $shop_model['id'], $products_cache_import);
    }

    /*
     * логика выключения товаров которые убрали из xml 
     */

    private function disable($vendor_code, $shop_id, array $product) {
        $id = $product['id'];
        $products_cache_import = Yii::$app->cache->get('products_shop_' . $shop_id); //актуализируем кеш
        $prod_model = Product::find()->where(['import_id' => $product['id']])->one();
        if (!is_null($prod_model)) {
            $profitable_product = $this->defineProfitableProduct($vendor_code, $product['trade_price'], $product['publish_status']);
            if ($profitable_product !== false) {
                $prod_model->import_id = $profitable_product['id'];
                $prod_model->price = $profitable_product['trade_price'];
                $prod_model->publish = (int) ($product['category_id'] !== 0);
            } else {
                $prod_model->publish = 0;
            }
            $prod_model->save();
        }
        $product['publish_status'] = 0;
        $products_cache_import[$id] = $product;
        Yii::$app->cache->set('products_shop_' . $shop_id, $products_cache_import);
    }

    /*
     * входная точка сохранения продуктов 
     */

    public function actionSaveProducts() {
        $action = 'create';
        //отсекания более 1 потока
        if ($this->importService->ChecklockProcess($action)) {
            return false;
        } else {
            $this->importService->lockProcess($action);
        }
        //получения товаров + обновления статуса магазину если продуктов нет то перейти к след магазину
        $products_xml = $this->getParseProducts($action);
        if (!$products_xml) {
            $this->importService->unlockProcess($action);
            return false;
        }
        //выбор записи текущего магазина
        $shop_model = ParseShop::find()->where([$this->getNameColumn($action) => ParseShop::IN_PROCESS])->asArray()->one();
        $this->importService->writeLogs('Сохранения товаров ' . $shop_model['name'] . ' : начало , количество продуктов на обработку : ' . 50, ('cronProcess_' . $action . '.txt'));
        //обработка массива с товарами xml
        $assemleProduct = $this->assembleProductsXml($shop_model['id'], $products_xml);
        //получения данных для построения связей(прм: соответствия категорий xml к категорий prosto)
        $connectionData = $this->importService->getConnectionData($shop_model['id']);
        $categories_status = $this->getCategoryStatus($connectionData['category_prosto']);
        $prod_count = 0;
        $this->consoleInfo('start', 'создание продуктов');
        foreach ($assemleProduct as $prod) {
            //сохранения товара
            $category_id = $prod['category_id'];
            $category_status = (isset($categories_status[$category_id])) ? $categories_status[$category_id] : 0;
            if (!$this->save($prod, $shop_model, $category_status, $connectionData))
                continue;
            $prod_count++;
            $this->consoleInfo('fill', $prod_count);
        }
        $this->consoleInfo('end');
        $this->importService->unlockProcess($action);
        $this->importService->writeLogs('Сохранения товаров ' . $shop_model['name'] . ' : конец , количество обработаных продуктов : ' . $prod_count, ('cronProcess_' . $action . '.txt'));
    }

    /*
     * входная точка обновления продуктов после редактирования
     */

    public function actionEditProducts() {
        $action = 'edit';
        //отсекания более 1 потока
        if ($this->importService->ChecklockProcess($action)) {
            return false;
        } else {
            $this->importService->lockProcess($action);
        }
        //получения товаров + обновления статуса магазину если продуктов нет то перейти к след магазину
        $ParseProduct = $this->getParseProducts($action);
        if (!$ParseProduct) {
            $this->importService->unlockProcess($action);
            return false;
        }
        $shop_model = ParseShop::find()->where([$this->getNameColumn($action) => ParseShop::IN_PROCESS])->one();
        $shop_id = $shop_model->id;
        $this->importService->writeLogs('Обновления после редактирования магазина ' . $shop_model->name . ' : начало , количество продуктов на обработку : ' . Yii::$app->params['import_settings']['limit_import'], ('cronProcess_' . $action . '.txt'));
        $products_cache_import = [];
        /* Собираем данные */
        $absolutePath = Yii::getAlias('@backend') . '/modules/import/assets/shops/shop_' . $shop_id;
        $params_data = $this->importService->getConnectionData($shop_id);
        $characters_id = $params_data['characters_id'];
        $categories_status = $this->getCategoryStatus($params_data['category_prosto']);
        $div_prod_keys = $this->importService->readJson($absolutePath . '/div_keys.json');
        $products_cache_import = Yii::$app->cache->get('products_shop_' . $shop_id);
        /* Собираем продукты c xml и базы */
        $products_xml = ArrayHelper::index($this->assembleProductsXml($shop_id, $ParseProduct), 'id');
        $products_db = ArrayHelper::index($this->getProducts(), 'product|vendor_code');
        $products_all = ArrayHelper::index($products_xml, 'id');
        $this->consoleInfo('start', 'редакторование продуктов');
        $prod_count = 0;
        foreach ($products_all as $id => $product) {
            switch ($id) {
                /* Обработка существующего товара */
                case (in_array($id, $div_prod_keys['exists']) && isset($products_xml[$id])):
                    if (!$this->edit($products_xml[$id], $products_db, $characters_id, $shop_model))
                        continue;
                    break;
                /* Обработка добаленного товара */
                case (in_array($id, $div_prod_keys['add'])):
                    $category_id = $products_xml[$id]['category_id'];
                    $category_status = (isset($categories_status[$category_id])) ? $categories_status[$category_id] : 0;
                    if (!$this->save($products_xml[$id], ArrayHelper::toArray($shop_model), $category_status, $params_data))
                        continue;
                    break;
                default: $this->consoleInfo('start', 'Нихуя ' . $id);
            }
            $prod_count += 1;
            $this->consoleInfo('fill');
        }
        $this->consoleInfo('end');
        $this->consoleInfo('count', $prod_count);
        $this->importService->unlockProcess($action);
        $this->importService->writeLogs('Обновления после редактирования магазина ' . $shop_model['name'] . ' : конец , количество обработаных продуктов : ' . $prod_count, ('cronProcess_' . $action . '.txt'));
    }

    /*
     * входная точка обновления продуктов
     */

    public function actionUpdateProducts() {
        $action = 'update';
        //отсекания более 1 потока
        if ($this->importService->ChecklockProcess($action)) {
            return false;
        } else {
            $this->importService->lockProcess($action);
        }
        //получения товаров + обновления статуса магазину если продуктов нет то перейти к след магазину
        $ParseProduct = $this->getParseProducts($action);
        if (!$ParseProduct) {
            $this->importService->unlockProcess($action);
            return false;
        }
        //выбор записи текущего магазина
        $shop_model = ParseShop::find()->where([$this->getNameColumn($action) => ParseShop::IN_PROCESS])->asArray()->one();
        $this->importService->writeLogs('Обновления товаров ' . $shop_model['name'] . ' : начало , количество продуктов на обработку : ' . Yii::$app->params['import_settings']['limit_import'], ('cronProcess_' . $action . '.txt'));
        //получения данных для построения связей(прм: соответствия категорий xml к категорий prosto)
        $connectionData = $this->importService->getConnectionData($shop_model['id']);
        //сбор продуктов xml с изменениям ключа елемента на индефикатор
        $products_xml = ArrayHelper::index($this->assembleProductsXml($shop_model['id'], $ParseProduct), 'id');
        //сбор продуктов с вместе с связанными данными
        $products_db = ArrayHelper::index($this->getProducts(), 'product|vendor_code');
        //сбор статусов категорий 
        $categories_status = $this->getCategoryStatus($connectionData['category_prosto']);
        //Изменяя ключи массива на ключи продуктов 
        $products_all = ArrayHelper::index($products_xml, 'id');
        $div_keys = $this->importService->readJson(Yii::getAlias('@backend') . '/modules/import/assets/shops/shop_' . $shop_model['id'] . '/div_keys.json');
        $prod_count = 0;
        foreach (array_keys($products_all) as $id) {
            switch ($id) {
                //обновления товара
                case (in_array($id, $div_keys['exists']) && isset($products_xml[$id])):
                    if (!$this->update($products_xml[$id], $products_db, $shop_model, $connectionData['characters_id']))
                        continue;
                    break;
                //сохранения ново добавленого товара 
                case (in_array($id, $div_keys['add'])):
                    $category_id = $products_xml[$id]['category_id'];
                    $category_status = (isset($categories_status[$category_id])) ? $categories_status[$category_id] : 0;
                    if (!$this->save($products_xml[$id], $shop_model, $category_status, $connectionData))
                        continue;
                    break;
            }
            $prod_count += 1;
        }
        $this->importService->unlockProcess($action);
        $this->importService->writeLogs('Обновления товаров ' . $shop_model['name'] . ' : конец , количество обработаных продуктов : ' . $prod_count, ('cronProcess_' . $action . '.txt'));
    }

}
