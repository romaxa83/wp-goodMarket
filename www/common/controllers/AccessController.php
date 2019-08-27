<?php

namespace common\controllers;
use Yii;
use yii\web\Controller;
use backend\modules\users\roles\models\PermissionActions;
use yii\helpers\ArrayHelper;

class AccessController extends Controller {

    public static function getAccessRules($controller) {
        $route = substr($controller->route, 0, strrpos($controller->route, '/'));
        $permisson_actions = PermissionActions::find()->where(['like', 'action', $route.'%', false])->asArray()->all();
        $permisson_actions = ArrayHelper::getColumn($permisson_actions, function($element){
            return [
                'permission' => $element['perm_name'],
                'route' => $element['action']
            ];
        });
        $permisson_actions_convert = self::convertPermAct($permisson_actions);
        $rules = [];
        $rules = self::getRules($permisson_actions_convert);
        array_push($rules, self::getAdditionalRules()); 
        $rules = array_filter($rules, function($element){
            return !empty($element);
        });
        return $rules;
    }

    private static function convertPermAct($permisson_actions){
        $convert_perm_act = [];
        foreach ($permisson_actions as $element) {
            $convert_perm_act[$element['permission']][] = substr($element['route'], strrpos($element['route'], '/')+1, strlen($element['route']));
        }
        return $convert_perm_act;
    }

    private static function getRules($permisson_actions){
        $rules = array_map(function($v, $k){
            if(!empty($k) && !empty($v)){
                return [
                    'allow' => true,
                    'roles' => [$k],
                    'actions' => $v
                ];
            }
        },$permisson_actions, array_keys($permisson_actions));
        return $rules;
    }

    public static function checkPermission($route){
        if(!self::isGod()){
            $route = strtok($route, '?');
            $route = trim($route, '/');
            $permisson_actions = PermissionActions::find()->where(['action' => $route])->asArray()->all();
            $permissions = array_column($permisson_actions, 'perm_name');
            foreach ($permissions as $perm_one) {
                if (Yii::$app->user->can($perm_one)) {
                    return true;
                }
            }
            return false;
        }
        return true;
    }

    public static function isView($controller, $action_name){
        $route = $controller->module->id.'/'.$controller->id.'/'.$action_name;
        if(!empty($route)){
            return self::checkPermission($route);
        }
        return false;
    }

    private static function getAdditionalRules(){
        return  [
                    'allow' => true,
                    'roles' => [Yii::$app->params['rbac']['god_role']]
                ];
    }

    private static function isGod(){
        $user_id = Yii::$app->user->id;
        $user_role = Yii::$app->authManager->getRolesByUser($user_id);
        $role_name = array_shift($user_role)->name;
        return ($role_name == Yii::$app->params['rbac']['god_role']);
    }

}