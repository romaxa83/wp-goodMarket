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
                <td><?=$form->field($model, 'currency')->textInput(['name'=>'currency'])->label(false)->error(false)?></td>
                <td>
                    <?php
                        $checked = ($model->status==1) ? 'true' : '';
                        $options = [
                            'id' => 'cd_'.$key,
                            'class' => 'tgl tgl-light publish-toggle status-toggle',
                            'data-id' => $key,
                            'data-url' => Url::to(['update-status']),
                        ];
                        echo Html::beginTag('div') .
                            Html::checkbox('status', $checked, $options) .
                            Html::label('',  'cd_'.$key, ['class' => 'tgl-btn']) .
                            Html::endTag('div');
                    ?>
                </td>

                <td>
                    <?=Html::tag(
                            'span',
                            '',[
                                'style' => 'color:rgb(63,140,187), margin:10px',
                                'class' => 'grid-option fa fa-floppy-o save-lang',
                                'data-get_action' => $action,
                                'data-entity' => 'lang',
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
