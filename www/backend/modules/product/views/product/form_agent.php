<?php

use kartik\select2\Select2;
?>
<div class="form-group">
    <label class="control-label" for="product-stock_id">ID</label>
    <input id="product-stock_id" class="form-control" name="Product[id]" value="<?php echo $item->id; ?>" readonly="readonly">
</div>
<div class="form-group">
    <label class="control-label" for="product-vendor-code">Артикул</label>
    <input id="product-vendor-code" class="form-control" name="Product[vendor_code]" value="<?php echo $item->vendor_code; ?>" readonly="readonly">
</div>
<div class="form-group">
    <label class="control-label" for="product-category-name">Категория</label>
    <?php if ($item->type == 'baza') : ?>
        <input type="text" id="product-category-name" class="form-control" name="Product[category_name]" value="<?php echo $item->categoryLang->name; ?>" readonly="readonly">
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
    <label class="control-label" for="product-name">Название</label>
    <input type="text" id="product-name" class="form-control" name="Product[name]" value="<?php echo $item->productLang[0]->name; ?>" readonly="readonly">
</div>
<div class="form-group">
    <label class="control-label" for="product-trade-price">Оптовая цена</label>
    <input type="text" id="product-trade-price" class="form-control" name="Product[trade_price]" value="<?php echo number_format($item->trade_price, 2, '.', ''); ?>" readonly="readonly">
</div>
<div class="form-group">
    <label class="control-label" for="product-amount">Количество</label>
    <input type="number" id="product-amount" class="form-control" name="Product[amount]" value="<?php echo $item->amount; ?>" <?php echo ($item->type === 'GoodMarket') ? '' : 'readonly="readonly"'; ?>>
</div>