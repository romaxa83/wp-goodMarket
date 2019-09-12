<?php
    use kartik\select2\Select2;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use app\modules\order\OrderAsset;

    OrderAsset::register($this);
?>
<div class="table-responsive-md manage-table" style="<?=($type=='view')?'display:none':''?>">
    <table id="manage-table-order-products" class="table table-hover">
        <thead>
            <tr>
                <th>Язык</th>
                <th>Категория</th>
                <th>Название</th>
                <th>Вариация</th>
                <th>Количество</th>
                <th width="100">Управление</th>
            </tr>
        </thead>
        <tbody>
             <tr>
                 <td>
                     <?php
                     echo Select2::widget([
                         'name' => 'lang_list_new',
                         'data' =>  $lang_list,
                         'options' => ['placeholder' => 'Выберите язык','class'=>'select-sale', 'width'=>'80px'],
                         'pluginOptions' => [
                             'allowClear' => true
                         ],
                     ]);
                     ?>
                 </td>
                <td>
                    <?php
                        echo Select2::widget([
                            'name' => 'category_list_new',
                            'attribute' =>'category_id',
                            'disabled' => true,
                            'options' => ['placeholder' => 'Выберите категорию','class'=>'select-sale', 'width'=>'200px'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    ?>
                </td>
                <td>
                    <?php
                        echo Select2::widget([
                            'name' => 'product_list_new',
                            'attribute' =>'product_id',
                            'disabled' => true,
                            'options' => ['placeholder' => 'Выберите продукт', 'class'=>'select-sale','width'=>'200px' ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    ?>
                </td>
                <td>
                   <?php
                        echo Select2::widget([
                            'name' => 'vproduct_list_new',
                            'disabled' => true,
                            'options' => ['placeholder' => 'Выберите вариацию', 'class'=>'select-sale','width'=>'200px' ],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    ?>
                </td>
                <td>
                   <input type="number" name="good-count" class="form-control" min="1" value="1">
                </td>
                <td>
                    <?=Html::tag(
                        'span',
                        '',[
                            'style' => 'color:rgb(63,140,187); margin:10px; cursor:pointer',
                            'class' => 'grid-option fa fa-floppy-o save-product',
                            'data-pjax' => '1'
                        ]);?>
                </td>
            </tr>
        </tbody>
    </table>
    <input type="hidden" name="products_data" value="{}">
</div>
