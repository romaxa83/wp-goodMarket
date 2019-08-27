<div class="row row-affix">
    <div class="col-md-offset-6 col-md-6 product-col my-prod-col">
        <div class="cell-add no-bg my-cell-add d-flex flex-wrap-lg w-100 mt-0">
            <?php foreach ($data as $one):?>
                <div class="delivery cell-pile">
                    <div class="img-wrap">
                        <img src="<?= $one->image?>" class="img-responsive">
                    </div>
                    <div class="text-wrap">
                        <h4 class="cell-title"><?= $one->title?></h4>
                        <?php if(strpos($one->description,'|')):?>
                            <?php foreach(explode('|',$one->description) as $item):?>
                                <p><?= $item?></p>
                            <?php endforeach;?>
                        <?php else:?>
                            <p><?= $one->description?></p>
                        <?php endif;?>
                    </div>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</div>