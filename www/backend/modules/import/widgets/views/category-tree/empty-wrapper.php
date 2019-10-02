<?php

use backend\modules\import\widgets\CategoryTreeWidget; ?>
<ul class="tree-category">
    <?php foreach ($category as $key => $one) : ?>
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