<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<tr data-key="<?=$key?>" data-index="<?=$index?>">
    <td colspan="6" style="padding: 0px;">
        <?php $form = ActiveForm::begin()?>
        <table id="sub_table-<?=$index?>">
            <tbody>
            <tr>
                <td><?=$index+1?></td>
                <td><?=$form->field($model, 'name')->textInput(['autofocus' => true, 'name' => 'name'])->label(false)->error(false)?></td>
                <td><?=$form->field($model, 'alias')->textInput(['name'=>'alias'])->label(false)->error(false)?></td>
                <td><?=$form->field($model, 'exchange')->textInput(['name'=>'exchange'])->label(false)->error(false)?></td>
                <td>
                    <?=Html::tag(
                        'span',
                        '',[
                        'style' => 'color:rgb(63,140,187), margin:10px',
                        'class' => 'grid-option fa fa-floppy-o save-currency',
                        'data-get_action' => $action,
                        'data-entity' => 'currency',
                        'data-key' => $key,
                        'data-pjax' => '1'
                    ]);?>

                </td>
            </tr>
            </tbody>
        </table>
        <?php ActiveForm::end(); ?>
    </td>
</tr>
