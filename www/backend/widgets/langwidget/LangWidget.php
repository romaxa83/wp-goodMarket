<?php

namespace backend\widgets\langwidget;

use Yii;
use yii\base\Widget;
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
        return $this->render('langs-tab', [
                    'class' => StringHelper::basename($this->model->className()),
                    'model' => $this->model,
                    'fields' => $this->fields,
                    'languages' => self::getActiveLanguageData(['lang', 'alias'])
        ]);
    }

    static function getActiveLanguageData($param) {
        $data = [];
        $languages = Lang::find()->select(['id', 'status', 'name as lang', 'alias'])->where(['status' => 1])->orderBy(['priority' => SORT_ASC])->asArray()->all();
        foreach ($languages as $k => $v) {
            if ($v['status'] == 1) {
                foreach ($param as $item) {
                    $data[$k][$item] = $v[$item];
                }
            }
        }
        return $data;
    }

    static function validate($model, $data) {
        $rules = $model->rules();
        $class = StringHelper::basename($model->className());
        foreach ($data[$class] as $k => $v) {
            foreach ($v as $k0 => $v0) {
                foreach ($rules as $rule) {
                    $action = $rule[1];
                    $attr = strtolower($class) . '-' . $k0 . '-' . $k;
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
                $model->addError($attr, $params['message'] . ' «' . $name . '».');
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
