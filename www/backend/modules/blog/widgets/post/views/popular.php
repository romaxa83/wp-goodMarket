<?php

use backend\modules\blog\helpers\ImageHelper;
use yii\helpers\StringHelper;
use yii\helpers\Url;

?>
<?php if ($data): ?>
    <section class="section section-ways">
        <div class="container">
            <div class="section-header header-text-right" id="line-2">
                <div class="row">
                    <div class="offset-1 offset-md-4 offset-lg-6 col-11 col-md-8 col-lg-6 header-title" id="animate2">
                        <span class="header-title__text">Популярные</span>
                        <div class="header-title__title">Новости</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5 m-0 ways-row">
            <div class="col-12 col-md-6 p-0 ways-wrapper way-animate-1">
                <div class="way way-h-bg">
                    <div class="way__img"
                         style="background: url(<?= ImageHelper::frontendImg(($data[0])) ?>) center / cover no-repeat">
                    </div>
                    <div class="way__description">
                        <div class="way-title"><a href="<?= Url::to(['blog/post/' . ($data[0])->alias], true) ?>"
                                                 class="way-title"><?= ($data[0])->title ?></a></div>
                        <div class="way-description"><?php echo $data[0]->description; ?></div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 p-0 way-animate-2">
                <div class="row m-0">
                    <div class="col-12 col-md-6 p-0">
                        <div class="way way-h-md">
                            <div class="way__img"
                                 style="background: url(<?= ImageHelper::frontendImg(($data[1])) ?>) center / cover no-repeat"></div>
                            <div class="way__description">
                                <div class="way-title"><a href="<?= Url::to(['blog/post/' . ($data[1])->alias], true) ?>"
                                                         class="way-title"><?= ($data[1])->title ?></a></div>
                                <div class="way-description"><?php echo $data[1]->description; ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 p-0">
                        <div class="way way-h-md">
                            <div class="way__img"
                                 style="background: url(<?= ImageHelper::frontendImg(($data[2])) ?>) center / cover no-repeat"></div>
                            <div class="way__description">
                                <div class="way-title"><a href="<?= Url::to(['blog/post/' . ($data[2])->alias], true) ?>"
                                                         class="way-title"><?= ($data[2])->title ?></a></div>
                                <div class="way-description"><?php echo $data[2]->description; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="way way-h-md">
                    <div class="way__img"
                         style="background: url(<?= ImageHelper::frontendImg(($data[3])) ?>) center / cover no-repeat"></div>
                    <div class="way__description">
                        <div class="way-title"><a href="<?= Url::to(['blog/post/' . ($data[3])->alias], true) ?>"
                                                 class="way-title"><?= ($data[3])->title ?></a></div>
                        <div class="way-description"><?php echo $data[3]->description; ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="<?php echo Url::to('/blog', TRUE); ?>" class="btn-regular btn-size-m button-isi">все новости</a>
        </div>
    </section>
<?php endif; ?>
