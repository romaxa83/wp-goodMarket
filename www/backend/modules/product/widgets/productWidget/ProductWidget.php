<?php

namespace backend\modules\product\widgets\productWidget;

use Yii;
use yii\base\Widget;
use backend\modules\product\widgets\productWidget\ProductWidgetAssets;

class ProductWidget extends Widget 
{
    public $typeSection;

    public function init() 
    {
        parent::init();
        Yii::setAlias('@productwidget-assets', __DIR__ . '/assets');
        ProductWidgetAssets::register(Yii::$app->view);
    }

    public function run() 
    {
        $file = __DIR__ . '/views/' . $this->typeSection . '.php';
        if (!file_exists($file)) {
            return 'Неверно задан параметр $typeSection';
        }

        if($this->typeSection === 'new'){
            $conditional = 'AND new = 1';
        }else{
            $conditional = 'ORDER by rating DESC';
        }

        $aliasLang = Yii::$app->language;
        $product = Yii::$app->db->createCommand(
            "SELECT product.id,product.publish,product.media_id,product_lang.product_id,product_lang.lang_id,product_lang.alias,product_lang.price,product_lang.name,filemanager_mediafile.url FROM product 
            LEFT JOIN product_lang ON product_lang.product_id = product.id 
            LEFT JOIN filemanager_mediafile ON product.media_id = filemanager_mediafile.id 
            LEFT JOIN lang ON product_lang.lang_id = lang.id 
            WHERE amount > 0 AND publish = 1 AND lang.alias = '{$aliasLang}' {$conditional}
            LIMIT 5"
        )->queryAll();
        
        if(!empty($product)){
            return $this->render($this->typeSection,['product' => $product]);
        }   
    }

}
