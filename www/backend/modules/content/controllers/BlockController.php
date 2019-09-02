<?php

namespace backend\modules\content\controllers;

use backend\modules\content\models\ChannelContent;
use backend\modules\content\models\PageText;
//use backend\modules\user\useCase\Access;
use Yii;
use yii\base\Exception;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

class BlockController extends Controller {
    protected $access;

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);

//        $this->access = new Access();
    }

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]
        ];
    }
    
//    public function actions() {
//        return [
//            'error' => [
//                'class' => 'yii\web\ErrorAction',
//            ],
//        ];
//    }

    /**
     * Add content block for page
     *
     * @perm('Добавление нового блока на страницу (контент)')
     *
     * @return false|string
     * @throws Exception
     * @throws ForbiddenHttpException
     */
    public function actionAddBlock()
    {
//        $this->access->accessAction();

        if(!Yii::$app->request->isAjax) {
            throw new Exception('Allowed only by ajax');
        }
        $id = $this->getLastId();
        $template = Yii::$app->request->post('block-type');
        $group = Yii::$app->request->post('content-group');

        Yii::$app->assetManager->bundles = [
            'yii\bootstrap\BootstrapPluginAsset' => false,
            'yii\bootstrap\BootstrapAsset' => false,
            'yii\web\JqueryAsset' => false
        ];

        $json = array(
            'html' => $this->renderAjax('new-block', compact('id', 'group', 'template')),
            'type' => $template,
            'id' => $id
        );

        return json_encode($json);
    }

    private function getLastId() {
        $cache = YII::$app->cache;
        if ($cache->exists('block-id')) {
            $last_id = $cache->get('block-id');
        } else {
            if(Yii::$app->request->post('content-parent') == 'page-new-content') {
                $lastRow = PageText::find()->max('id');
            }
            else {
                $lastRow = ChannelContent::find()->max('id');
            }

            $last_id = !$lastRow ? 0 : (int) $lastRow;
        }
        $id = ++$last_id;
        $cache->set('block-id', $id);
        return $id;
    }

}
