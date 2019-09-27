<?php


namespace backend\modules\content\controllers;


use backend\modules\content\models\ContentOptions;
use backend\modules\content\models\Page;
//use backend\modules\user\useCase\Access;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

class OptionsController extends Controller
{
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
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @perm('Редактирование опций (контент)')
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionIndex()
    {
//        $this->access->accessAction();

        if($settings = Yii::$app->request->post('options')) {
            foreach($settings as $id => $setting) {
                $option = ContentOptions::find()->where(['id' => $id])->one();
                $option = $option ? $option : new ContentOptions();
                $option->attributes = $setting;
                $option->id = $id;
                if($option->save()) {
                    Yii::$app->session->setFlash('success', "<p>Данные сохранены</p>");
                } else {
                    $errors = '';
                    foreach ($option->getErrors() as $fieldWithErrors) {
                        foreach ($fieldWithErrors as $error) {
                            $errors .= '<p>' . $error . '</p>';
                        }
                    }
                    Yii::$app->session->setFlash($errors);
                }
            }
        }

        $options = ContentOptions::find()->indexBy('name')->asArray()->all();
        $pages = Page::find()->asArray()->all();

        return $this->render('index', compact('options', 'pages'));
    }
}