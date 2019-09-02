<?php

use yii\helpers\Html;

/** @var int $block_id */
/** @var string $value */
/** @var string $group */
?>

<?= Html::textarea("{$group}[{$block_id}][text]", $value, ['class' => 'form-control']) ?>