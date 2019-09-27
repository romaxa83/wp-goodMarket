<?php
    use app\modules\order\OrderAsset;
    use yii\widgets\DetailView;

    OrderAsset::register($this);
?>

<?= DetailView::widget([
    'model' => $product,
    'attributes' => [
    	[
    		'label' => 'Артикул',
    		'attribute' => 'vendor_code'
    	],
    	[
    		'label' => 'Имя продукта',
    		'attribute' => 'product_name'
    	],
        [
    		'label' => 'Имя категории',
    		'attribute' => 'category_name'
    	],
        
        [
        	'label' => 'Вариация',
        	'attribute' => 'variation',
        	'value' => function($model){
        		return (!empty($model['variation'])?$model['variation']:'Нету');
        	}
        ],
        [
        	'label' => 'Ед. измерения',
        	'attribute' => 'unit',
        	'value' => function($model){
        		return $model['unit'] ?? 'Нету';
        	}
        ],
        [
    		'label' => 'Цена',
    		'attribute' => 'price',
    	],
        [
    		'label' => 'Количество',
    		'attribute' => 'order_amount',
    	],
        [
        	'label' => 'Скидка (%)',
        	'attribute' => 'sale'
        ],
        [
        	'label' => 'Сумма',
        	'attribute' => 'summ'
        ],
        [
        	'label' => 'Цена со скидкой',
        	'attribute' => 'sale_summ'
        ]
    ],
]) ?>