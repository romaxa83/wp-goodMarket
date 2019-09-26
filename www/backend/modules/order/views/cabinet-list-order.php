<?php

use yii\helpers\Url;
use common\helpers\ProductsHelper;

/* @var $order */
/* @var $count */
$status_list = ['1' => 'новый', '2' => 'подтвержден', '3' => 'отменен', '4' => 'отправлен', '5' => 'Покупка в один клик'];
?>
<div class="row-item">
    <table class="table-responsive table-history table-condensed w-100">
        <thead data-toggle="collapse" data-target="#history-element-<?= $count ?>">
        <tr>
            <th class="id-history">Заказ <?= $order['id'] ?></th>
            <th class="date-history"><?= date("d.m.Y", strtotime($order['date'])) ?></th>
            <th colspan="2" class="status">Статус заказа: <span
                        class="sent"><?= $status_list[$order['status']] ?></span></th>
        </tr>
        </thead>
        <tbody class="collapse in" id="history-element-<?= $count ?>">
        <?php foreach ($order['order_products'] as $product): ?>
            <tr>
                <td class="td1"><a href="<?= Url::to('/catalog' . \frontend\controllers\SiteController::createCategoryUrl($product['product_data']['category_id']) . '/product/' . $product['product_data']['alias']) ?>">
                        <?= $product['vproduct_id'] != null
                            ? $product['product_data']['product_name'] . ' (' . $product['vproduct_data']['char_value'] . ')'
                            : $product['product_data']['product_name'] ?></a></td>
                <td class="td2"><?= isset($product['product_data']['sale']) ?
                        ProductsHelper::priceWithSale($product['product_data']['price'], $product['product_data']['sale']['sale']) :
                        $product['product_data']['price'] ?> грн
                </td>
                <td class="td3"><?= $product['count'] ?> шт</td>
                <td class="td4"><?= $product['cost'] ?> грн</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
        <tr>
            <td class="text-total" colspan="3">Всего к оплате:</td>
            <td class="value-total" colspan="1"><?= $order['cost'] ?> грн</td>
        </tr>
        </tfoot>
    </table>
</div>