<?php

use common\controllers\AccessController;
?>

<aside class="main-sidebar">
    <section class="sidebar">
        <?php
        echo dmstr\widgets\Menu::widget(
                [
                    'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                    'items' => [
                        ['label' => 'Каталог', 'icon' => 'list', 'url' => ['/category/category'], 'visible' => AccessController::checkPermission('/category/category/index')],
                        ['label' => 'Продукты', 'icon' => 'list', 'url' => ['/product/product'], 'visible' => AccessController::checkPermission('/product/product/index')],
                        [
                            'label' => 'Контент',
                            'icon' => 'briefcase',
                            'url' => '#',
                            'items' =>
                                [
                                    ['label' => 'Страницы', 'icon' => 'file-o', 'url' => ['/content/page'], 'visible' => AccessController::checkPermission('/content/page')],
                                    ['label' => 'Типы записей', 'icon' => 'archive', 'url' => ['/content/channel'], 'visible' => AccessController::checkPermission('/content/channel')],
                                    ['label' => 'Настройки', 'icon' => 'gear', 'url' => ['/content/options'], 'visible' => AccessController::checkPermission('/content/options')],
                                ]
                        ],
                        ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                        [
                            'label' => 'Инструменты',
                            'icon' => 'share',
                            'url' => '#',
                            'items' => [
                                ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],],
                                ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
                                [
                                    'label' => 'Level One',
                                    'icon' => 'circle-o',
                                    'url' => '#',
                                    'items' => [
                                        ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
                                        [
                                            'label' => 'Level Two',
                                            'icon' => 'circle-o',
                                            'url' => '#',
                                            'items' => [
                                                ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                                ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]
        )
        ?>
    </section>
</aside>
