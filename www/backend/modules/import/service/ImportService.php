<?php

namespace backend\modules\import\service;

use Yii;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

class ImportService {

    const PRICE_PATTERN = '/^(0|[1-9]\d*)(\.\d*)?$/';

    /*
     * валидация на тип данных 
     */

    public function checkType($var, $type) {
        switch ($type) {
            case 'string': return is_string($var);
            case 'string_not_empty': return (is_string($var) && !empty($var));
            case 'key': return ((is_string($var) || empty($var) || is_numeric($var)) && !strpos($var, 0x20));
            case 'integer': return (filter_var($var, FILTER_VALIDATE_INT) != false && is_numeric($var)) ? true : false;
            case 'boolean': return (filter_var($var, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) !== null) ? true : false;
            case 'url': return (filter_var($var, FILTER_VALIDATE_URL) != false) ? true : false;
            case 'price': return (preg_match(self::PRICE_PATTERN, $var)) ? true : false;
        }
        return true;
    }

    /*
     * рекурсивное преобразования вложенного массива в строчку 
     */

    public function getCategoryKeysRecursive(array $category) {
        $data = [];
        foreach ($category as $k => $v) {
            $data[] = $k;
            if (!empty($v['child'])) {
                $data[] = $this->getCategoryKeysRecursive($v['child']);
            }
        }
        return implode(',', $data);
    }

    /*
     * разбитие строки category_id на массив 
     */

    public function getCategoryKeys(array $category) {
        return explode(',', $this->getCategoryKeysRecursive($category));
    }

    /*
     * Записует данные в json файл
     */

    public function writeJson(array $value, $path) {
        $data_json = Json::encode($value);
        $fp = fopen($path, "w");
        file_put_contents($path, $data_json);
        fclose($fp);
    }

    /*
     * Достаёт данные с json файл
     */

    public function readJson($path) {
        if (file_exists($path)) {
            $fp = fopen($path, 'r');
            $result = Json::decode(file_get_contents($path));
            fclose($fp);
            return $result;
        } else {
            throw new \DomainException('По данному пути ( ' . $path . ' ) файл отсутствует.');
        }
    }

    /*
     * Запись логов 
     */

    public function writeLogs($text, $nameFile = null) {
        $baseLogPath = Yii::getAlias('@backend') . '/modules/import/assets/logs/';
        $logPath = ($nameFile !== null) ? $baseLogPath . $nameFile : $baseLogPath . 'log.txt';
        if (!file_exists($baseLogPath)) {
            mkdir($baseLogPath, 0777, true);
        }
        $fp = fopen($logPath, "a+");
        file_put_contents($logPath, date('Y-m-d H:i:s') . ': ' . $text . PHP_EOL, FILE_APPEND);
        fclose($fp);
        chmod($logPath, 0777);
    }

    /*
     * копирования данных 
     */

    public function copyData($file_origin, $file_copy) {
        if (file_exists($file_origin)) {
            copy($file_origin, $file_copy);
        } else {
            throw new \DomainException('По данному пути ( ' . $file_origin . ' ) файл отсутствует.');
        }
    }

    /*
     * получения настроек импорта
     */

    public function getConnectionData($index) {
        $absolutePath = Yii::getAlias('@backend') . '/modules/import/assets/shops/shop_' . $index;
        $connect['seo_template'] = $this->readJson($absolutePath . '/seo_template.json');
        $connect['main_fields'] = $this->readJson($absolutePath . '/main_fields.json');
        $connect['additional_fields'] = $this->readJson($absolutePath . '/additional_fields.json');
        $connect['combine_category'] = $this->readJson($absolutePath . '/combine_category.json');
        $connect['characters_id'] = $this->readJson($absolutePath . '/characters_id.json');
        $connect['characters_shop'] = $this->readJson($absolutePath . '/characters_shop.json');
        $connect['category_prosto'] = $this->readJson($absolutePath . '/category_prosto.json');
        return $connect;
    }

    /*
     * получения следущей даты обновления   
     */

    public function getInterval($date, $update_frequency) {
        $timeToUpdate = new \DateTime($date);
        if ($update_frequency == 0) {
            $interval = 'PT1H';
        }
        if ($update_frequency == 1) {
            $interval = 'P1D';
        }
        if ($update_frequency == 2) {
            $interval = 'P1W';
        }
        if ($update_frequency == 3) {
            $interval = 'P1M';
        }
        $timeToUpdate->add(new \DateInterval($interval));
        return $timeToUpdate->format('Y-m-d H:i:s');
    }

    /*
     * Метод делит продукты на 3 типа: добавленные, удаленные и существующие 
     */

    public function divideProducts($shop_id, $new_link = false) {
        $products_cache = Yii::$app->cache->get('products_shop_' . $shop_id);
        $path = Yii::getAlias('@backend') . '/modules/import/assets/shops/shop_' . $shop_id;
        $tags = $this->readJson($path . '/import_tags.json');
        $attr = $this->readJson($path . '/import_attr.json');
        $prod_xml_keys = ArrayHelper::getColumn(array_filter(array_intersect_key($attr, array_flip($tags['offer'])), function($element) {
                            return isset($element['attributes']['id']);
                        }), function($element) {
                    return $element['attributes']['id'];
                });
        $prod_cache_keys = array_keys($products_cache);
        $products_add_keys = (!$new_link) ? array_diff($prod_xml_keys, $prod_cache_keys) : $prod_xml_keys;
        $products_deleted_keys = (!$new_link) ? array_diff($prod_cache_keys, $prod_xml_keys) : [];
        $products_exists_keys = (!$new_link) ? array_intersect($prod_cache_keys, $prod_xml_keys) : [];
        $div_prod_keys = [
            'add' => $products_add_keys,
            'del' => $products_deleted_keys,
            'exists' => $products_exists_keys
        ];
        $this->writeJson($div_prod_keys, $path . '/div_keys.json');
    }

    /*
     * возвращает истинна или нет 
     */

    public function isTrue($var) {
        return filter_var($var, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }

    /*
     *  возвращает публиковать или нет 
     */

    public function isPublish(array $product_xml, int $category_status = 0) {
        $def_fields_value = Yii::$app->params['import_settings']['def_fields_value'];
        foreach ($def_fields_value as $key => $value) {
            if ($product_xml[$key] == $value) {
                return false;
            }
        }
        return (($category_status === 1) && $this->isTrue($product_xml['available']));
    }

    /*
     *  создает блок файл для определенного процесса 
     */

    public function lockProcess($process_name) {
        $pathLockFile = Yii::getAlias('@console') . '/runtime/' . $process_name;
        touch($pathLockFile);
        chmod($pathLockFile, 0777);
    }

    /*
     *  удаляет блок файл для определенного процесса 
     */

    public function unlockProcess($process_name) {
        if (file_exists(dirname(dirname(__DIR__)) . '/console/runtime/' . $process_name)) {
            unlink(dirname(dirname(__DIR__)) . '/console/runtime/' . $process_name);
        }
    }

    /*
     *  проверка блока для определенного процесса 
     */

    public function ChecklockProcess($process_name) {
        if (file_exists(dirname(dirname(__DIR__)) . '/console/runtime/' . $process_name)) {
            return true;
        } else {
            return false;
        }
    }

}
