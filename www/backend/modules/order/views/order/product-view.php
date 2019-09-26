<?php 
use app\modules\order\OrderAsset;
$this->title = 'Продукты заказа';
OrderAsset::register($this);
?>
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?=$this->title?></h3>
    </div>
    <div class="box-body">
        <?php
            echo $this->render('order-products-form',$order_products_params);
            echo $this->render('products-table',$order_products_params)
        ?>
    </div>
</div>
