<?php

namespace backend\modules\content\components;

use backend\modules\content\models\Page;
use backend\modules\content\models\SlugManager;
use yii\base\BaseObject;
use yii\db\ActiveQuery;
use yii\web\UrlRuleInterface;

class PageRule extends BaseObject implements UrlRuleInterface {

    public $connectionID = 'db';
    public $name;

    public function init() {
        if ($this->name === null) {
            $this->name = __CLASS__;
        }
    }

    //Формирует ссылки в заданном виде (часть формируется в коде, остальное берется из БД)
    public function createUrl($manager, $route, $params) {
        $link = '';
        $template = '';
        $post_id = 0;

        if (count($params)) {
            $link = "?";
            foreach ($params as $key => $value) {
                if ($key == 'template') {
                    $template = $value;
                    continue;
                }
                if($key == 'post_id') {
                    $post_id = $value;
                    continue;
                }
                $link .= "$key=$value&";
            }
            $link = substr($link, 0, -1); //удаляем последний символ (&)
        }

        $full_link = '';
        $page = SlugManager::find()
            ->where([
                'and',
                [
                    'route' => $route,
                    'template' => $template,
                    'post_id' => $post_id
                ]
            ])
            ->one();
        if ($page) {
            $full_link = $page->slug;
            while($page->parent_id) {
                $page = SlugManager::find()
                    ->where(['id' => $page->parent_id])
                    ->one();
                if ($page) {
                    $full_link = $page->slug . '/' . $full_link;
                }
            }
        }

        if ($full_link == '' && $template != 'main') {
            return false;
        }
        return $full_link . $link;
    }

    //Разбирает входящий URL запрос, преобразует ссылки произвольного вида (из БД поле link_sef) в нужный для Yii2
    public function parseRequest($manager, $request) {
        //Получаем URL
        $pathInfo = $request->getPathInfo();
        $slugs = explode('/', $pathInfo);
        $count = count($slugs);
//        $page = false;
//        $childes = array();
//        for($i = $count; $i > 0; $i--)  {
//            $pages = SlugManager::find()->where(['slug' => $slugs[$i]])->asArray()->all();
//            if(count($pages) == 1 && $i == $count) {
//                $page = $pages[0];
//                break;
//            }
//            $level = array();
//            foreach($pages as $record) {
//                if(!empty($childes)) {
//                    if(isset($childes[$record->id])) {
//                        $level[] = $record;
//                    }
//                }
//                if($record->parent_id) {
//                    $childes[$record->parent_id] = SlugManager::find()->where(['id' => $record->parent_id])->all();
//                }
//            }
//
//        }

        $page = SlugManager::find()->where(['slug' => $slugs[$count - 1]])->one();

        if ($page) {
            $_GET['id'] = $page->id;

            $route = $page->route;
            $params = array(
                'template' => $page->template,
                'post_id' => $page->post_id,
            );

            return [$route, $params];
        }
        return false;
    }

}
