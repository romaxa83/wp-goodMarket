<?php

namespace backend\widgets\langwidget;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use backend\modules\settings\models\Settings;
use backend\widgets\langwidget\LangWidgetAsset;
use yii\helpers\StringHelper;
use common\models\Lang;

class LangWidget extends Widget {

    public $model;
    public $fields;

    public function init() {
        parent::init();
        Yii::setAlias('@langwidget-assets', __DIR__ . '/assets');
        LangWidgetAsset::register(Yii::$app->view);
    }

    public function run() {
        $class = StringHelper::basename($this->model->className());
        return $this->render('langs-tab', [
                    'class' => $class,
                    'model' => $this->model,
                    'fields' => $this->fields,
                    'languages' => self::getActiveLanguageData(['lang', 'alias'])
        ]);
    }

    static function getActiveLanguageData($param) {
        $data = [];
        $languages = Lang::find()->select(['status', 'name as lang', 'alias','id'])->where(['status' => 1])->orderBy(['priority' => SORT_ASC])->asArray()->all();
        foreach ($languages as $k => $v) {
            if ($v['status'] == 1) {
                foreach ($param as $item) {
                    $data[$k][$item] = $v[$item];
                }
            }
        }
        return $data;
    }

    static function validate($model) {
        $rules = $model->rules();
        $class = strtolower(StringHelper::basename($model->className()));
        $data = Yii::$app->request->post()[StringHelper::basename($model->className())]['Language'];
        foreach ($data as $k => $v) {
            foreach ($v as $k0 => $v0) {
                foreach ($rules as $rule) {
                    $action = $rule[1];
                    $attr = $class . '-' . $k0 . '-' . $k;
                    if (is_array($rule[0]) && (array_search($k0, $rule[0]) !== FALSE))
                        if (method_exists(self::className(), $action))
                            self::$action($model, $attr, $v0, $k0, $rule);
                    if (!is_array($rule[0]) && ($rule[0] == $k0))
                        if (method_exists(self::className(), $action))
                            self::$action($model, $attr, $v0, $k0, $rule);
                }
            }
        }
        return !$model->hasErrors();
    }

    private static function number($model, $attr, $value, $name, array $params = []) {
        if (!is_numeric($value)) {
            if (!array_key_exists('on', $params)) {
                $params['on'] = 'default';
            }
            if ($params['on'] == $model->scenario) {
                $name = $model->getAttributeLabel($name);
                $model->addError($attr, $params['message']);
            }
        }
    }

    private static function required($model, $attr, $value, $name, array $params = []) {
        if (empty($value)) {
            if (!array_key_exists('on', $params)) {
                $params['on'] = 'default';
            }
            if ($params['on'] == $model->scenario) {
                $name = $model->getAttributeLabel($name);
                $model->addError($attr, $params['message'] . ' ' . $name);
            }
        }
    }

    private static function unique($model, $attr, $value, $name, array $params = []) {
        $class_name = $model->className();
        $data = $class_name::find()->where([$name => $value])->one();
        if (isset($data)) {
            if (!array_key_exists('on', $params)) {
                $params['on'] = 'default';
            }
            if ($params['on'] == $model->scenario) {
                $name = $model->getAttributeLabel($name);
                $model->addError($attr, $params['message']);
            }
        }
    }

    private static function match($model, $attr, $value, $name, array $params = []) {
        $class_name = $model->className();
        if (!preg_match($params['pattern'], $value)) {
            if (!array_key_exists('on', $params)) {
                $params['on'] = 'default';
            }
            if ($params['on'] == $model->scenario) {
                $name = $model->getAttributeLabel($name);
                $model->addError($attr, $params['message']);
            }
        }
    }

}
