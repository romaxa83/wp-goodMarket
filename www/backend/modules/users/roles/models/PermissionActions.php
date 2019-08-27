<?php

namespace backend\modules\users\roles\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Это класс модели для таблицы "auth_rule".
 *
 * @property string $name
 * @property resource $data
 * @property int $created_at
 * @property int $updated_at
 *
 * @property AuthItem[] $authItems
 */
class PermissionActions extends ActiveRecord
{

    public static function tableName()
    {
        return 'permission_actions';
    }

    public function rules()
    {
        return [
            
        ];
    }

    public static function insertRoutes($routes, $perm_name){
    	if(!empty($routes)){
    		Yii::$app->db->createCommand()
		        ->batchInsert(
		            'permission_actions',
		            ['perm_name', 'action'],
		             array_map(function($item) use ($perm_name){
		                $item = trim($item, '/');
		                $item = str_replace('none/', '', $item); 
		                return [
		                    'perm_name' => $perm_name,
		                    'action' => $item
		                ];
		            }, $routes)
		        )
		        ->execute();
    	}
    }
}
