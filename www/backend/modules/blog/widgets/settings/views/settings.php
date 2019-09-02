<?php
/* @var $hide_col_status */
/* @var $count_page */
/* @var $model */
/* @var $hide_col */
?>
<div class="settings-for-user"
     data-user-id="<?=Yii::$app->user->identity->id?>"
     data-model="<?=$model?>">
    <ul class="navbar-custom-menu">
        <ul class="nav navbar-nav">
            <li class="dropdown messages-menu">
                <span class="dropdown-toggle" data-toggle="dropdown" title="Настройки">
                    <i class="fa fa-cog" aria-hidden="true"></i>
                </span>
                <ul class="dropdown-menu">
                    <li class="header">
                        <div class="form-group">
                            <label class="control-label" for="country-page">Кол-во записей на странице</label>
                            <input type="number" min="1" max="100" data-old-value="<?= $count_page?>" data-type="count-page" value="<?= $count_page?>" class="form-control control-page">
                            <small class="form-text text-muted">минимум - 1,максимум - 100</small>
                        </div>
                    </li>
                    <?php if($hide_col_status):?>
                        <li class="header">
                            <ul class="form-group check-list-hide-col"
                                data-type="hide-col"
                                <label class="control-label" for="hide-col">Скрыть столбцы</label>
                                <?php if(!empty($attributes)):?>
                                    <?php foreach($attributes as $attribute => $label):?>
                                        <?php if($hide_col !== null) $checked = in_array($attribute,$hide_col) ? 'checked' :'';?>
                                        <li><input type="checkbox" data-attr="<?= $attribute?>" <?=$checked??''?> class="custom-checkbox select-on-check-all check-hide-col"> <?= $label?></li>
                                    <?php endforeach;?>
                                <?php endif;?>
                            </ul>
                        </li>
                    <?php endif;?>
                </ul>
            </li>
        </ul>
    </ul>
</div>
