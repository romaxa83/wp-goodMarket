<?php

use yii\helpers\Url;
use yii\helpers\Html;
use common\controllers\AccessController;
?>
<div class="box-header with-border">
    <div class="pull-left">
        <div class="box-title">
            <?php if (count($level['child']) > 0): ?>
                <a data-toggle="collapse" data-parent="<?php echo '#' . $accordion; ?>" href="#collapse-<?php echo $level['parent']['id']; ?>" aria-expanded="false" class="collapsed">
                    <?php echo $level['parent']['name']; ?>
                </a>
            <?php else : ?>
                <span><?php echo $level['parent']['name']; ?></span>
            <?php endif; ?>
        </div><br>
        <?php if ($level['parent']['publish_status'] == 1): ?>
            <div style="color: #00a65a;">Вкл. на складе</div>
        <?php endif; ?>
        <?php if ($level['parent']['publish_status'] == 0): ?>
            <div style="color: #dd4b39;">Откл. на складе</div>
        <?php endif; ?>
    </div>
    <div class="pull-right" style="margin-top: 10px;margin-left: 15px;font-size: 18px;">
        <?php if(AccessController::isView(Yii::$app->controller, 'edit')):?>
            <a href="<?php echo Url::to(['/category/category/edit', 'id' => $level['parent']['id']]); ?>"><i class="fa fa-edit"></i></a>
        <?php endif;?>
    </div>
    <div class="pull-right" style="margin-top: 7px;">
        <?php
        $access = AccessController::isView(Yii::$app->controller, 'update-status');
        $checked = (isset($level['parent']['publish']) && $level['parent']['publish'] == 1) ? 'true' : '';
        $options = [
            'id' => 'cd_' . $level['parent']['id'],
            'class' => 'tgl tgl-light publish-toggle status-toggle',
            'data-id' => $level['parent']['id'],
            'data-url' => Url::to(['update-status']),
            'disabled' => !$access
        ];
        echo Html::beginTag('div') .
        Html::checkbox('status', $checked, $options) .
        Html::label('', 'cd_' . $level['parent']['id'], ['class' => 'tgl-btn']) .
        Html::endTag('div');
        ?>
    </div>
</div>
