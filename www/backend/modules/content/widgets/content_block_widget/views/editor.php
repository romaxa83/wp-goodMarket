<?php

use vova07\imperavi\Widget;

/** @var int $block_id */
/** @var string $value */
/** @var string $group */
?>
<?= Widget::widget([
    'name' => "{$group}[{$block_id}][text]",
    'value' => $value,
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'plugins' => [
            'clips',
            'fullscreen',
        ],
        'clips' => [
            ['Lorem ipsum...', 'Lorem...'],
        ],
    ],
]) ?>