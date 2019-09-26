<?php
namespace backend\modules\order\models;

use yii\db\ActiveRecord;

class HistoryStatusOrder extends ActiveRecord 
{
    public static function tableName()
    {
        return 'history_status_order';
    }
    
    public function rules() {
        return [
            [['status', 'order_id'], 'required'],
        ];
    }
}
