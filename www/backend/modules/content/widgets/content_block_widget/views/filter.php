<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\modules\filter\models\Filter;

$filter = ArrayHelper::map(Filter::find()->where(['status' => TRUE])->asArray()->all(), 'link', 'name');
?>
<div class="form-group">
    <?php echo Html::dropDownList("{$group}[{$block_id}][text]", $value, $filter, ['prompt' => 'Выбор фильтра', 'class' => 'form-control']); ?>
</div>
