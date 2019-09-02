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
                            'label' => 'Блог',
                            'icon' => 'book',
                            'url' => '#',
                            'items' => [
                                ['label' => 'Категорий', 'icon' => 'clone', 'url' => ['/blog/category/index'], 'visible' => AccessController::checkPermission('/blog/category/index')],
                                ['label' => 'Теги', 'icon' => 'tags', 'url' => ['/blog/tag/index'], 'visible' => AccessController::checkPermission('/category/category/index')],
                                ['label' => 'Посты','icon' => 'file-text-o','url' => ['/blog/post/index'], 'visible' => AccessController::checkPermission('/category/category/index')]
                            ],
                            'visible' => AccessController::checkPermission('/category/category/index')
                        ],
                        ['label' => 'Баннера', 'icon' => 'list', 'url' => ['/banners/banners'], 'visible' => AccessController::checkPermission('/banners/banners/index')],
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
