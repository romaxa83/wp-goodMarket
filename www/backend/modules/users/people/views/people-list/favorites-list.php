<div class="box-header with-border">
    <h3 class="box-title">Список избранное</h3>
</div>
<div class="box-favorites">
    <?php if(!empty($favorites['products_id'])):?>
        <ul>
        <?php foreach ($favorites['product_name'] as $one):?>
            <li><?=$one?></li>
        <?php endforeach;?>
        </ul>
    <?php else:?>
        <h4>У пользователя нет товаров в избранном</h4>
    <?php endif;?>
</div>
