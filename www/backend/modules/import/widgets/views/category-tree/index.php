<?php

use backend\modules\import\widgets\CategoryTreeWidget;
use yii\helpers\Html;

/* @var $categoryXml */
/* @var $categoryXml */
?>

<div class="row">
    <div class="col-xs-6">
        <ul class="main-category-tree tree-category">
            <?php foreach ($categoryXml as $key => $one) : ?>
                <li data-id="<?= $key ?>">
                    <div class="list-group-item list-group-item-action">
                        <?= $one['parent']['name'] ?>
                    </div>
                    <?php
                    if (!empty($one['child'])) {
                        echo CategoryTreeWidget::widget(['category' => $one['child'], 'wrapper' => false]);
                    }
                    ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="col-xs-6">
        <?php for ($i = 0; $i < $count; $i++) : ?>
            <?=
            Html::dropDownList(
                    "categoryProsto[]", isset($chooseCategory) ? $chooseCategory[$i] : 'Укажите категорию', $category, ['class' => 'list-group-item list-group-item-action category-prosto', 'prompt' => 'Укажите категорию']);
            ?>
        <?php endfor; ?> 
    </div>
</div>