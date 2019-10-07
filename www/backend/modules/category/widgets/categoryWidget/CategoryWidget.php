<?php

namespace backend\modules\category\widgets\categoryWidget;

use Yii;
use yii\base\Widget;
use backend\modules\category\widgets\categoryWidget\CategoryWidgetAssets;
use backend\modules\category\models\Category;
use yii\helpers\ArrayHelper;

class CategoryWidget extends Widget 
{
    public $mobile = false;

    public function init() 
    {
        parent::init();
        Yii::setAlias('@categorywidget-assets', __DIR__ . '/assets');
        CategoryWidgetAssets::register(Yii::$app->view);
    }

    public function run() 
    {
        $aliasLang = Yii::$app->language;
        $category = Yii::$app->db->createCommand("SELECT category.id as row_id,category.alias as category_alias,category.parent_id,category.publish,category_lang.name,category_lang.category_id,category_lang.lang_id,lang.id,lang.alias FROM category LEFT JOIN category_lang ON category_lang.category_id = category.id LEFT JOIN lang ON lang.id = category_lang.lang_id WHERE category.publish = 1 AND lang.alias = '{$aliasLang}' ORDER by rating DESC LIMIT 12")->queryAll();
        
        $parentCategory = [];
        $childCategory = [];

        foreach($category as $oneCategory){
            if(empty($oneCategory['parent_id'])){
                $parentCategory[] = $oneCategory;
            }else{
                $childCategory[$oneCategory['parent_id']][] = $oneCategory;
            }
        }

        if($this->mobile){
            return $this->render('list-category-mob',[
                'parentCategory' => $parentCategory,
                'childCategory' => $childCategory
            ]);
        }else{
            return $this->render('list-category',[
                'parentCategory' => $parentCategory,
                'childCategory' => $childCategory
            ]);
        }
    }

}
