<?php

namespace console\controllers;

use backend\modules\blog\entities\Post;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

class PostController extends Controller
{
    public function actionPublish()
    {
        $posts = ArrayHelper::map(Post::find()->select(['id','published_at'])->where(['status' => 2])->asArray()->all(),'id','published_at');

        if (!empty($posts)) {
            $pub = 0;
            $deferred = 0;
            foreach ($posts as $id => $val) {
                if ((int)$val <= (int)time()) {

                    $post = Post::find()->where(['id' => $id])->one();
                    $post->updateAttributes(['status' => 1]);
                    $pub++;
                } else {
                    $deferred++;
                }
            }
            echo 'Опубликовано - '. $pub .PHP_EOL . 'Ждут публикации - '. $deferred . PHP_EOL;
        } else {
            echo 'Отложенных статей нет'. PHP_EOL;
        }
    }
}
