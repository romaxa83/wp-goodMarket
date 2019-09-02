<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\modules\filter\models\Filter;

$filter = ArrayHelper::map(Filter::find()->where(['status' => TRUE])->asArray()->all(), 'link', 'name');
?>
<div class="form-group">
    <?php echo Html::dropDownList('filter', null, $filter, ['prompt' => 'Выбор фильтра', 'class' => 'form-control']); ?>
</div>
