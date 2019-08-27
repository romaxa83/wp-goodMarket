<div class="box-header with-border">
    <h3 class="box-title">Список желаний</h3>
</div>
<div class="box-wishes">

    <?php if (!empty($wishes)):?>

        <div class="row">
            <div class="col-xs-6">
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <?php foreach ($wishes as $i => $wish):?>
                    <div class="panel panel-default">
                        <div class="panel-heading" role="tab" id="heading<?=$i?>">
                            <h4 class="panel-title" data-target="#collapse<?=$i?>" data-toggle="collapse" data-parent="#accordion" aria-expanded="true" aria-controls="collapse<?=$i?>">
                                <?=$wish['title']?>  ( <?= Yii::$app->formatter->asDate($wish['created_at'])?> ) <span class="badge">
                                    <?=!empty($wish['products_id']) ? substr_count($wish['products_id'],',') + 1 : 'пуст'?>
                                </span>
                            </h4>
                        </div>
                        <div id="collapse<?=$i?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading<?=$i?>">
                            <div class="panel-body">
                                <?php if(!empty($wish['products_id'])):?>
                                    <ul>
                                    <?php foreach ($wish['product_name'] as $one):?>

                                        <li><?= $one?></li>

                                    <?php endforeach;?>
                                    </ul>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                <?php endforeach;?>
                </div>
            </div>
        </div>

    <?php else:?>
        <h4 class="box-title">У пользователя нет списков</h4>
    <?php endif;?>
</div>
