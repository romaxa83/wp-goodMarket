<?php

use yii\helpers\Json;

?>
<div class="modal-body">
    <?php foreach ($product_attributes as $k => $v): ?>
        <div class="form-group" data-id="<?= $k ?>">
            <label class="col-sm-3 control-label" for="Attribute[product_attribute_<?= $k ?>]"><?= $v['characteristic']['name'] ?>: </label>
            <div class="col-sm-6">
                <span class="form-control" <?= $v['characteristic']['type'] == 'color' ? 'style="color:' . $v['value'] . '"' : '' ?>
              id="Attribute[product_attribute_<?= $k ?>]"><?= $v['value'] ?></span>
            </div>
            <div class="col-sm-1">
                <button type="button" class="close delete-product-attribute" aria-label="Close" data-id="<?= $k ?>"><span aria-hidden="true">&times;</span></button>
            </div>
        </div>
    <?php endforeach; ?>
    <button type="button" class="btn btn-primary pre-generate-product-characteristic">Сгенерировать</button>
</div>

<input type="hidden" name="Atribute[product_attributes]" value='<?= Json::encode($product_attributes) ?>'>
