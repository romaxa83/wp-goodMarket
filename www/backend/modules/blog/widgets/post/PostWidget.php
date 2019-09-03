<?php

namespace backend\modules\blog\widgets\post;

use backend\modules\blog\entities\Post;
use backend\modules\blog\repository\PostRepository;
use yii\base\Widget;

class PostWidget extends Widget
{
    public $template;

    private $data;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $view = $this->template;

        switch ($this->template) {
            case 'popular':
                $this->data = $this->getPostMainPaige();
                break;

            default:
                throw new \DomainException('Неверно указан template,либо его не существует');
        }

        return $this->render($view,[
            'data' => $this->data
        ]);
    }

    private function getPostMainPaige()
    {
        $posts = Post::find()->where(['is_main' => Post::ON_MAIN_PAGE])->orderBy(['position' => SORT_ASC])->all();
        if(count($posts) == 4){
            return $posts;
        }
        return false;
    }

}
