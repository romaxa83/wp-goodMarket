<?php

use yii\helpers\Url;
use kartik\select2\Select2;
use kartik\color\ColorInput;
use backend\modules\filemanager\widgets\FileInput;
?>
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
                        <input type="hidden" name="action" class="form-control">
                        <input type="text" name="name" class="form-control">
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
                        <input type="hidden" name="action" class="form-control">
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
<div class="modal fade" id="atribute-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="exampleModalLabel">Добавление атрибутов</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <?php
                    echo '<label class="control-label">Характеристика</label>';
                    echo Select2::widget([
                        'name' => 'Atribute[characteristic]',
                        'data' => [],
                        'language' => 'ru',
                        'options' => ['placeholder' => 'Выберите характеристику'],
                    ]);
                    ?>
                </div>
                <div class="form-group">
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
                <div class="form-group">
                    <label class="control-label">Значение</label>
                    <input type="text" name="Atribute[value]" class="form-control">
                    <p class="help-block help-block-error"></p>
                </div>
                <div class="form-group">
                    <label class="control-label">Цена</label>
                    <input type="text" name="Atribute[price]" class="form-control">
                    <p class="help-block help-block-error"></p>
                </div>
                <div class="form-group">
                    <label class="control-label">Количество</label>
                    <input type="text" name="Atribute[amount]" class="form-control">
                    <p class="help-block help-block-error"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary save-">Сохранить</button>
            </div>
        </div>
    </div>
</div>