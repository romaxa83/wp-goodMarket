<?php

use common\controllers\AccessController;
?>
<div class="row">
    <?php if (AccessController::isView(\Yii::$app->controller, 'clear-log')): ?>
        <div class="col-xs-1">
            <a href="<?= yii\helpers\Url::to('/admin/import/import/clear-log') ?>" class="btn btn-success">Очистить логи</a>
        </div>
    <?php endif; ?>
</div>    
<div class="row block-log">
    <div class="col-xs-12">
        <?php foreach ($log as $key => $value) : ?>    
            <div class="panel-group">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" href="#<?= $value['name'] ?>"><?= $value['name'] ?></a>
                        </h4>
                    </div>
                    <div id="<?= $value['name'] ?>" class="panel-collapse collapse">
                        <?php foreach ($value['value'] as $one) : ?>
                            <div class="panel-body"><?= $one ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>    
    </div>
</div>