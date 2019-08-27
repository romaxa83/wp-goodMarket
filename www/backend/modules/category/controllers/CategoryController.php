<?php

namespace backend\modules\category\controllers;

use backend\modules\category\models\CategoryLang;
use common\models\Lang;
use Yii;
use backend\modules\category\models\Category;
use backend\controllers\BaseController;
use backend\widgets\SeoWidget;
use common\controllers\AccessController;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
/**
 * MenuController реализует CRUD-систему для модели Menu.
 */
class CategoryController extends BaseController {

    public function behaviors()
    {
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

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if (!AccessController::checkPermission($action->controller->route)) {
                throw new ForbiddenHttpException('Вам не разрешено производить данное действие.');
            }
           return parent::beforeAction($action);
        } else {
            return false;
        }
    }

    public function actionIndex() {
        $category = Category::find()->select(['category.id', 'category_lang.name', 'category.parent_id', 'category.publish', 'category.publish_status'])->asArray()
            ->leftJoin('category_lang', 'category_lang.category_id = category.id')
            ->all();
        $category = self::addItem($category, 0);
        return $this->render('index', [
                    'category' => $category
        ]);
    }

    public function actionEdit() {
        $id = Yii::$app->request->get('id');
        $model = Category::find()->where(['id' => $id])->with('categoryLang')->with('categoryLang.lang')->one();

        $data['Language'] = [];
        foreach ($model->categoryLang as $v) {
            foreach ($v as $k1 => $v1) {
                $data['Language'][$v->lang->alias][$k1] = $v1;
            }
        }
        $model->languageData = $data;

        $lang_id = Lang::find()->select('id')->where(['alias' => Yii::$app->params['settings']['mainContentLanguage']])->one()->id;
        $parent_name = CategoryLang::find()->select('name')->where(['category_id' => $model->parent_id, 'lang_id' => $lang_id])->one();
        $parent_name = isset($parent_name->name) ? $parent_name->name : 'Нет';

        if ($post = Yii::$app->request->post()){
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->scenario = Category::SAVED_CATEGORY;
                $model->rating = $post['Category']['rating'];
                $model->media_id = $post['Category']['media_id'];
                $model->publish = $post['Category']['publish'];
                $model->alias = $post['Category']['alias'];

                if ($model->save() && CategoryLang::saveAll($model->id, $post['Category']['Language']) && SeoWidget::save($id, 'category', $post['SEO']) > 0) {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', 'Категория успешно отредактирована');
                    $this->redirect(['/category/category']);
                }
            } catch(\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch(\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
        return $this->render('form-category', [
            'model' => $model,
            'parent_name' => $parent_name
        ]);
    }

    public function actionUpdateStatus() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post();
            $category = Category::findOne($data['id']);
            $category->publish = (bool) $data['checked'];
            $category->update(FALSE);
        }
    }

    static function addItem($mas, $parent_id = 0) {
        $data = [];
            foreach ($mas as $k => $v) {
                if ($v['parent_id'] == $parent_id) {
                    $data[$k]['parent'] = $v;
                    $data[$k]['child'] = self::addItem($mas, $v['id']);
                }
            }
        return $data;
    }
}
