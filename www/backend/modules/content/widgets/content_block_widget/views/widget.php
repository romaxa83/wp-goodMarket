<?php

use yii\helpers\Html;

/** @var int $block_id */
/** @var int $content_id */
/** @var string $value */
/** @var string $type */
/** @var string $name */
/** @var string $label */
/** @var string $group */
/** @var array $errors */
?>

<div class="form-group <?= $errors ? 'has-error' : '' ?>">
    <label class="control-label"><?= $label ?></label>
    <input type="hidden" name="<?= $group ?>[<?= $block_id ?>][id]" value="<?= $content_id ?>">
    <input type="hidden" name="<?= $group ?>[<?= $block_id ?>][name]" value="<?= $name ?>">
    <input type="hidden" name="<?= $group ?>[<?= $block_id ?>][label]" value="<?= $label ?>">
    <?= Html::input('hidden', "{$group}[{$block_id}][type]", $type) ?>

    <?= $this->render($type, [
        'group' => $group,
        'block_id' => $block_id,
        'value' => $value,
    ]) ?>

</div>
