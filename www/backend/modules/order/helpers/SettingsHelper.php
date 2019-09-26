<?php
namespace backend\modules\order\helpers;

use backend\modules\settings\models\Settings;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

class SettingsHelper
{
    public function getAllList($alias,$flag=null)
    {

        $this->isSettings($alias);

        $array = $this->getArray($this->getSettings($alias));
        if($flag){
            $array[0] = 'Неизвестно';
        }

        return $array;
    }

    public function getList($alias)
    {
        $this->isSettings($alias);

        return $this->getArrayWithoutDel($this->getSettings($alias));
    }

    private function isSettings($alias)
    {
        if($this->getSettings($alias) === null){
            throw new Exception('По алиасу "' . $alias .'" ,не найдено настроек!');
        }
    }

    private function getArrayWithoutDel($data)
    {
        return $this->keyId($this->filter($this->sortAndSerialize($data)));
    }

    private function getArray($data)
    {

        return $this->keyId($this->sortAndSerialize($data));
    }

    private function getSettings($alias)
    {
        return Settings::find()->where(['name' => $alias])->asArray()->one()['body'];
    }

    private function sortAndSerialize($data)
    {
        $arr = unserialize($data);
        usort($arr, function ($item1, $item2) {
            return $item1['position'] <=> $item2['position'];
        });

        return $arr;
    }

    private function keyId($array)
    {
        return array_combine(array_column($array,'id'),array_column($array,'name'));
    }

    private function filter($array)
    {
        return array_filter($array,function($e){
            return $e['status'];
        });
    }



}