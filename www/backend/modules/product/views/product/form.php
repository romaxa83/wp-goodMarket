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
        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Поставщики</a></li>
        <li><a href="#tab_2" data-toggle="tab" aria-expanded="false">Продукт</a></li>
        <?php if (AccessController::isView(Yii::$app->controller, 'show-gallery-template')): ?>
            <li><a href="#tab_3" data-toggle="tab" aria-expanded="true">Галерея</a></li>
        <?php endif; ?>
        <?php if (AccessController::checkPermission('seo/default/index')): ?>
            <li><a href="#tab_4" data-toggle="tab" aria-expanded="true">SEO</a></li>
        <?php endif; ?>
        <?php if (AccessController::isView(Yii::$app->controller, 'ajax-get-product-characteristic')): ?>
            <li><a href="#tab_5" data-toggle="tab" aria-expanded="true">Характеристики</a></li>
        <?php endif; ?>
        <?php if (Yii::$app->controller->action->id === 'update'): ?>    
            <li><a href="#tab_6" data-toggle="tab" aria-expanded="true">Атрибуты</a></li>
        <?php endif; ?>
        <?php if (Yii::$app->controller->action->id === 'update'): ?> 
            <li><a href="#tab_7" data-toggle="tab" aria-expanded="true">Акции</a></li>
        <?php endif; ?>
    </ul>
    <div class="tab-content">
        <?php if (isset($provider)): ?>
            <div class="tab-pane active" id="tab_1">
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
        <div class="tab-pane" id="tab_2">
            <?php
            echo LangWidget::widget(['model' => $model, 'fields' => [
                    ['type' => 'hidden', 'name' => 'lang_id'],
                    ['type' => 'text', 'name' => 'name'],
                    ['type' => 'text', 'name' => 'price'],
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
            ?>
            <?php echo $form->field($model, 'alias'); ?>
            <?php echo $form->field($model, 'rating')->textInput(['type' => 'number', 'min' => 0, 'max' => 100, 'step' => 1]); ?>
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
            if ($product->publish) {
                echo $form->field($model, 'publish')->inline()->radioList([1 => 'Да', 0 => 'Нет'], [
                    'item' => function($index, $label, $name, $checked, $value) {
                        $check = $checked ? ' checked="checked"' : '';
                        $return = '<label class="mr-15">';
                        $return .= '<input type="radio" name="' . $name . '" value="' . $value . '" ' . $check . ' class="custom-radio">';
                        $return .= '<span>' . ucwords($label) . '</span>';
                        $return .= '</label>';
                        return $return;
                    }
                ]);
            }
            ?>
        </div>
        <?php if (AccessController::isView(Yii::$app->controller, 'show-gallery-template')): ?>
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
                    <input type="hidden" name="prod_rating" value="">
                </div>
            </div>
        <?php endif; ?>
        <?php if (AccessController::checkPermission('seo/default/index')): ?>
            <div class="tab-pane" id="tab_4">
                <?php echo SeoWidget::widget(['id' => $model->id]); ?>
            </div>
        <?php endif; ?>
        <?php if (AccessController::isView(Yii::$app->controller, 'ajax-get-product-characteristic')): ?>
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
                            <button class="btn btn-default crud-box-edit" data-title="Редактирование группы" data-modal="product-group-modal"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                            <button class="btn btn-default crud-box-add" data-title="Добавление группы" data-modal="product-group-modal"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
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
                            <button class="btn btn-default crud-box-edit" data-title="Редактирование характеристики" data-modal="product-characteristic-modal"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                            <button class="btn btn-default crud-box-add add-select-group" data-title="Добавление характеристики" data-modal="product-characteristic-modal"><i class="fa fa-plus-circle" aria-hidden="true"></i></button>
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
        <?php endif; ?>
        <?php if (Yii::$app->controller->action->id === 'update'): ?>
            <div class="tab-pane" id="tab_6">
                <?php if (isset($product->productLang[0]->name)): ?>
                    <div class="mt-15 mb-15"><?php echo $product->categoryLang->name . ' > ' . $product->productLang[0]->name; ?></div>
                <?php endif; ?>
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
                            'value' => function($model) {
                                return $model['id'];
                            }
                        ],
                        [
                            'headerOptions' => ['width' => '92%'],
                            'attribute' => 'char_value',
                            'value' => function($model) {
                                return $model['char_value'];
                            }
                        ],
                        [
                            'headerOptions' => ['width' => '10%'],
                            'attribute' => 'amount',
                            'value' => function($model) {
                                return $model->amount;
                            }
                        ],
                        [
                            'headerOptions' => ['width' => '10%'],
                            'contentOptions' => function($model) use ($id) {
                                return ['class' => 'life-edit-price', 'data-stock-id' => $model['id'], 'data-product-id' => $id];
                            },
                            'attribute' => 'price',
                            'value' => function($model) {
                                return number_format($model['price'], 2, '.', '');
                            }
                        ],
                        [
                            'label' => 'Скидка (%)',
                            'headerOptions' => ['width' => '1%'],
                            'attribute' => 'sale',
                            'value' => function($model) {
                                return 0;
                            },
                        ],
                        [
                            'label' => 'Цена со скидкой',
                            'headerOptions' => ['width' => '1%'],
                            'attribute' => 'sale_price',
                            'value' => function($model) {
                                return 0;
                            }
                        ],
                        [
                            'headerOptions' => ['width' => '1%'],
                            'attribute' => 'media_id',
                            'format' => 'raw',
                            'value' => function($model) use($id) {
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
                            'value' => function($model) use($id) {
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
                            'header' => '',
                            'headerOptions' => ['width' => '1'],
                            'template' => '',
                            'filter' => '<a href="' . Url::to(['/product/product/update?id=' . $id], TRUE) . '"><i class="grid-option fa fa-filter" title="Сбросить фильтр"></i></a>',
                            'buttons' => [
                            ]
                        ],
                    ]
                ]);
                ?>
            </div>
        <?php endif; ?>
        <?php if (Yii::$app->controller->action->id === 'update'): ?>
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
                                    'value' => function($model) {
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
        <?php endif; ?>
    </div>
</div>
<div class="form-group">
    <?php echo Html::submitButton('Сохранить и выйти в список', ['class' => 'btn btn-primary', 'name' => 'save', 'value' => '/product/product']) ?>
    <?php echo Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'save', 'value' => (Yii::$app->controller->action->id === 'update') ? '/product/product/update?id=' . $id : '/product/product/create']) ?>
    <?php if (($model->publish == 1)): ?>
        <a href="<?php echo $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER["HTTP_HOST"] . '/product/' . $model->alias ?>" target="_blank" class="btn btn-primary">Перейти в карточку товара</a>
    <?php endif ?>
    <a href="<?php echo Url::to(['/product/product']) ?>" class="btn btn-danger">Отмена</a>
</div>
<?php ActiveForm::end(); ?>
<div class="modal fade bd-example-modal-lg" id="gallery-show" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div id="gallery-show-img"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="manufacturer-show" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Производитель</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="recipient-name" class="control-label">Производитель</label>
                    <input type="text" class="form-control" id="refacturer">
                    <p class="help-block help-block-error"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary add-manufacturer">Сохранить</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="product-group-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel"></h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label class="control-label">Группа</label>
                        <input type="text" name="group" class="form-control">
                        <p class="help-block help-block-error"></p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary crud-box-save" data-id="product-group_id" data-url="<?php echo Url::to('/admin/product/product/ajax-add-product-group', TRUE); ?>">Сохранить</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="product-characteristic-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel"></h4>
            </div>
            <div class="modal-body">
                <form>
                    <input type="hidden" name="group_id" readonly="readonly" class="form-control">
                    <div class="form-group">
                        <label class="control-label">Название</label>
                        <input type="text" name="name" class="form-control">
                        <p class="help-block help-block-error"></p>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Тип</label>
                        <select name="type" class="form-control">
                            <option value="">Выберите тип</option>
                            <?php foreach (Yii::$app->getModule('product')->params['characteristic_type'] as $k => $v): ?>
                                <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="help-block help-block-error"></p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary crud-box-save" data-id="characteristic-name" data-url="<?php echo Url::to('/admin/product/product/ajax-add-product-characteristic', TRUE); ?>">Сохранить</button>
            </div>
        </div>
    </div>
</div>