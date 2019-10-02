<?php

use yii\helpers\Url;
use yii\helpers\Html;

/** @var int $slugId */
/** @var string $slugRoute */
/** @var string $routeToAction */
/** @var string $value */
/** @var array $templates */
?>

<?= Html::label('Выбрать шаблон', 'choose-template', array('class' => 'control-label')) ?>
<?= Html::hiddenInput('slug[id]', $slugId) ?>
<?= Html::hiddenInput('slug[route]', $slugRoute) ?>
<?= Html::hiddenInput('slag-action', Url::to([$routeToAction])) ?>
<?= Html::dropDownList(
    'slug[template]',
    $value,
    array_map(
        function($el){
            return $el['label'];
        },
        $templates
    ),
    array(
        'prompt' => 'Выбор шаблона',
        'class' => 'form-control',
        'id' => 'choose-template'
    )
) ?>