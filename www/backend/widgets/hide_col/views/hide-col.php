<div class="btn-group">
    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Настройки">
        <i class="fa fa-gears"></i>
    </button>
    <ul class="dropdown-menu check-list-hide-col"
        data-type="hide-col"
        data-model="<?= $model ?>"
        data-user-id="<?= Yii::$app->user->identity->id ?>">
        <li><a href="javascript: void(0);">Скрыть столбец</a></li>
        <li role="separator" class="divider"></li>
        <?php if (!empty($attributes)): ?>
            <?php foreach ($attributes as $attribute => $label): ?>
                <?php if ($hide_col !== null) $checked = in_array($attribute, $hide_col) ? 'checked' : ''; ?>
                <li><input type="checkbox" data-attr="<?= $attribute ?>" <?= $checked ?? '' ?> class="custom-checkbox select-on-check-all check-hide-col"> <?= $label ?></li>
                <?php endforeach; ?>
            <?php endif; ?>
    </ul>
</div>