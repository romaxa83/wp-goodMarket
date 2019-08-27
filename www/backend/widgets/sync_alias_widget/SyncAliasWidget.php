<?php

namespace backend\widgets\sync_alias_widget;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\data\ArrayDataProvider;
use backend\widgets\sync_alias_widget\SyncAliasWidgetAssets;

class SyncAliasWidget extends Widget {

    public $field_donor_name;
    public $field_recipient_name;
    public function init() {
        parent::init();
        Yii::setAlias('@sync-alias-assets', __DIR__ . '/assets');
        SyncAliasWidgetAssets::register(Yii::$app->view);
    }
    
    public function run() {
        return $this->render('_button',['donor_name'=>$this->field_donor_name, 'recipient_name'=>$this->field_recipient_name]);
    }

}
