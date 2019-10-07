<?php

namespace backend\modules\blog\widgets\blogWidget;

use Yii;
use yii\base\Widget;
use backend\modules\blog\widgets\blogWidget\BlogWidgetAssets;
use yii\helpers\ArrayHelper;

class BlogWidget extends Widget 
{
    public $mobile = false;

    public function init() 
    {
        parent::init();
        Yii::setAlias('@blogwidget-assets', __DIR__ . '/assets');
        BlogWidgetAssets::register(Yii::$app->view);
    }

    public function run() 
    {
        $aliasLang = Yii::$app->language;
        $postBlog = Yii::$app->db->createCommand("SELECT blog_post.id,blog_post.alias,blog_post.media_id,blog_post.status,blog_post.published_at,blog_post_lang.post_id,blog_post_lang.lang_id,blog_post_lang.title,blog_post_lang.description,filemanager_mediafile.id,filemanager_mediafile.url FROM blog_post LEFT JOIN blog_post_lang ON blog_post_lang.post_id = blog_post.id LEFT JOIN lang ON lang.id = blog_post_lang.lang_id LEFT JOIN filemanager_mediafile ON filemanager_mediafile.id = blog_post.media_id WHERE blog_post.status = 1 AND lang.alias = '{$aliasLang}' ORDER by blog_post.published_at DESC LIMIT 3")->queryAll();

        return $this->render('blog-section',['postBlog' => $postBlog]);
    }

}
