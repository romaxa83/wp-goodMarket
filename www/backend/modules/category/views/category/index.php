<?php

use backend\modules\category\CategoryAsset;
use yii\helpers\Html;

CategoryAsset::register($this);
?>
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Список категорий</h3>
    </div>
    <div class="row">
        <div class="col-md-12 mb-15">
            <?= Html::a('Добавить категорию', ['create'], ['class' => 'btn btn-primary']); ?>
        </div>
    </div>
    <div class="box-body">
        <div class="box-group" id="accordion0">
            <?php foreach ($category as $level0): ?>
                <div class="panel box">
                    <?php echo $this->render('form-item', ['level' => $level0, 'accordion' => 'accordion0']); ?>
                    <?php if (count($level0['child']) > 0) : ?>
                        <div id="collapse-<?php echo $level0['parent']['id']; ?>" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                            <div class="box-body">
                                <div class="box-group" id="accordion1">
                                    <?php foreach ($level0['child'] as $level1) : ?>
                                        <div class="panel box">
                                            <?php echo $this->render('form-item', ['level' => $level1, 'accordion' => 'accordion1']); ?>
                                            <?php if (count($level1['child']) > 0) : ?>
                                                <div id="collapse-<?php echo $level1['parent']['id']; ?>" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                    <div class="box-body">
                                                        <div class="box-group" id="accordion2">
                                                            <?php foreach ($level1['child'] as $level2) : ?>
                                                                <div class="panel box">
                                                                    <?php echo $this->render('form-item', ['level' => $level2, 'accordion' => 'accordion2']); ?>
                                                                    <?php if (count($level2['child']) > 0) : ?>
                                                                        <div id="collapse-<?php echo $level2['parent']['id']; ?>" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                                            <div class="box-body">
                                                                                <div class="box-group" id="accordion3">
                                                                                    <?php foreach ($level2['child'] as $level3) : ?>
                                                                                        <div class="panel box">
                                                                                            <?php echo $this->render('form-item', ['level' => $level3, 'accordion' => 'accordion3']); ?>
                                                                                            <?php if (count($level3['child']) > 0) : ?>
                                                                                                <div id="collapse-<?php echo $level3['parent']['id']; ?>" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                                                                    <div class="box-body">
                                                                                                        <div class="box-group" id="accordion4">
                                                                                                            <?php foreach ($level3['child'] as $level4) : ?>
                                                                                                                <div class="panel box">
                                                                                                                    <?php echo $this->render('form-item', ['level' => $level4, 'accordion' => 'accordion4']); ?>
                                                                                                                </div>
                                                                                                            <?php endforeach; ?>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            <?php endif; ?>
                                                                                        </div>
                                                                                    <?php endforeach; ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
