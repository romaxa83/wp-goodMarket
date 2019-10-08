<?php 
use yii\helpers\Url;
?>

<section class="goods-section novelties">
    <div class="container">
        <div class="goods-section__desc text-center">
            <h2>
                Новинки
            </h2>
            <div class="goods-section__bg-elem"></div>
        </div>
        <div class="slider-wrapper">
            <div
                class="swiper-container"
                data-breakpoints='{
                "200": { "slidesPerView": "1", "spaceBetween": "0" },
                "371": { "slidesPerView": "2", "spaceBetween": "10" },
                "390": {"slidesPerView": "2", "spaceBetween": "30" },
                "768": {"slidesPerView": "3", "spaceBetween": "30"},
                "1200": {"slidesPerView": "5", "spaceBetween": "4"} }'
                data-media="(min-width: 200px)"
            >
                <div class="swiper-wrapper">
                    <?php foreach($product as $oneProduct) : ?>
                    <div class="swiper-slide">
                        <div class="card product-card">
                            <!-- <div class="product-card__status-wrap">
                                <div class="product-card__status product-card__status--new">
                                    <p>new</p>
                                </div>
                                <div class="product-card__status product-card__status--sale">
                                    <p>-20%</p>
                                </div>
                            </div> -->
                            <button class="product-card__favorites">
                                <svg width="27" height="24">
                                    <use xlink:href="/img/spritemap.svg#sprite-heart-outline"></use>
                                </svg>
                            </button>
                            <div class="product-card__img">
                                <picture>
                                    <img
                                        src="<?= '/admin' . $oneProduct['url'] ?>"
                                        alt="product card img"
                                        title="product card img"
                                    />
                                </picture>
                            </div>
                            <div class="product-card__body">
                                <div class="product-card__title">
                                    <a href="<?= Url::to('product/' . $oneProduct['alias']) ?>" class="product-card__goods-link"></a>
                                    <p><?= $oneProduct['name'] ?></p>
                                </div>
                                <div class="product-card__reviews d-flex justify-content-between">
                                    <div class="product-card__reviews-stars reviews-stars">
                                        <input
                                            type="hidden"
                                            class="rating"
                                            disabled
                                            value="1"
                                        />
                                    </div>
                                    <div class="product-card__reviews-link">
                                        <a href="#">127 отзывов</a>
                                    </div>
                                </div>
                                <div class="product-card__price d-flex justify-content-between">
                                    <div class="product-card__price--actual">
                                        <p><?= number_format($oneProduct['price'],2,',',' ') ?></p>
                                    </div>
                                    <div class="product-card__cart open-popup" data-popup="#cartPopup">
                                        <button>
                                            <!-- <svg width="24" height="22">
                                                <use xlink:href="/img/spritemap.svg#sprite-shopping-cart"></use>
                                            </svg> add class product-card__cart--added  -->
                                            Купить
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <div class="swiper-pagination d-xl-none"></div>
            </div>
        </div>
    </div>
</section>