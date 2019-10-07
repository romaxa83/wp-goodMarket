<?php
use yii\helpers\Url;
?>

<section class="hero-sections col-xl-9 col-xxl-10">
    <div class="container">
        <div class="slider-wrapper offer-slider">
            <div class="swiper-container gallery-top">
                <div class="swiper-wrapper">
                    <?php foreach ($banners as $k => $v): ?>
                        <?php $lang = array_key_first($v['bannerLang'])?>
                        <?php if (isset($v['bannerLang'][$lang]['media']['header_resolutions'])): ?>
                            <?php $banner = $v['bannerLang'][$lang] ?>
                            <div class="swiper-slide">
                                <div class="offer-card">
                                    <div class="offer-card__img">
                                        <picture>
                                            <source
                                                    media="(min-width: 1500px)"
                                                    srcset="<?php echo Url::to('/admin/' . $banner['media']['header_resolutions']['xxl'], TRUE); ?>"
                                            />
                                            <source
                                                    media="(min-width: 1200px)"
                                                    srcset="<?php echo Url::to('/admin/' . $banner['media']['header_resolutions']['xl'], TRUE); ?>"
                                            />
                                            <source
                                                    media="(min-width: 768px)"
                                                    srcset="<?php echo Url::to('/admin/' . $banner['media']['header_resolutions']['md'], TRUE); ?>"
                                            />
                                            <img
                                                    src="<?php echo Url::to('/admin/' . $banner['media']['header_resolutions']['sm'], TRUE); ?>"
                                                    alt="<?php echo $banner['media']['alt'] ?>"
                                                    title="banner"
                                            />
                                        </picture>
                                    </div>
                                    <div class="offer-card__wrapper">
                                        <div class="offer-card__desc text-center">
                                            <h2>
                                                <?php echo $banner['title'] ?>
                                            </h2>
                                            <?php echo $banner['text'] ?>
                                        </div>
                                        <div class="offer-card__link text-center text-md-left">
                                            <a href="<?php echo Url::to($banner['alias'], TRUE); ?>" class="btn btn-outline-primary">
                                                За покупками
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
        <div class="slider-wrapper card-categories-slider">
            <div class="swiper-container gallery-thumbs">
                <div class="swiper-wrapper">
                    <?php foreach ($banners as $k => $v): ?>
                        <?php $lang = array_key_first($v['bannerLang'])?>
                        <?php if (isset($v['bannerLang'][$lang]['media']['header_resolutions'])): ?>
                            <?php $banner = $v['bannerLang'][$lang] ?>
                            <div class="swiper-slide">
                                <div class="card-categories">
                                    <a href="#" class="card-categories__link"></a>
                                    <div class="card-categories__img">
                                        <picture>
                                            <img
                                                    src="<?php echo Url::to('/admin/' . $banner['media']['header_resolutions']['slider-thumb'], TRUE); ?>"
                                                    alt="<?php echo $banner['media']['alt'] ?>"
                                                    title="product category"
                                            />
                                        </picture>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-pagination d-none"></div>
            </div>
        </div>
    </div>
</section>
