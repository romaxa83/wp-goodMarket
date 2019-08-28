<?php

use kartik\select2\Select2;
?>
<div class="form-group">
    <label class="control-label" for="product-stock_id">ID продукта</label>
    <input id="product-stock_id" class="form-control" name="Product[stock_id]" value="<?php echo $item->stock_id; ?>" readonly="readonly">
</div>
<div class="form-group">
    <label class="control-label" for="product-vendor-code">Артикул продукта</label>
    <input id="product-vendor-code" class="form-control" name="Product[vendor_code]" value="<?php echo $item->vendor_code; ?>" readonly="readonly">
</div>
<div class="form-group">
    <label class="control-label" for="product-category-name">Категория</label>
    <?php if ($item->type == 'baza') : ?>
        <input type="text" id="product-category-name" class="form-control" name="Product[category_name]" value="<?php echo $item->category->name; ?>" readonly="readonly">
        <input type="hidden" id="product-category-id" class="form-control" name="Product[category_id]" value="<?php echo $item->category->id; ?>" readonly="readonly">
    <?php else : ?>
        <?php
        echo Select2::widget([
            'model' => $item->category,
            'attribute' => 'name',
            'data' => $category,
            'options' => ['placeholder' => 'Выберите категорию ...', 'value' => $item->category_id]
        ]);
        ?>
    <?php endif; ?>
</div>
<div class="form-group">
    <label class="control-label" for="product-name">Название товара</label>
    <input type="text" id="product-name" class="form-control" name="Product[name]" value="<?php echo $item->productLang[0]->name; ?>" readonly="readonly">
</div>
<div class="form-group">
    <label class="control-label" for="product-trade-price">Оптовая цена</label>
    <input type="text" id="product-trade-price" class="form-control" name="Product[trade_price]" value="<?php echo number_format($item->trade_price, 2, '.', ''); ?>" readonly="readonly">
</div>
<div class="form-group">
    <label class="control-label" for="product-amount">Остаток на складе</label>
    <input type="text" id="product-amount" class="form-control" name="Product[amount]" value="<?php echo $item->amount; ?>" readonly="readonly">
</div>
<div class="form-group">
    <label class="control-label" for="product-stock-publish">Статус на базе</label>
    <input type="text" id="product-stock-publish" class="form-control" name="Product[stock_publish]" value="<?php echo $item->stock_publish; ?>" readonly="readonly">
</div>