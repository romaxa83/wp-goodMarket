<ul class="navbar-nav">
    <?php foreach($parentCategory as $oneParent) : ?>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="<?= \yii\helpers\Url::to(['catalog/' . $oneParent['category_alias']]) ?>">
            <?= $oneParent['name'] ?>
        </a>
        <div class="dropdown-menu dropdown-menu--subcategory">
            <?php if(isset($childCategory[$oneParent['row_id']])) : ?>
                <?php foreach($childCategory[$oneParent['row_id']] as $oneChild) : ?>
                <a href="<?= \yii\helpers\Url::to(['catalog/' . $oneChild['category_alias']]) ?>" class="dropdown-item"><?= $oneChild['name'] ?></a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </li>
    <?php endforeach; ?>
    <li class="nav-item dropdown">
        <a class="nav-link nav-link--all" href="<?= \yii\helpers\Url::to(['catalog']) ?>">
            Все категории
        </a>
    </li>
</ul>