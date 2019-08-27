<?php

use yii\helpers\Url;
use yii\helpers\Html;
use backend\modules\settings\helpers\StaticDataHelper;

/* @var $data backend\modules\settings\models\ProductStaticData */
$this->title = 'Блоки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Список блоков</h3>
    </div>
    <div class="box-body">
        <div class="table-responsive-md">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">Заголовок</th>
                    <th scope="col">Текст</th>
                    <th scope="col">Статус</th>
                    <th scope="col" width="110">Управление</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $one):?>
                    <tr>
                        <td><?= $one->title?></td>
                        <td><?= StaticDataHelper::parseDescriptionForAdmin($one->description)?></td>
                        <td><?php
                            $checked = ($one->status == 1) ? true : false;
                            $options = [
                                'id' => 'cd_' . $one->id,
                                'class' => 'tgl tgl-light publish-toggle status-toggle',
                                'data-id' => $one->alias,
                                'data-url' => Url::to(['update-status'])
                            ];
                            echo Html::beginTag('div') .
                                Html::checkbox('status', $checked, $options) .
                                Html::label('', 'cd_' . $one->id, ['class' => 'tgl-btn']) .
                                Html::endTag('div');
                            ?></td>
                        <td>
                            <a href="<?=Url::to(['/settings/product-static-data/edit?id=' . $one->id])?>" class="grid-option fa fa-pencil"></a>
                        </td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>