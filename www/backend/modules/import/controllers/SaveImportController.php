<?php

namespace backend\modules\import\controllers;

use backend\controllers\BaseController;
use Yii;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use backend\modules\seo\models\SeoMeta;
use backend\modules\category\controllers\CategoryController;
use backend\modules\product\controllers\ProductController;
use Ausi\SlugGenerator\SlugGenerator;
use backend\modules\product\models\Product;
use backend\modules\import\models\ParseShop;
use backend\modules\import\models\ShopGroup;
use backend\modules\filemanager\controllers\FileController;
use backend\modules\filemanager\models\Mediafile;
use backend\modules\product\models\Group;
use backend\modules\product\models\Characteristic;
use backend\modules\product\models\ProductCharacteristic;
use backend\modules\category\models\Category;
use backend\modules\product\models\Manufacturer;
use dosamigos\transliterator\TransliteratorHelper;
use backend\modules\import\service\ImportService;
use backend\modules\import\service\FilterAttr;
use common\controllers\AccessController;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

class SaveImportController extends BaseController {

    const SEO_KEYS = [
        'seo_header',
        'seo_name',
        'seo_keyword',
        'seo_description',
        'seo_text'
    ];
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
        'manufacturer' => 'string_not_empty',
        'available' => 'boolean',
        'unit' => 'string'
    ];

    public $importService;
    public $error_field;
    public $filter;

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
            return parent::beforeAction($action);
        } else {
            return false;
        }
    }

    public function init() {
        parent::init();
        $this->importService = new ImportService();
        $this->filter = new FilterAttr();
    }

    private function createShopProductsDataFolder($index) {
        $absolutePath = Yii::getAlias('@backend') . '/modules/import/assets/shops/shop_' . $index;
        if (!file_exists($absolutePath)) {
            mkdir($absolutePath);
        }
        $this->moveParams($index);
    }

    public function validateAttrs(array $main_fields, $additional_fields) {
        $absolutePath = Yii::getAlias('@backend') . '/modules/import/assets/xml';
        $tags = $this->importService->readJson($absolutePath . '/import_tags.json');
        $attr = $this->importService->readJson($absolutePath . '/import_attr.json');
        if (!empty($main_fields)) {
            $validate_fields = $main_fields + array_diff($additional_fields, array(''));
            foreach ($validate_fields as $key => $value) {
                $param_tag = [];
                if (isset($tags[$value])) {
                    $param_key = $tags[$value];
                    $filter_attr = \yii\helpers\ArrayHelper::getColumn($attr, function($element) use ($value) {
                                if ($element['tag'] == $value) {
                                    return $element;
                                }
                            });
                    $param_tag = $filter_attr[array_rand($param_key)];
                } else {
                    $param_key = $tags['param'];
                    do {
                        $param_tag = $attr[array_shift($param_key)];
                    } while (!empty($param_key) && (trim($param_tag['attributes']['name']) !== $value));
                    if (empty($param_key)) {
                        $param_key = $tags['offer'];
                        $open_tags = array_filter(array_intersect_key($attr, array_flip($param_key)), function($element) {
                            return isset($element['attributes']);
                        });
                        $param_tag = array_shift($open_tags);
                    }
                }
                if (isset(self::FIELD_TYPES[$key])) {
                    if (!empty($param_tag)) {
                        if (array_key_exists('value', $param_tag)) {
                            $param_val = $param_tag['value'];
                        } else if (isset($param_tag['attributes'][$value])) {
                            $param_val = $param_tag['attributes'][$value];
                        } else {
                            $this->error_field = $key;
                            return false;
                        }
                        $type = self::FIELD_TYPES[$key];
                        if ($type == 'string_not_empty' || $type == 'string' || $type == 'key') {
                            $param_val = $this->stringToType($param_val);
                        }
                        if (!$this->validate($param_val, $key)) {
                            $this->error_field = $key;
                            return false;
                        }
                    }
                }
            }
        }
        return true;
    }

    private function stringToType($var) {
        $types = ['integer', 'boolean', 'string'];
        foreach ($types as $type) {
            $valid = $this->importService->checkType($var, $type);
            if ($valid)
                break;
        }
        settype($var, $type);
        return $var;
    }

    private function validate($param_val, $key) {
        if ($param_val !== '') {
            $valid = $this->importService->checkType($param_val, self::FIELD_TYPES[$key]);
            return $valid;
        }
        return false;
    }

    public function actionCheckValidFields() {
        $cause = "Запрос не прошел на валидацию";
        $field = 'none';
        $fields_name = Yii::$app->getModule('import')->params['xml-main-fields'] + Yii::$app->getModule('import')->params['xml-additional-fields'];
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            $main_fields = $post['requiredField'];
            $additional_fields = $post['additionalField'];
            if ($this->validateAttrs($main_fields, $additional_fields)) {
                return Json::encode(['status' => 'success']);
            } else {
                $cause = "Неверный тип у поля " . $fields_name[$this->error_field];
                $field = $this->error_field;
            }
        }
        return Json::encode(['status' => 'error', 'message' => $cause, 'field' => $field]);
    }

    private function addItem($mas, $parent_id = 0) {
        $data = [];
        foreach ($mas as $k => $v) {
            if ($v['parent_id'] == $parent_id) {
                $data[$k]['parent'] = $v;
                $data[$k]['child'] = self::addItem($mas, $v['id']);
            }
        }
        return $data;
    }

    public function actionStartSave() {
        /* сбор значений с формы */
        $post = Yii::$app->request->post();
        $characters_id = [];
        $shop_data = $post['ParseShop'];
        $main_fields = $post['requiredField'];
        $additional_fields = $post['additionalField'];
        $category_prosto = $post['categoryProsto'];
        $characters_shop = (isset($post['characteristic'])) ? $post['characteristic'] : [];
//        // пересмотреть код
//        $category_data = ArrayHelper::index(Category::find()
//                                ->select(['category.id', 'category.parent_id', 'category_lang.name'])
//                                ->leftJoin('category_lang', 'category_lang.category_id = category.id')
//                                ->asArray()->all(), 'id');
//        $category_shop = $this->addItem($category_data);
//        //$category_shop = unserialize('a:2:{i:0;a:5:{i:80257;a:2:{s:6:"parent";a:2:{s:4:"name";s:26:"Телефоны, MP3, GPS";s:9:"parent_id";i:0;}s:5:"child";a:6:{i:80003;a:2:{s:6:"parent";a:2:{s:4:"name";s:35:"Мобильные телефоны";s:9:"parent_id";s:5:"80257";}s:5:"child";a:0:{}}i:80023;a:2:{s:6:"parent";a:2:{s:4:"name";s:33:"Электронные книги";s:9:"parent_id";s:5:"80257";}s:5:"child";a:0:{}}i:80027;a:2:{s:6:"parent";a:2:{s:4:"name";s:16:"Наушники";s:9:"parent_id";s:5:"80257";}s:5:"child";a:0:{}}i:80263;a:2:{s:6:"parent";a:2:{s:4:"name";s:89:"Аксессуары для мобильных телефонов и смартфонов";s:9:"parent_id";s:5:"80257";}s:5:"child";a:5:{i:80032;a:2:{s:6:"parent";a:2:{s:4:"name";s:28:"Bluetooth-гарнитуры";s:9:"parent_id";s:5:"80263";}s:5:"child";a:0:{}}i:146229;a:2:{s:6:"parent";a:2:{s:4:"name";s:55:"Чехлы для мобильных телефонов";s:9:"parent_id";s:5:"80263";}s:5:"child";a:0:{}}i:146332;a:2:{s:6:"parent";a:2:{s:4:"name";s:69:"Аккумуляторы для мобильных телефонов";s:9:"parent_id";s:5:"80263";}s:5:"child";a:0:{}}i:651392;a:2:{s:6:"parent";a:2:{s:4:"name";s:19:"Смарт-часы";s:9:"parent_id";s:5:"80263";}s:5:"child";a:0:{}}i:146202;a:2:{s:6:"parent";a:2:{s:4:"name";s:45:"Защитные пленки и стекла";s:9:"parent_id";s:5:"80263";}s:5:"child";a:2:{i:4635113;a:2:{s:6:"parent";a:2:{s:4:"name";s:29:"Защитные пленки";s:9:"parent_id";s:6:"146202";}s:5:"child";a:0:{}}i:4635121;a:2:{s:6:"parent";a:2:{s:4:"name";s:29:"Защитные стекла";s:9:"parent_id";s:6:"146202";}s:5:"child";a:0:{}}}}}}i:4626529;a:2:{s:6:"parent";a:2:{s:4:"name";s:49:"Мобильная связь и интернет";s:9:"parent_id";s:5:"80257";}s:5:"child";a:1:{i:83244;a:2:{s:6:"parent";a:2:{s:4:"name";s:35:"Мобильный интернет";s:9:"parent_id";s:7:"4626529";}s:5:"child";a:0:{}}}}i:4628089;a:2:{s:6:"parent";a:2:{s:4:"name";s:61:"Оптические аксессуары и адаптеры";s:9:"parent_id";s:5:"80257";}s:5:"child";a:1:{i:4628096;a:2:{s:6:"parent";a:2:{s:4:"name";s:63:"Объективы для мобильных устройств";s:9:"parent_id";s:7:"4628089";}s:5:"child";a:0:{}}}}}}i:81202;a:2:{s:6:"parent";a:2:{s:4:"name";s:55:"Активный отдых, туризм и хобби";s:9:"parent_id";i:0;}s:5:"child";a:1:{i:82411;a:2:{s:6:"parent";a:2:{s:4:"name";s:30:"Туризм и кемпинг";s:9:"parent_id";s:5:"81202";}s:5:"child";a:1:{i:82445;a:2:{s:6:"parent";a:2:{s:4:"name";s:38:"Рюкзаки и гермомешки";s:9:"parent_id";s:5:"82411";}s:5:"child";a:0:{}}}}}}i:88468;a:2:{s:6:"parent";a:2:{s:4:"name";s:30:"Товары для детей";s:9:"parent_id";i:0;}s:5:"child";a:3:{i:4265805;a:2:{s:6:"parent";a:2:{s:4:"name";s:29:"Детские игрушки";s:9:"parent_id";s:5:"88468";}s:5:"child";a:2:{i:100812;a:2:{s:6:"parent";a:2:{s:4:"name";s:59:"Машинки, модели техники и оружие";s:9:"parent_id";s:7:"4265805";}s:5:"child";a:1:{i:97422;a:2:{s:6:"parent";a:2:{s:4:"name";s:47:"Радиоуправляемые игрушки";s:9:"parent_id";s:6:"100812";}s:5:"child";a:0:{}}}}i:100784;a:2:{s:6:"parent";a:2:{s:4:"name";s:40:"Развитие и творчество";s:9:"parent_id";s:7:"4265805";}s:5:"child";a:1:{i:102912;a:2:{s:6:"parent";a:2:{s:4:"name";s:20:"Творчество";s:9:"parent_id";s:6:"100784";}s:5:"child";a:1:{i:4632117;a:2:{s:6:"parent";a:2:{s:4:"name";s:18:"Рисование";s:9:"parent_id";s:6:"102912";}s:5:"child";a:0:{}}}}}}}}i:3933347;a:2:{s:6:"parent";a:2:{s:4:"name";s:47:"Прогулки и активный отдых";s:9:"parent_id";s:5:"88468";}s:5:"child";a:1:{i:4625901;a:2:{s:6:"parent";a:2:{s:4:"name";s:32:"Электротранспорт";s:9:"parent_id";s:7:"3933347";}s:5:"child";a:0:{}}}}i:4630482;a:2:{s:6:"parent";a:2:{s:4:"name";s:26:"Робототехника";s:9:"parent_id";s:5:"88468";}s:5:"child";a:0:{}}}}i:80258;a:2:{s:6:"parent";a:2:{s:4:"name";s:37:"ТВ, Аудио/Видео, Фото";s:9:"parent_id";i:0;}s:5:"child";a:2:{i:80011;a:2:{s:6:"parent";a:2:{s:4:"name";s:22:"Медиаплееры";s:9:"parent_id";s:5:"80258";}s:5:"child";a:0:{}}i:80015;a:2:{s:6:"parent";a:2:{s:4:"name";s:44:"Телевизоры и аксессуары";s:9:"parent_id";s:5:"80258";}s:5:"child";a:1:{i:165692;a:2:{s:6:"parent";a:2:{s:4:"name";s:39:"ТВ-антенны и ресиверы";s:9:"parent_id";s:5:"80015";}s:5:"child";a:0:{}}}}}}i:80253;a:2:{s:6:"parent";a:2:{s:4:"name";s:40:"Компьютеры и ноутбуки";s:9:"parent_id";i:0;}s:5:"child";a:3:{i:80004;a:2:{s:6:"parent";a:2:{s:4:"name";s:16:"Ноутбуки";s:9:"parent_id";s:5:"80253";}s:5:"child";a:0:{}}i:80026;a:2:{s:6:"parent";a:2:{s:4:"name";s:51:"Компьютерные комплектующие";s:9:"parent_id";s:5:"80253";}s:5:"child";a:2:{i:80261;a:2:{s:6:"parent";a:2:{s:4:"name";s:36:"Товары для геймеров";s:9:"parent_id";s:5:"80026";}s:5:"child";a:1:{i:80020;a:2:{s:6:"parent";a:2:{s:4:"name";s:66:"Игровые консоли и детские приставки";s:9:"parent_id";s:5:"80261";}s:5:"child";a:0:{}}}}i:80087;a:2:{s:6:"parent";a:2:{s:4:"name";s:20:"Видеокарты";s:9:"parent_id";s:5:"80026";}s:5:"child";a:0:{}}}}i:80111;a:2:{s:6:"parent";a:2:{s:4:"name";s:39:"Сетевое оборудование";s:9:"parent_id";s:5:"80253";}s:5:"child";a:1:{i:80193;a:2:{s:6:"parent";a:2:{s:4:"name";s:28:"Маршрутизаторы";s:9:"parent_id";s:5:"80111";}s:5:"child";a:0:{}}}}}}}i:1;N;}');
//        $combine_category = array_combine(array_values($this->importService->getCategoryKeys($category_shop)), array_values($category_prosto));
//        // /пересмотреть код

        /* сохранение магазина */
        $shop_model = new ParseShop();
        foreach ($shop_data as $key => $value) {
            $shop_model->$key = $value;
        }
        $shop_model->date_create = date('Y-m-d H:i:s');
        $shop_model->date_update = date('Y-m-d H:i:s');
        $shop_model->date_to_update = $this->importService->getInterval($shop_model->date_update, $shop_model->update_frequency);
        if (!$shop_model->save()) {
            Yii::$app->session->setFlash('error', 'Не удалось сохранить магазин');
            return $this->redirect($post['save']);
        }
        /* сохранение характеристик */
        $group_model = new Group();
        $group_model->name = $shop_data['name'];
        $group_model->status = 1;
        //создание характеристики
        if ($group_model->save()) {
            $group_id = $group_model->id;
            $shop_group = new ShopGroup();
            $shop_group->shop_id = $shop_model->id;
            $shop_group->group_id = $group_id;
            if ($shop_group->save()) {
                if (!empty($characters_shop)) {
                    foreach ($characters_shop as $key => $value) {
                        $characteristic_model = new Characteristic();
                        $characteristic_model->group_id = $group_id;
                        $characteristic_model->name = $value;
                        $characteristic_model->type = 'text';
                        $characteristic_model->status = 1;
                        $characteristic_model->attribute = $this->filter->findAttribute($value);
                        if ($characteristic_model->save()) {
                            $characters_id[$characteristic_model->name] = $characteristic_model->id;
                        }
                    }
                }
            }
        }
        /* СОХРАНЕНИЕ ПАРАМЕТРОВ В ФАЙЛ */
        $index = $shop_model->id;
        /* создание папки для хранения даннных продукта магазина */
        $this->createShopProductsDataFolder($index);
        $absolutePath = Yii::getAlias('@backend') . '/modules/import/assets/shops/shop_' . $index;
        $this->importService->writeJson($main_fields, $absolutePath . '/main_fields.json');
        $this->importService->writeJson($post['Seo'], $absolutePath . '/seo_template.json');
        $this->importService->writeJson($additional_fields, $absolutePath . '/additional_fields.json');
        $this->importService->writeJson($characters_shop, $absolutePath . '/characters_shop.json');
        $this->importService->writeJson($category_prosto, $absolutePath . '/category_prosto.json');
        $this->importService->writeJson($post['categoryProsto'], $absolutePath . '/combine_category.json');
        $this->importService->writeJson($characters_id, $absolutePath . '/characters_id.json');
        $this->importService->writeJson([], $absolutePath . '/div_keys.json');

        /* ставим права на все содеожимое папки и саму папку рекурсивно */
        recursiveChmod($absolutePath, 0777, 0777);
        Yii::$app->session->setFlash('success', 'Добавлен новый магазин');
        return $this->redirect($post['save']);
    }

    public function actionEdit() {
        $post = Yii::$app->request->post();
        $id = $post['ParseShop']['id'];

        /* Обновляем магазин */
        $shop = ParseShop::findOne($id);
        $new_link = trim($shop->link) !== trim($post['ParseShop']['link']);
        $shop->update_frequency = $post['ParseShop']['update_frequency'];
        $shop->name = $post['ParseShop']['name'];
        $shop->link = $post['ParseShop']['link'];
        $shop->currency = $post['ParseShop']['currency'];
        $shop->currency_value = $post['ParseShop']['currency_value'];
        $shop->date_update = date('Y-m-d h:i:s');
        $shop->date_to_update = $this->importService->getInterval($shop->date_update, $shop->update_frequency);
        $shop->edit_process = ParseShop::IN_PROCESS;
        $shop->update();

        /* Обновляем характеристики */
        $shop_group = ShopGroup::findOne(['shop_id' => $shop->id]);
        $group_id = null;
        if ($shop_group !== null) {
            $group_id = $shop_group->group_id;
        } else {
            if (!empty($post['characteristic'])) {
                $group_model = new Group();
                $group_model->name = $shop->name;
                $group_model->status = 1;
                if ($group_model->save()) {
                    $group_id = $group_model->id;
                    $shop_group = new ShopGroup();
                    $shop_group->shop_id = $shop->id;
                    $shop_group->group_id = $group_id;
                    $shop_group->save();
                }
            }
        }

        $characters_id = $this->saveNewCharacteristic($post['characteristic'] ?? null, $group_id ?? null);

        /* СОХРАНЕНИЕ ПАРАМЕТРОВ В ФАЙЛ */
        $this->moveParams($shop->id);
        $absolutePath = Yii::getAlias('@backend') . '/modules/import/assets/shops/shop_' . $id;
        $this->importService->writeJson($post['requiredField'], $absolutePath . '/main_fields.json');
        $this->importService->writeJson($post['additionalField'], $absolutePath . '/additional_fields.json');
        $this->importService->writeJson((isset($post['characteristic'])) ? $post['characteristic'] : [], $absolutePath . '/characters_shop.json');
        $this->importService->writeJson($post['categoryProsto'], $absolutePath . '/category_prosto.json');
        $this->importService->writeJson($post['categoryProsto'], $absolutePath . '/combine_category.json');
        $characters_id_old = array_values($this->importService->readJson($absolutePath . '/characters_id.json'));
        $this->importService->writeJson($characters_id, $absolutePath . '/characters_id.json');
        $this->importService->writeJson($characters_id_old, $absolutePath . '/characters_id_old.json');
        
        //Пересмотреть
        //$this->importService->divideProducts($shop->id, $new_link);

        /* ставим права на все содеожимое папки и саму папку рекурсивно */
        recursiveChmod($absolutePath . '/characters_id_old.json', 0777, 0777);
        Yii::$app->session->setFlash('success', 'Магазин отредактирован.');
        return $this->redirect($post['save']);
    }

    /* Метод удаляет старые и сохраняет новые характеристики ,
     * возвращает id - новых характеристик или пустой массив */

    private function saveNewCharacteristic($characteristic, $group_id) {
        $arr = [];
        if ($characteristic && $group_id) {
            \Yii::$app->db->createCommand()->batchInsert(
                    'characteristic', ['group_id', 'name', 'type', 'status', 'attribute'], array_map(function($item) use ($group_id) {
                        return [
                            'group_id' => $group_id,
                            'name' => $item,
                            'type' => 'text',
                            'status' => 1,
                            'attribute' => $this->filter->findAttribute($item)
                        ];
                    }, $characteristic)
            )->execute();
            $new_chars = Characteristic::find()->where(['group_id' => $group_id])->asArray()->all();
            if ($new_chars) {
                foreach ($new_chars as $value) {
                    $arr[$value['name']] = $value['id'];
                }
            }
        }
        return $arr;
    }

    private function moveParam($absolutePath, $alias) {
        $file1 = Yii::$app->basePath . '/modules/import/assets/xml/' . $alias . '.json';
        $file2 = $absolutePath . '/' . $alias . '.json';
        $this->importService->copyData($file1, $file2);
        unlink($file1);
    }

    private function moveParams($shop_id) {
        $absolutePath = Yii::getAlias('@backend') . '/modules/import/assets/shops/shop_' . $shop_id;
        $this->moveParam($absolutePath, 'import_attr');
        $this->moveParam($absolutePath, 'import_tags');
    }

}
