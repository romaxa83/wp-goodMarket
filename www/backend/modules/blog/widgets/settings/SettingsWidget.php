<?php

namespace backend\modules\blog\widgets\settings;

use yii\base\Widget;

class SettingsWidget extends Widget
{
    /*
     * св-во $attribute
     * принимает массив который отображаеться в настройках
     * и привязываеться к столбцам , где
     * ключ - это атрибут столбцы,а значение ,ыводиться в списке настроек
     * пример:
     * 'attribute' => [
     *      'id' => 'ID',
     *      'name' => Имя
     * ]
     */

    public $attribute = [];

    /*
     * св-во $hide_col
     * принимает массив уже установленных настроек
     */

    public $hide_col = [];

    /*
     * св-во $hide_col_status
     * отображает секцию для скрытия столбцов
     */

    public $hide_col_status = true;

    /*
     * св-во $count_page
     * принимает кол-во выводимых записей на странице
     */

    public $count_page;

    /*
     * св-во $model
     * принимает название сущьности,для сохранении в настройках
     * что бы потом идентифицировать настройки для конкретной сущьности
     */

    public $model;

    public function init()
    {
        parent::init();

        \Yii::setAlias('@settings-assets',  \Yii::getAlias('@backend').'/modules/blog/widgets/settings/assets');

        SettingsWidgetAsset::register(\Yii::$app->view);
    }

    public function run()
    {
        return $this->render('settings',[
            'attributes' => $this->attribute,
            'model' => $this->model,
            'hide_col' => $this->hide_col,
            'count_page' => $this->count_page,
            'hide_col_status' => $this->hide_col_status
        ]);
    }

    public static function setConfig($attribute,$arr_check,$another_config = null)
    {
        $config = [
            'data-attr' => $attribute,
            'style' => $arr_check !== null && in_array($attribute, $arr_check)?'display:none':''
        ];
        if($another_config){
            $config = array_merge($config,$another_config);
        }
        return $config;
    }
}

