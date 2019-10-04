<div class="dropdown-menu dropdown-menu--category">
    <?php foreach($parentCategory as $oneParent) : ?>
    <div>
        <span class="dropdown-item dropdown-toggle" href="#"><?= $oneParent['name'] ?></span>
        <div class="dropdown-menu dropdown-menu--subcategory">
            <a class="dropdown-item" href="<?= \yii\helpers\Url::to(['catalog/' . $oneParent['category_alias']]) ?>"><?= $oneParent['name'] ?></a>
            <?php if(isset($childCategory[$oneParent['row_id']])) : ?>
                <?php foreach($childCategory[$oneParent['row_id']] as $oneChild) : ?>
                <a class="dropdown-item" href="<?= \yii\helpers\Url::to(['catalog/' . $oneChild['category_alias']]) ?>"><?= $oneChild['name'] ?></a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>