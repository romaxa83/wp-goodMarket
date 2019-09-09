<?php

use backend\modules\product\ProductAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\widgets\langwidget\LangWidget;
use yii\bootstrap\Modal;
use backend\widgets\SeoWidget;
use backend\modules\filemanager\widgets\FileInput;
use kartik\select2\Select2;
use kartik\color\ColorInput;
use yii\grid\GridView;
use backend\modules\product\models\CustomSerialColumn;
use backend\modules\product\models\CustomActionColumn;
use common\controllers\AccessController;

ProductAsset::register($this);
?>
<?php
Modal::begin([
    'id' => 'product-gallery',
    'size' => Modal::SIZE_LARGE,
    'header' => 'Галерея',
]);
Modal::end();
?>
<?php $form = ActiveForm::begin(['id' => 'form-product', 'method' => 'POST', 'options' => ['data-product-id' => $product['id']]]); ?>
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <?php foreach ($tabs as $key => $tab): ?>
            <li class="<?php echo ($key == 0) ? 'active' : '' ?>">
                <a href="<?php echo '#' . $tab['href']; ?>" data-toggle="tab" aria-expanded="<?php echo ($key == 0) ? TRUE : FALSE ?>"><?php echo $tab['name']; ?></a>
            </li>
        <?php endforeach; ?>
        <?php if (Yii::$app->controller->action->id === 'create'): ?>
            <li class="pull-right pt-5">
                <div class="pull-right-submit">
                    <?php echo Html::submitButton('<i class="fa fa-puzzle-piece" aria-hidden="true"></i>', ['title' => 'Добавить характеристики', 'class' => 'btn btn-primary', 'name' => 'update', 'value' => 'tab_5']) ?>
                    <?php //echo Html::submitButton('<i class="fa fa-file" aria-hidden="true"></i>', ['title' => 'Добавить атрибуты', 'class' => 'btn btn-primary', 'name' => 'update', 'value' => 'tab_6']) ?>
                </div>
            </li>
        <?php endif; ?>
    </ul>
    <div class="tab-content">
        <?php if (isset($provider)): ?>
            <div class="tab-pane <?php echo (Yii::$app->controller->action->id === 'update') ? 'active' : '' ?>" id="tab_1">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <?php foreach ($provider as $key => $item) : ?>
                            <li class="<?php echo ($key == 0) ? 'active' : '' ?>" >
                                <a href="<?php echo '#provider_' . $key ?>" data-toggle="tab" aria-expanded="false"><?php echo $item->type; ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="tab-content">
                        <?php foreach ($provider as $key => $item) : ?>
                            <div class="<?php echo ($key == 0) ? 'tab-pane active' : 'tab-pane'; ?>" id="<?php echo 'provider_' . $key ?>">
                                <?php echo $this->render('form_agent', ['item' => $item, 'category' => $category]); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="tab-pane <?php echo (Yii::$app->controller->action->id === 'create') ? 'active' : '' ?>" id="tab_2">
            <?php
            echo $form->field($model, 'vendor_code')->textInput(['readonly' => 'readonly']);
            if (Yii::$app->controller->action->id === 'create') {
                echo $form->field($model, 'category_id')->widget(Select2::classname(), [
                    'data' => $category,
                    'language' => 'ru',
                    'options' => ['placeholder' => 'Выберите категорию'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
            }
            echo LangWidget::widget(['model' => $modelLang, 'fields' => [
                    ['type' => 'text', 'name' => 'alias'],
                    ['type' => 'text', 'name' => 'name'],
                    ['type' => 'number', 'name' => 'price'],
                    ['type' => 'widget', 'name' => 'currency', 'class' => 'kartik\select2\Select2', 'options' => [
                            'data' => ['uah' => 'Гривна', 'usd' => 'Доллар'],
                            'language' => 'ru',
                            'options' => ['placeholder' => 'Выберите валюту'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]],
                    ['type' => 'widget', 'name' => 'description', 'class' => 'vova07\imperavi\Widget', 'options' => [
                            'settings' => [
                                'lang' => 'ru',
                                'minHeight' => 200,
                                'imageUpload' => Url::to(['image-upload']),
                                'imageDelete' => Url::to(['file-delete']),
                                'imageManagerJson' => Url::to(['images-get']),
                                'plugins' => [
                                    'clips',
                                    'fullscreen',
                                ],
                            ],
                            'plugins' => [
                                'imagemanager' => 'vova07\imperavi\bundles\ImageManagerAsset',
                            ]
                        ]
                    ]
            ]]);
            if (Yii::$app->controller->action->id === 'create') {
                echo $form->field($model, 'amount')->textInput(['type' => 'number']);
            }
            echo $form->field($model, 'rating')->textInput(['type' => 'number', 'min' => 0, 'max' => 100, 'step' => 1]);
            ?>
            <div class="manufacturer-position-relative">
                <?php
                echo $form->field($model, 'manufacturer_id')->widget(Select2::classname(), [
                    'data' => $manufacturer,
                    'language' => 'ru',
                    'options' => ['placeholder' => 'Выберите производителя', 'id' => 'manufacturer', 'class' => 'crud'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                ?>
                <button class="btn btn-default manufacturer-delete"><i class="fa fa-trash" aria-hidden="true"></i></button>
                <button class="btn btn-default manufacturer-edit"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                <button class="btn btn-default manufacturer"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
            </div>
            <?php
            echo $form->field($model, 'publish')->inline()->radioList([1 => ' Да', 0 => ' Нет'], [
                'item' => function($index, $label, $name, $checked, $value) {
                    $check = $checked ? ' checked="checked"' : '';
                    $return = '<label class="mr-15">';
                    $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" ' . $check . ' class="custom-radio">';
                    $return .= '<span>' . ucwords($label) . '</span>';
                    $return .= '</label>';
                    return $return;
                }
            ]);
            ?>
        </div>
        <div class="tab-pane" id="tab_3">
            <div class="gallery-box gallery-product">
                <div class="gallery-box-panel">
                    <?php
                    echo $form->field($model, 'gallery')->widget(FileInput::className(), [
                        'buttonTag' => 'button',
                        'buttonName' => '<i class="fa fa-plus-circle" aria-hidden="true"></i>',
                        'buttonOptions' => ['class' => 'btn btn-default'],
                        'options' => ['class' => 'form-control', 'data-value' => $model->media_id],
                        'template' => '<div class="input-group" style="float: right;">{input}<span class="input-btn">{button}</span></div>',
                        'thumb' => 'original',
                        'imageContainer' => '.img',
                        'pasteData' => FileInput::DATA_URL,
                        'callbackBeforeInsert' => 'function(e, data) {addItemInGallery(data);}',
                        'defaultTag' => 'product',
                    ]);
                    ?>
                </div>
                <div class="gallery-box-content clearfix" id="sortable" data-id="<?php echo $model->id; ?>"></div>
                <input type="hidden" name="Product[gallery_serialize]" id="gallery-item-serialize" value="<?php echo htmlspecialchars($model->gallery); ?>">
                <input type="hidden" name="Product[prod_rating]" value="">
            </div>
        </div>
        <div class="tab-pane" id="tab_4">
            <?php echo SeoWidget::widget(['id' => $model->id]); ?>
        </div>
        <div class="tab-pane" id="tab_5">
            <div class="row">
                <div class="col-md-4">
                    <div class="crud-box">
                        <?php
                        echo $form->field($model, 'group_id')->widget(Select2::classname(), [
                            'data' => $group,
                            'language' => 'ru',
                            'options' => ['placeholder' => 'Выберите группу', 'class' => 'crud'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        ?>
                        <button class="btn btn-default crud-box-delete" data-id="product-group_id" data-url="<?php echo Url::to('/admin/product/product/ajax-delete-product-group', TRUE); ?>" ><i class="fa fa-trash" aria-hidden="true"></i></button>
                        <button class="btn btn-default crud-box-edit" data-title="Редактирование группы" data-modal="product-group-modal" data-action="update"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                        <button class="btn btn-default crud-box-add" data-title="Добавление группы" data-modal="product-group-modal" data-action="create"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                    </div>
                </div>
                <div class="col-md-4 hidden">
                    <div class="crud-box">
                        <?php
                        echo $form->field($characteristic, 'name')->widget(Select2::classname(), [
                            'data' => $characteristic_list,
                            'language' => 'ru',
                            'options' => ['placeholder' => 'Выберите характеристику', 'class' => 'crud'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        ?>
                        <button class="btn btn-default crud-box-delete" data-id="characteristic-name" data-url="<?php echo Url::to('/admin/product/product/ajax-delete-product-characteristic', TRUE); ?>" ><i class="fa fa-trash" aria-hidden="true"></i></button>
                        <button class="btn btn-default crud-box-edit" data-title="Редактирование характеристики" data-modal="product-characteristic-modal" data-action="update"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                        <button class="btn btn-default crud-box-add" data-title="Добавление характеристики" data-modal="product-characteristic-modal" data-action="create"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="type-item type-text hidden">
                        <?php echo $form->field($product_characteristic, 'value', ['inputTemplate' => '<div class="form-group">{input}<button type="button" class=" pull-right btn btn-default save-characteristic" data-edit="0" title="Добавить характеристику"><i class="fa fa fa-floppy-o" aria-hidden="true"></i></button></div>']); ?>
                    </div>
                    <div class="type-item type-color hidden">
                        <?php echo $form->field($product_characteristic, 'value', ['inputTemplate' => '{input}<button type="button" class=" pull-right btn btn-default save-characteristic" data-edit="0" title="Добавить характеристику"><i class="fa fa fa-floppy-o" aria-hidden="true"></i></button>'])->widget(ColorInput::classname(), ['options' => ['placeholder' => 'Select color ...', 'readonly' => 'readonly']]); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <label>Характеристики</label>
                </div>
            </div>
            <div id="product-characteristic"></div>
        </div>
        <div class="tab-pane" id="tab_6">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <?php
                        echo '<label class="control-label">Характеристика</label>';
                        echo Select2::widget([
                            'id' => 'atribute_characteristic',
                            'name' => 'Atribute[characteristic]',
                            'data' => [],
                            'language' => 'ru',
                            'options' => ['placeholder' => 'Выберите характеристику'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                        ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group attr-type attr-color hidden">
                        <?php
                        echo '<label class="control-label">Цвет</label>';
                        echo ColorInput::widget([
                            'name' => 'Atribute[color]',
                            'attribute' => 'saturation',
                            'options' => ['placeholder' => 'Выберите цвет'],
                            'options' => ['readonly' => true]
                        ]);
                        ?>
                    </div>
                    <div class="form-group attr-type attr-text hidden">
                        <label class="control-label">Значение</label>
                        <input type="text" name="Atribute[value]" class="form-control">
                        <p class="help-block help-block-error"></p>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group form-field hidden">
                        <label class="control-label">Цена</label>
                        <input type="number" name="Atribute[price]" class="form-control">
                        <p class="help-block help-block-error"></p>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group form-field hidden">
                        <label class="control-label">Количество</label>
                        <input type="number" name="Atribute[amount]" class="form-control">
                        <p class="help-block help-block-error"></p>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group form-field hidden">
                        <label class="control-label">Изображение</label>
                        <div>
                            <?php
                            echo Html::button('<i class="fa fa-plus" aria-hidden="true"></i>', [
                                'title' => 'Добавить изображение',
                                'class' => 'btn btn-primary grid-option product-gallery-window',
                                'data-toggle' => \Yii::t('yii', 'modal'),
                                'data-target' => \Yii::t('yii', '#product-gallery'),
                                'data-product_id' => $id,
                                'data-media' => $model["media_id"],
                                'data-id' => $model['id']
                            ]) . '<span class="media_id"> ' . $model["media_id"] . '<span>';
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group form-field hidden">
                        <label class="control-label"></label>
                        <div>
                            <button type="button" class="btn btn-default" data-edit="0" title="Сохранить атрибут">
                                <i class="fa fa fa-floppy-o" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => [
                    'id' => 'product-table1',
                    'class' => 'table table-striped table-bordered table-hover',
                ],
                'columns' => [
                    [
                        'class' => CustomSerialColumn::className(),
                        'headerOptions' => ['width' => '1%']
                    ],
                    [
                        'headerOptions' => ['width' => '1%'],
                        'attribute' => 'id',
                        'value' => function ($model) {
                            return $model['id'];
                        }
                    ],
                    [
                        'headerOptions' => ['width' => '92%'],
                        'attribute' => 'char_value',
                        'value' => function ($model) {
                            return $model['char_value'];
                        }
                    ],
                    [
                        'headerOptions' => ['width' => '10%'],
                        'attribute' => 'amount',
                        'value' => function ($model) {
                            return $model->amount;
                        }
                    ],
                    [
                        'headerOptions' => ['width' => '10%'],
                        'contentOptions' => function ($model) use ($id) {
                            return ['class' => 'life-edit-price', 'data-stock-id' => $model['id'], 'data-product-id' => $id];
                        },
                        'attribute' => 'price',
                        'value' => function ($model) {
                            return number_format($model['price'], 2, '.', '');
                        }
                    ],
                    [
                        'label' => 'Скидка (%)',
                        'headerOptions' => ['width' => '1%'],
                        'attribute' => 'sale',
                        'value' => function ($model) {
                            return 0;
                        },
                    ],
                    [
                        'label' => 'Цена со скидкой',
                        'headerOptions' => ['width' => '1%'],
                        'attribute' => 'sale_price',
                        'value' => function ($model) {
                            return 0;
                        }
                    ],
                    [
                        'headerOptions' => ['width' => '1%'],
                        'attribute' => 'media_id',
                        'format' => 'raw',
                        'value' => function ($model) use ($id) {
                            return Html::tag(
                                            'a', '', [
                                        'href' => '#',
                                        'title' => 'Добавить превью',
                                        'aria-label' => 'Добавить превью',
                                        'style' => 'color:rgb(63,140,187)',
                                        'class' => 'grid-option fa fa-plus-circle product-gallery-window',
                                        'data-toggle' => \Yii::t('yii', 'modal'),
                                        'data-target' => \Yii::t('yii', '#product-gallery'),
                                        'data-product_id' => $id,
                                        'data-media' => $model["media_id"],
                                        'data-id' => $model['id'],
                                        'data-pjax' => '1'
                                    ]) . '<span class="media_id">' . $model["media_id"] . '<span>';
                        }
                    ],
                    [
                        'label' => 'Статус',
                        'headerOptions' => ['width' => '1%'],
                        'format' => 'raw',
                        'attribute' => 'publish',
                        'filter' => [0 => 'Выкл.', 1 => 'Вкл.'],
                        'value' => function ($model) use ($id) {
                            $access = AccessController::isView(Yii::$app->controller, 'ajax-save-v-product');
                            $checked = ($model['publish'] == 1) ? 'true' : '';
                            $options = [
                                'id' => 'cd_' . $model['id'],
                                'class' => 'tgl tgl-light publish-toggle status-toggle-v-product',
                                'data-product_id' => $id,
                                'data-char-value' => $model['char_value'],
                                'data-stock_id' => $model['id'],
                                'data-url' => Url::to(['ajax-update-v-product-status']),
                                'disabled' => !$access
                            ];
                            return Html::beginTag('div') .
                                    Html::checkbox('status', $checked, $options) .
                                    Html::label('', 'cd_' . $model['id'], ['class' => 'tgl-btn']) .
                                    Html::endTag('div');
                        }
                    ],
                    [
                        'class' => CustomActionColumn::className(),
                        'header' => Html::button('<i class="fa fa fa-plus"></i>', ['title' => 'Добавить атрибут', 'class' => 'btn btn-primary add-atribute', 'style' => 'cursor: pointer']),
                        'headerOptions' => ['width' => '1'],
                        'template' => '',
                        'buttons' => [
                        ]
                    ],
                ]
            ]);
            ?>
        </div>
        <div class="tab-pane" id="tab_7">
            <div class="table-responsive-md">
                <?php
                if (isset($stockDataProvider)) {
                    echo GridView::widget([
                        'dataProvider' => $stockDataProvider,
                        'tableOptions' => [
                            'id' => 'sock-list',
                            'class' => 'table table-hover'
                        ],
                        'columns' => [
                            [
                                'attribute' => 'title',
                                'label' => 'Название акции',
                                'format' => 'html',
                                'value' => function ($model) {
                                    if ($model['type'] == 0) {
                                        $url = ['/stock/stock/show-stock-products?id=' . $model['stock_id'] . '&sale_product_id=' . $model['sp_id']];
                                    } else {
                                        $url = ['/stock/stock/edit-stock?id=' . $model['stock_id']];
                                    }
                                    return Html::tag(
                                                    'a', $model['title'], [
                                                'href' => Url::to($url),
                                                'style' => 'color:rgb(63,140,187)',
                                                'class' => '',
                                                'data-pjax' => '0',
                                    ]);
                                }
                            ],
                            [
                                'attribute' => 'var',
                                'label' => 'Вариация'
                            ],
                            [
                                'attribute' => 'sale',
                                'label' => 'Скидка (%)'
                            ],
                            [
                                'attribute' => 'sale_price',
                                'label' => 'Цена со скидкой',
                            ],
                        ],
                    ]);
                }
                ?>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <?php echo Html::submitButton('Сохранить и выйти в список', ['class' => 'btn btn-primary', 'name' => 'save', 'value' => '/product/product']) ?>
    <?php echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'save', 'value' => (Yii::$app->controller->action->id === 'update') ? '/product/product/update?id=' . $id : '/product/product/create']) ?>
    <?php if (Yii::$app->controller->action->id === 'update' && $model->publish == 1): ?>
        <a href="<?php echo $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER["HTTP_HOST"] . '/product/' . $modelLang->alias ?>" target="_blank" class="btn btn-primary">Перейти в карточку товара</a>
    <?php endif ?>
    <a href="<?php echo Url::to(['/product/product']) ?>" class="btn btn-danger">Отмена</a>
</div>
<?php ActiveForm::end(); ?>
<?php echo $this->render('modal'); ?>