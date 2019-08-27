<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use common\controllers\AccessController;
?>

<tr data-key="<?=$key?>" data-index="<?=$index?>">
    <td colspan="6" style="padding: 0px;">
        <?php $form = ActiveForm::begin()?>
        <table id="sub_table-<?=$index?>">
            <tbody>
            <tr>
                <td><?=$index+1?></td>
                <td><?=$form->field($model, 'name')->textInput(['autofocus' => true, 'name' => 'name'])->label(false)->error(false)?></td>
                <td><?=$form->field($model, 'position')->textInput(['name'=>'position','type' => 'number','value' => $index + 1])->label(false)->error(false)?></td>

                <td>
                    <?=Html::tag(
                        'span',
                        '',[
                        'style' => 'color:rgb(63,140,187), margin:10px',
                        'class' => 'grid-option fa fa-floppy-o save-row',
                        'data-get_action' => $action,
                        'data-key' => $key,
                        'data-pjax' => '1',
                        'data-setting' => $setting
                    ]);?>

                </td>

            </tr>
            </tbody>
        </table>
        <?php ActiveForm::end(); ?>
    </td>


</tr>
