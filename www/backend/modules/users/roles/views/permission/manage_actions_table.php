<?php
    use kartik\select2\Select2;
    use yii\helpers\ArrayHelper;
    use yii\helpers\Html;
    use app\modules\users\roles\RolesAsset;

    RolesAsset::register($this);
?>
<div class="table-responsive-md actions-table">
    <table id="manage-actions-table" class="table table-hover">
        <thead>
            <tr>
                <th>Модуль</th>
                <th>Подмодуль</th>
                <th>Контролер</th>
                <th>Действие</th>
                <th width="100">Управление</th>
            </tr>
        </thead>
        <tbody>
             <tr> 
                <td>
                    <?php 
                        echo Select2::widget([
                            'name' => 'module',
                            'data' =>  $module,
                            'language' => 'ru',
                            'options' => ['placeholder' => 'Выберите модуль','class'=>'select-sale', 'width'=>'200px', 'data-parent-route'=>''],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    ?>
                </td>
                <td>
                    <?php 
                        echo Select2::widget([
                            'name' => 'submodule',
                            'data' => $submodule,
                            'language' => 'ru',
                            'disabled' => true,
                            'options' => ['placeholder' => 'Выберите подмодуль', 'class'=>'select-sale','width'=>'200px', 'data-parent-route'=>''],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    ?>
                </td>
                <td>
                   <?php 
                        echo Select2::widget([
                            'name' => 'controller',
                            'data' => $controller,
                            'language' => 'ru',
                            'disabled' => true,
                            'options' => ['placeholder' => 'Выберите контроллер', 'class'=>'select-sale','width'=>'200px', 'data-parent-route'=>''],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    ?>
                </td>
                <td>
                   <?php 
                        echo Select2::widget([
                            'name' => 'action',
                            'data' => $action,
                            'language' => 'ru',
                            'disabled' => true,
                            'options' => ['placeholder' => 'Выберите действие', 'class'=>'select-sale','width'=>'200px', 'data-parent-route'=>''],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]);
                    ?>
                </td>
                <td>
                    <?=Html::tag(
                        'span',
                        '',[
                            'style' => 'color:rgb(63,140,187), margin:10px',
                            'class' => 'grid-option fa fa-floppy-o save-route',
                            'data-pjax' => '1'
                        ]);?>
                </td>
            </tr>
        </tbody>
    </table>
    <input type="hidden" name="routes" value=<?=json_encode($permission_routes)?>>
</div>
