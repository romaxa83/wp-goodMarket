<?php
use yii\helpers\Json;
?>

<div class="modal-body">
    <p>Если поле цена = 0, то товар не отображается</p>
    <div class="form-group">
        <div class="col-sm-6">
        </div>
        <div class="col-sm-3">
            Цена
        </div>
        <div class="col-sm-3">
            Количество
        </div>
    </div>

    <?php foreach ($combination as $c): ?>
    <?php $encode = Json::encode($c)?>
    <div class="form-group">
        <div class="col-sm-6">
            <span class="form-control"><?php foreach ($c as $k => $v) {
                    echo $product_attributes[$v]['characteristic']['type'] == 'color'
                        ? ($k != array_key_first($c) ? ' | ' : '' ) . 'Цвет: <span style="color:' . $product_attributes[$v]['value'] . '"> ' . $product_attributes[$v]['value'] . '</span>'
                        : ($k != array_key_first($c) ? ' | ' : '' ) . $product_attributes[$v]['value'];
                } ?></span>
        </div>
        <div class="col-sm-3">
            <input type="number" name="attribute_price" class="form-control" data-id='<?= $encode ?>'
                   value="<?= isset($vproducts[$encode]['price']) ? $vproducts[$encode]['price'] : '' ?>"
                   data-product-id="<?= $product_attributes[$c[0]]['product_id'] ?>">
        </div>
        <div class="col-sm-3">
            <input type="number" name="attribute_count" class="form-control" data-id='<?= $encode ?>'
                   value="<?= isset($vproducts[$encode]['amount']) ? $vproducts[$encode]['amount'] : '' ?>"
                   data-product-id="<?= $product_attributes[$c[0]]['product_id'] ?>">
        </div>
    </div>
    <?php endforeach; ?>

    <button type="button" class="btn btn-primary generate-product-characteristic">Добавить</button>
</div>
