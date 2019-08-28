<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\modules\product\models\CustomSerialColumn;
?>
<?php

if ($dataProvider->count != 0) {
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => [
            'id' => 'product-table',
            'class' => 'table table-striped table-bordered table-hover'
        ],
        'columns' => [
            [
                'class' => CustomSerialColumn::className(),
                'headerOptions' => ['width' => '1%']
            ],
            [
                'label' => 'Группа',
                'headerOptions' => ['width' => '1%'],
                'attribute' => 'id',
                'value' => function($model) {
                    return $model['group_name'];
                }
            ],
            [
                'label' => 'Название',
                'headerOptions' => ['width' => '1%'],
                'attribute' => 'id',
                'value' => function($model) {
                    return $model['characteristic_name'];
                }
            ],
            [
                'label' => 'Значение',
                'attribute' => 'id',
                'format' => 'raw',
                'value' => function($model) {
                    if ($model['characteristic_type'] == 'color') {
                        $model['product_characteristic_value'] = '<div style="width:22px;height:22px;background-color:' . $model['product_characteristic_value'] . ';" title="' . $model['product_characteristic_value'] . '"></div>';
                    }
                    return $model['product_characteristic_value'];
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Управление',
                'headerOptions' => ['width' => '1%'],
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function($url, $model, $index) {
                        return Html::tag(
                                        'a', '', [
                                    'title' => 'Редактировать характеристику',
                                    'aria-label' => 'Редактировать характеристику',
                                    'data-id' => $model['id'],
                                    'class' => 'grid-option fa fa-pencil edit-characteristic',
                                    'data-pjax' => '1'
                        ]);
                    },
                    'delete' => function($url, $model, $index) {
                        return Html::tag(
                                        'a', '', [
                                    'title' => 'Удалить характеристику',
                                    'aria-label' => 'Удалить характеристику',
                                    'data-id' => $model['id'],
                                    'class' => 'grid-option fa fa-trash delete-characteristic',
                                    'data-pjax' => '1'
                        ]);
                    }
                ]
            ],
        ]
    ]);
} else {
    echo 'Ничего не найдено';
}
?>


