<?php
use backend\modules\banners\widgets\sliderwidget\BannerSliderWidget;
use backend\modules\blog\widgets\blogWidget\BlogWidget;
use backend\modules\category\widgets\categoryWidget\CategoryWidget;
?>

<div class="hero-sections-wrapper">
    <div class="hero-sections-row flex-nowrap">
        <div class="category-section col-xl-3 col-xxl-2 d-none d-xl-block">
            <?= CategoryWidget::widget(); ?>
        </div>
        <?php echo BannerSliderWidget::widget();?>
    </div>
</div>

<section class="goods-section top-sales">
    <div class="container">
        <div class="goods-section__desc text-center">
            <h2>
                Топ продаж
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
                    <div class="swiper-slide">
                        <div class="card product-card">
                            <div class="product-card__status-wrap">
                                <div
                                        class="product-card__status product-card__status--new"
                                >
                                    <p>new</p>
                                </div>
                                <div
                                        class="product-card__status product-card__status--sale"
                                >
                                    <p>-20%</p>
                                </div>
                            </div>
                            <button class="product-card__favorites">
                                <svg width="27" height="24">
                                    <use
                                            xlink:href="/img/spritemap.svg#sprite-heart-outline"
                                    ></use>
                                </svg>
                            </button>
                            <div class="product-card__img">
                                <picture>
                                    <source
                                            type="image/webp"
                                            srcset="/img/product-card-img.webp"
                                    />
                                    <img
                                            src="/img/product-card-img.jpg"
                                            alt="product card img"
                                            title="product card img"
                                    />
                                </picture>
                            </div>
                            <div class="product-card__body">
                                <div class="product-card__title">
                                    <a href="#" class="product-card__goods-link"></a>
                                    <p>
                                        Название товара, очень длинное название товара
                                    </p>
                                </div>
                                <div
                                        class="product-card__reviews d-flex justify-content-between"
                                >
                                    <div class="product-card__reviews-stars reviews-stars">
                                        <input
                                                type="hidden"
                                                class="rating"
                                                disabled
                                                value="2"
                                        />
                                    </div>
                                    <div class="product-card__reviews-link">
                                        <a href="#">127 отзывов</a>
                                    </div>
                                </div>
                                <div
                                        class="product-card__price d-flex justify-content-between"
                                >
                                    <div class="product-card__price--actual">
                                        <p>
                                            11 100 грн
                                            <del>120 грн</del>
                                        </p>
                                    </div>
                                    <div
                                            class="product-card__cart product-card__cart--added open-popup"
                                            data-popup="#cartPopup"
                                    >
                                        <button>
                                            <svg width="24" height="22">
                                                <use
                                                        xlink:href="/img/spritemap.svg#sprite-shopping-cart"
                                                ></use>
                                            </svg>
                                            Купить
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="card product-card">
                            <div class="product-card__status-wrap">
                                <div
                                        class="product-card__status product-card__status--new"
                                >
                                    <p>new</p>
                                </div>
                            </div>
                            <button class="product-card__favorites active">
                                <svg width="27" height="24">
                                    <use
                                            xlink:href="/img/spritemap.svg#sprite-heart-outline"
                                    ></use>
                                </svg>
                            </button>
                            <div class="product-card__img">
                                <picture>
                                    <source
                                            type="image/webp"
                                            srcset="/img/product-card-img.webp"
                                    />
                                    <img
                                            src="/img/product-card-img.jpg"
                                            alt="product card img"
                                            title="product card img"
                                    />
                                </picture>
                            </div>
                            <div class="product-card__body">
                                <div class="product-card__title">
                                    <a href="#" class="product-card__goods-link"></a>
                                    <p>
                                        Название товара, очень длинное название товара
                                    </p>
                                </div>
                                <div
                                        class="product-card__reviews d-flex justify-content-between"
                                >
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
                                <div
                                        class="product-card__price d-flex justify-content-between"
                                >
                                    <div class="product-card__price--not-available">
                                        <p>Нет в наличии</p>
                                    </div>
                                    <div
                                            class="product-card__cart product-card__cart--not-available"
                                    >
                                        <button type="button">
                                            <svg width="24" height="22">
                                                <use
                                                        xlink:href="/img/spritemap.svg#sprite-shopping-cart"
                                                ></use>
                                            </svg>
                                            Предзаказ
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="card product-card">
                            <div class="product-card__status-wrap">
                                <div
                                        class="product-card__status product-card__status--sale"
                                >
                                    <p>-20%</p>
                                </div>
                            </div>
                            <button class="product-card__favorites">
                                <svg width="27" height="24">
                                    <use
                                            xlink:href="/img/spritemap.svg#sprite-heart-outline"
                                    ></use>
                                </svg>
                            </button>
                            <div class="product-card__img">
                                <picture>
                                    <source
                                            type="image/webp"
                                            srcset="/img/product-card-img.webp"
                                    />
                                    <img
                                            src="/img/product-card-img.jpg"
                                            alt="product card img"
                                            title="product card img"
                                    />
                                </picture>
                            </div>
                            <div class="product-card__body">
                                <div class="product-card__title">
                                    <a href="#" class="product-card__goods-link"></a>
                                    <p>
                                        Название товара, очень длинное название товара
                                    </p>
                                </div>
                                <div
                                        class="product-card__reviews d-flex justify-content-between"
                                >
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
                                <div
                                        class="product-card__price d-flex justify-content-between"
                                >
                                    <div class="product-card__price--actual">
                                        <p>
                                            11 100 грн
                                            <del>120 грн</del>
                                        </p>
                                    </div>
                                    <div
                                            class="product-card__cart open-popup"
                                            data-popup="#cartPopup"
                                    >
                                        <button>
                                            <svg width="24" height="22">
                                                <use
                                                        xlink:href="/img/spritemap.svg#sprite-shopping-cart"
                                                ></use>
                                            </svg>
                                            Купить
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="card product-card">
                            <div class="product-card__status-wrap">
                                <div
                                        class="product-card__status product-card__status--new"
                                >
                                    <p>new</p>
                                </div>
                            </div>
                            <button class="product-card__favorites">
                                <svg width="27" height="24">
                                    <use
                                            xlink:href="/img/spritemap.svg#sprite-heart-outline"
                                    ></use>
                                </svg>
                            </button>
                            <div class="product-card__img">
                                <picture>
                                    <source
                                            type="image/webp"
                                            srcset="/img/product-card-img.webp"
                                    />
                                    <img
                                            src="/img/product-card-img.jpg"
                                            alt="product card img"
                                            title="product card img"
                                    />
                                </picture>
                            </div>
                            <div class="product-card__body">
                                <div class="product-card__title">
                                    <a href="#" class="product-card__goods-link"></a>
                                    <p>
                                        Название товара, очень длинное название товара
                                    </p>
                                </div>
                                <div
                                        class="product-card__reviews d-flex justify-content-between"
                                >
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
                                <div
                                        class="product-card__price d-flex justify-content-between"
                                >
                                    <div class="product-card__price--not-available">
                                        <p>Нет в наличии</p>
                                    </div>
                                    <div
                                            class="product-card__cart product-card__cart--not-available"
                                    >
                                        <button type="button">
                                            <svg width="24" height="22">
                                                <use
                                                        xlink:href="/img/spritemap.svg#sprite-shopping-cart"
                                                ></use>
                                            </svg>
                                            Предзаказ
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="card product-card">
                            <div class="product-card__status-wrap">
                                <div
                                        class="product-card__status product-card__status--new"
                                >
                                    <p>new</p>
                                </div>
                            </div>
                            <button class="product-card__favorites">
                                <svg width="27" height="24">
                                    <use
                                            xlink:href="/img/spritemap.svg#sprite-heart-outline"
                                    ></use>
                                </svg>
                            </button>
                            <div class="product-card__img">
                                <picture>
                                    <source
                                            type="image/webp"
                                            srcset="/img/product-card-img.webp"
                                    />
                                    <img
                                            src="/img/product-card-img.jpg"
                                            alt="product card img"
                                            title="product card img"
                                    />
                                </picture>
                            </div>
                            <div class="product-card__body">
                                <div class="product-card__title">
                                    <a href="#" class="product-card__goods-link"></a>
                                    <p>
                                        Название товара, очень длинное название товара
                                    </p>
                                </div>
                                <div
                                        class="product-card__reviews d-flex justify-content-between"
                                >
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
                                <div
                                        class="product-card__price d-flex justify-content-between"
                                >
                                    <div class="product-card__price--not-available">
                                        <p>Нет в наличии</p>
                                    </div>
                                    <div
                                            class="product-card__cart product-card__cart--not-available"
                                    >
                                        <button type="button">
                                            <svg width="24" height="22">
                                                <use
                                                        xlink:href="/img/spritemap.svg#sprite-shopping-cart"
                                                ></use>
                                            </svg>
                                            Предзаказ
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination d-xl-none"></div>
            </div>
        </div>
    </div>
</section>

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
                    <div class="swiper-slide">
                        <div class="card product-card">
                            <div class="product-card__status-wrap">
                                <div
                                        class="product-card__status product-card__status--new"
                                >
                                    <p>new</p>
                                </div>
                                <div
                                        class="product-card__status product-card__status--sale"
                                >
                                    <p>-20%</p>
                                </div>
                            </div>
                            <button class="product-card__favorites active">
                                <svg width="27" height="24">
                                    <use
                                            xlink:href="/img/spritemap.svg#sprite-heart-outline"
                                    ></use>
                                </svg>
                            </button>
                            <div class="product-card__img">
                                <picture>
                                    <source
                                            type="image/webp"
                                            srcset="/img/product-card-img.webp"
                                    />
                                    <img
                                            src="/img/product-card-img.jpg"
                                            alt="product card img"
                                            title="product card img"
                                    />
                                </picture>
                            </div>
                            <div class="product-card__body">
                                <div class="product-card__title">
                                    <a href="#" class="product-card__goods-link"></a>
                                    <p>
                                        Название товара, очень длинное название товара
                                    </p>
                                </div>
                                <div
                                        class="product-card__reviews d-flex justify-content-between"
                                >
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
                                <div
                                        class="product-card__price d-flex justify-content-between"
                                >
                                    <div class="product-card__price--actual">
                                        <p>
                                            11 100 грн
                                            <del>120 грн</del>
                                        </p>
                                    </div>
                                    <div
                                            class="product-card__cart product-card__cart--added open-popup"
                                            data-popup="#cartPopup"
                                    >
                                        <button>
                                            <svg width="24" height="22">
                                                <use
                                                        xlink:href="/img/spritemap.svg#sprite-shopping-cart"
                                                ></use>
                                            </svg>
                                            Купить
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="card product-card">
                            <div class="product-card__status-wrap">
                                <div
                                        class="product-card__status product-card__status--new"
                                >
                                    <p>new</p>
                                </div>
                            </div>
                            <button class="product-card__favorites">
                                <svg width="27" height="24">
                                    <use
                                            xlink:href="/img/spritemap.svg#sprite-heart-outline"
                                    ></use>
                                </svg>
                            </button>
                            <div class="product-card__img">
                                <picture>
                                    <source
                                            type="image/webp"
                                            srcset="/img/product-card-img.webp"
                                    />
                                    <img
                                            src="/img/product-card-img.jpg"
                                            alt="product card img"
                                            title="product card img"
                                    />
                                </picture>
                            </div>
                            <div class="product-card__body">
                                <div class="product-card__title">
                                    <a href="#" class="product-card__goods-link"></a>
                                    <p>
                                        Название товара, очень длинное название товара
                                    </p>
                                </div>
                                <div
                                        class="product-card__reviews d-flex justify-content-between"
                                >
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
                                <div
                                        class="product-card__price d-flex justify-content-between"
                                >
                                    <div class="product-card__price--not-available">
                                        <p>Нет в наличии</p>
                                    </div>
                                    <div
                                            class="product-card__cart product-card__cart--not-available"
                                    >
                                        <button type="button">
                                            <svg width="24" height="22">
                                                <use
                                                        xlink:href="/img/spritemap.svg#sprite-shopping-cart"
                                                ></use>
                                            </svg>
                                            Предзаказ
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="card product-card">
                            <div class="product-card__status-wrap">
                                <div
                                        class="product-card__status product-card__status--new"
                                >
                                    <p>new</p>
                                </div>
                                <div
                                        class="product-card__status product-card__status--sale"
                                >
                                    <p>-20%</p>
                                </div>
                            </div>
                            <button class="product-card__favorites">
                                <svg width="27" height="24">
                                    <use
                                            xlink:href="/img/spritemap.svg#sprite-heart-outline"
                                    ></use>
                                </svg>
                            </button>
                            <div class="product-card__img">
                                <picture>
                                    <source
                                            type="image/webp"
                                            srcset="/img/product-card-img.webp"
                                    />
                                    <img
                                            src="/img/product-card-img.jpg"
                                            alt="product card img"
                                            title="product card img"
                                    />
                                </picture>
                            </div>
                            <div class="product-card__body">
                                <div class="product-card__title">
                                    <a href="#" class="product-card__goods-link"></a>
                                    <p>
                                        Название товара, очень длинное название товара
                                    </p>
                                </div>
                                <div
                                        class="product-card__reviews d-flex justify-content-between"
                                >
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
                                <div
                                        class="product-card__price d-flex justify-content-between"
                                >
                                    <div class="product-card__price--actual">
                                        <p>
                                            11 100 грн
                                            <del>120 грн</del>
                                        </p>
                                    </div>
                                    <div
                                            class="product-card__cart open-popup"
                                            data-popup="#cartPopup"
                                    >
                                        <button>
                                            <svg width="24" height="22">
                                                <use
                                                        xlink:href="/img/spritemap.svg#sprite-shopping-cart"
                                                ></use>
                                            </svg>
                                            Купить
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="card product-card">
                            <div class="product-card__status-wrap">
                                <div
                                        class="product-card__status product-card__status--new"
                                >
                                    <p>new</p>
                                </div>
                            </div>
                            <button class="product-card__favorites active">
                                <svg width="27" height="24">
                                    <use
                                            xlink:href="/img/spritemap.svg#sprite-heart-outline"
                                    ></use>
                                </svg>
                            </button>
                            <div class="product-card__img">
                                <picture>
                                    <source
                                            type="image/webp"
                                            srcset="/img/product-card-img.webp"
                                    />
                                    <img
                                            src="/img/product-card-img.jpg"
                                            alt="product card img"
                                            title="product card img"
                                    />
                                </picture>
                            </div>
                            <div class="product-card__body">
                                <div class="product-card__title">
                                    <a href="#" class="product-card__goods-link"></a>
                                    <p>
                                        Название товара, очень длинное название товара
                                    </p>
                                </div>
                                <div
                                        class="product-card__reviews d-flex justify-content-between"
                                >
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
                                <div
                                        class="product-card__price d-flex justify-content-between"
                                >
                                    <div class="product-card__price--not-available">
                                        <p>Нет в наличии</p>
                                    </div>
                                    <div
                                            class="product-card__cart product-card__cart--not-available"
                                    >
                                        <button type="button">
                                            <svg width="24" height="22">
                                                <use
                                                        xlink:href="/img/spritemap.svg#sprite-shopping-cart"
                                                ></use>
                                            </svg>
                                            Предзаказ
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="card product-card">
                            <div class="product-card__status-wrap">
                                <div
                                        class="product-card__status product-card__status--new"
                                >
                                    <p>new</p>
                                </div>
                            </div>
                            <button class="product-card__favorites">
                                <svg width="27" height="24">
                                    <use
                                            xlink:href="/img/spritemap.svg#sprite-heart-outline"
                                    ></use>
                                </svg>
                            </button>
                            <div class="product-card__img">
                                <picture>
                                    <source
                                            type="image/webp"
                                            srcset="/img/product-card-img.webp"
                                    />
                                    <img
                                            src="/img/product-card-img.jpg"
                                            alt="product card img"
                                            title="product card img"
                                    />
                                </picture>
                            </div>
                            <div class="product-card__body">
                                <div class="product-card__title">
                                    <a href="#" class="product-card__goods-link"></a>
                                    <p>
                                        Название товара, очень длинное название товара
                                    </p>
                                </div>
                                <div
                                        class="product-card__reviews d-flex justify-content-between"
                                >
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
                                <div
                                        class="product-card__price d-flex justify-content-between"
                                >
                                    <div class="product-card__price--not-available">
                                        <p>Нет в наличии</p>
                                    </div>
                                    <div
                                            class="product-card__cart product-card__cart--not-available"
                                    >
                                        <button type="button">
                                            <svg width="24" height="22">
                                                <use
                                                        xlink:href="/img/spritemap.svg#sprite-shopping-cart"
                                                ></use>
                                            </svg>
                                            Предзаказ
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination d-xl-none"></div>
            </div>
        </div>
    </div>
</section>

<div class="benefits">
    <div class="container">
        <div class="slider-wrapper">
            <div
                    class="swiper-container"
                    data-breakpoints='{
          "200": { "slidesPerView": "auto", "spaceBetween": "80px", "loop": "true",
          "centeredSlides": "true" },
          "768": {"slidesPerView": "4"} }'
                    data-media="(max-width: 768px)"
            >
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="benefits-card text-center text-xl-left">
                            <div class="benefits-card__img">
                                <svg width="65" height="51">
                                    <use xlink:href="/img/spritemap.svg#sprite-tag"></use>
                                </svg>
                            </div>
                            <div class="benefits-card__desc">
                                <p>
                                    <b>Lorem ipsum</b>
                                    dolor sit amet, consectetur
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="benefits-card text-center text-xl-left">
                            <div class="benefits-card__img">
                                <svg width="75" height="51">
                                    <use
                                            xlink:href="/img/spritemap.svg#sprite-delivery"
                                    ></use>
                                </svg>
                            </div>
                            <div class="benefits-card__desc">
                                <p>
                                    <b>Lorem ipsum</b>
                                    dolor sit amet, consectetur
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="benefits-card text-center text-xl-left">
                            <div class="benefits-card__img">
                                <svg width="59" height="51">
                                    <use xlink:href="/img/spritemap.svg#sprite-list"></use>
                                </svg>
                            </div>
                            <div class="benefits-card__desc">
                                <p>
                                    <b>Lorem ipsum</b>
                                    dolor sit amet, consectetur
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="benefits-card text-center text-xl-left">
                            <div class="benefits-card__img">
                                <svg width="61" height="51">
                                    <use
                                            xlink:href="/img/spritemap.svg#sprite-headphones"
                                    ></use>
                                </svg>
                            </div>
                            <div class="benefits-card__desc">
                                <p>
                                    <b>Lorem ipsum</b>
                                    dolor sit amet, consectetur
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-pagination d-md-none"></div>
            </div>
        </div>
    </div>
</div>

<div class="goods-section product-categories">
    <div class="container">
        <div class="goods-section__desc product-categories__desc text-center">
            <h2>
                Товары для дома
            </h2>
            <div class="goods-section__bg-elem"></div>
        </div>
        <div class="d-xl-flex justify-content-xl-between">
            <div class="card-stock col-xl-4 px-0">
                <a href="#" class="card-stock__link"></a>
                <div class="card-stock__img">
                    <picture>
                        <source
                                media="(min-width: 1200px)"
                                type="image/webp"
                                srcset="/img/stock-img_xl.webp"
                        />
                        <source
                                media="(min-width: 1200px)"
                                srcset="/img/stock-img_xl.jpg"
                        />
                        <!--	<source
              media="(min-width: 768px)"
              type="image/webp"
              srcset="<%= require('./assets//img/top_banner-md.webp') %>"
            />
            <source
              media="(min-width: 768px)"
              srcset="<%= require('./assets//img/top_banner-md.jpg') %>"
            /> -->
                        <source
                                type="image/webp"
                                srcset="/img/stock-img_sm.webp"
                        />
                        <img
                                src="/img/stock-img_sm.jpg"
                                alt="top banner"
                                title="top banner"
                                class="top-banner__img"
                        />
                    </picture>
                </div>
                <div
                        class="card-stock__desc card-stock__desc--bg-secondary text-center"
                >
                    <h3>
                        Скидки на товары для дома
                    </h3>
                    <p>
                        50% на домашний текстиль
                    </p>
                </div>
            </div>
            <div class="slider-wrapper col-xl-8 px-0">
                <div
                        class="swiper-container"
                        data-breakpoints='{
        "200": { "slidesPerView": "1", "spaceBetween": "0" },
        "371": { "slidesPerView": "2", "spaceBetween": "10" },
        "390": {"slidesPerView": "2", "spaceBetween": "30" },
        "768": {"slidesPerView": "3", "spaceBetween": "30"},
        "1200": {"slidesPerView": "4", "spaceBetween": "10"} }'
                        data-media="(min-width: 200px)"
                >
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="card product-card">
                                <div class="product-card__status-wrap">
                                    <div
                                            class="product-card__status product-card__status--new"
                                    >
                                        <p>new</p>
                                    </div>
                                    <div
                                            class="product-card__status product-card__status--sale"
                                    >
                                        <p>-20%</p>
                                    </div>
                                </div>
                                <button class="product-card__favorites">
                                    <svg width="27" height="24">
                                        <use
                                                xlink:href="/img/spritemap.svg#sprite-heart-outline"
                                        ></use>
                                    </svg>
                                </button>
                                <div class="product-card__img">
                                    <picture>
                                        <source
                                                type="image/webp"
                                                srcset="/img/product-card-img.webp"
                                        />
                                        <img
                                                src="/img/product-card-img.jpg"
                                                alt="product card img"
                                                title="product card img"
                                        />
                                    </picture>
                                </div>
                                <div class="product-card__body">
                                    <div class="product-card__title">
                                        <a href="#" class="product-card__goods-link"></a>
                                        <p>
                                            Название товара, очень длинное название товара
                                        </p>
                                    </div>
                                    <div
                                            class="product-card__reviews d-flex justify-content-between"
                                    >
                                        <div
                                                class="product-card__reviews-stars reviews-stars"
                                        >
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
                                    <div
                                            class="product-card__price d-flex justify-content-between"
                                    >
                                        <div class="product-card__price--not-available">
                                            <p>Нет в наличии</p>
                                        </div>
                                        <div
                                                class="product-card__cart product-card__cart--not-available"
                                        >
                                            <button>
                                                <svg width="24" height="22">
                                                    <use
                                                            xlink:href="/img/spritemap.svg#sprite-shopping-cart"
                                                    ></use>
                                                </svg>
                                                Предзаказ
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="card product-card">
                                <div class="product-card__status-wrap">
                                    <div
                                            class="product-card__status product-card__status--new"
                                    >
                                        <p>new</p>
                                    </div>
                                    <div
                                            class="product-card__status product-card__status--sale"
                                    >
                                        <p>-20%</p>
                                    </div>
                                </div>
                                <button class="product-card__favorites">
                                    <svg width="27" height="24">
                                        <use
                                                xlink:href="/img/spritemap.svg#sprite-heart-outline"
                                        ></use>
                                    </svg>
                                </button>
                                <div class="product-card__img">
                                    <picture>
                                        <source
                                                type="image/webp"
                                                srcset="/img/product-card-img.webp"
                                        />
                                        <img
                                                src="/img/product-card-img.jpg"
                                                alt="product card img"
                                                title="product card img"
                                        />
                                    </picture>
                                </div>
                                <div class="product-card__body">
                                    <div class="product-card__title">
                                        <a href="#" class="product-card__goods-link"></a>
                                        <p>
                                            Название товара, очень длинное название товара
                                        </p>
                                    </div>
                                    <div
                                            class="product-card__reviews d-flex justify-content-between"
                                    >
                                        <div
                                                class="product-card__reviews-stars reviews-stars"
                                        >
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
                                    <div
                                            class="product-card__price d-flex justify-content-between"
                                    >
                                        <div class="product-card__price--actual">
                                            <p>
                                                11 100 грн
                                                <del>120 грн</del>
                                            </p>
                                        </div>
                                        <div
                                                class="product-card__cart product-card__cart--added open-popup"
                                                data-popup="#cartPopup"
                                        >
                                            <button>
                                                <svg width="24" height="22">
                                                    <use
                                                            xlink:href="/img/spritemap.svg#sprite-shopping-cart"
                                                    ></use>
                                                </svg>
                                                Купить
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="card product-card">
                                <div class="product-card__status-wrap">
                                    <div
                                            class="product-card__status product-card__status--new"
                                    >
                                        <p>new</p>
                                    </div>
                                    <div
                                            class="product-card__status product-card__status--sale"
                                    >
                                        <p>-20%</p>
                                    </div>
                                </div>
                                <button class="product-card__favorites active">
                                    <svg width="27" height="24">
                                        <use
                                                xlink:href="/img/spritemap.svg#sprite-heart-outline"
                                        ></use>
                                    </svg>
                                </button>
                                <div class="product-card__img">
                                    <picture>
                                        <source
                                                type="image/webp"
                                                srcset="/img/product-card-img.webp"
                                        />
                                        <img
                                                src="/img/product-card-img.jpg"
                                                alt="product card img"
                                                title="product card img"
                                        />
                                    </picture>
                                </div>
                                <div class="product-card__body">
                                    <div class="product-card__title">
                                        <a href="#" class="product-card__goods-link"></a>
                                        <p>
                                            Название товара, очень длинное название товара
                                        </p>
                                    </div>
                                    <div
                                            class="product-card__reviews d-flex justify-content-between"
                                    >
                                        <div
                                                class="product-card__reviews-stars reviews-stars"
                                        >
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
                                    <div
                                            class="product-card__price d-flex justify-content-between"
                                    >
                                        <div class="product-card__price--not-available">
                                            <p>Нет в наличии</p>
                                        </div>
                                        <div
                                                class="product-card__cart product-card__cart--not-available"
                                        >
                                            <button>
                                                <svg width="24" height="22">
                                                    <use
                                                            xlink:href="/img/spritemap.svg#sprite-shopping-cart"
                                                    ></use>
                                                </svg>
                                                Пердзаказ
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="card product-card">
                                <div class="product-card__status-wrap">
                                    <div
                                            class="product-card__status product-card__status--new"
                                    >
                                        <p>new</p>
                                    </div>
                                    <div
                                            class="product-card__status product-card__status--sale"
                                    >
                                        <p>-20%</p>
                                    </div>
                                </div>
                                <button class="product-card__favorites">
                                    <svg width="27" height="24">
                                        <use
                                                xlink:href="/img/spritemap.svg#sprite-heart-outline"
                                        ></use>
                                    </svg>
                                </button>
                                <div class="product-card__img">
                                    <picture>
                                        <source
                                                type="image/webp"
                                                srcset="/img/product-card-img.webp"
                                        />
                                        <img
                                                src="/img/product-card-img.jpg"
                                                alt="product card img"
                                                title="product card img"
                                        />
                                    </picture>
                                </div>
                                <div class="product-card__body">
                                    <div class="product-card__title">
                                        <a href="#" class="product-card__goods-link"></a>
                                        <p>
                                            Название товара, очень длинное название товара
                                        </p>
                                    </div>
                                    <div
                                            class="product-card__reviews d-flex justify-content-between"
                                    >
                                        <div
                                                class="product-card__reviews-stars reviews-stars"
                                        >
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
                                    <div
                                            class="product-card__price d-flex justify-content-between"
                                    >
                                        <div class="product-card__price--actual">
                                            <p>
                                                11 100 грн
                                                <del>120 грн</del>
                                            </p>
                                        </div>
                                        <div
                                                class="product-card__cart product-card__cart--added open-popup"
                                                data-popup="#cartPopup"
                                        >
                                            <button>
                                                <svg width="24" height="22">
                                                    <use
                                                            xlink:href="/img/spritemap.svg#sprite-shopping-cart"
                                                    ></use>
                                                </svg>
                                                Купить
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
                <div class="reviews-product-card">
                    <div class="row justify-content-xxl-between">
                        <div
                                class="reviews-product-card__desc-wrapper d-flex col-md-6 col-xxl-4"
                        >
                            <div class="product-card__img reviews-product-card__img">
                                <picture>
                                    <source
                                            type="image/webp"
                                            srcset="/img/product-card-img.webp"
                                    />
                                    <img
                                            src="/img/product-card-img.jpg"
                                            alt="product card img"
                                            title="product card img"
                                    />
                                </picture>
                            </div>
                            <div class="reviews-product-card__body d-flex flex-column">
                                <div
                                        class="product-card__title reviews-product-card__title"
                                >
                                    <a href="#" class="product-card__goods-link"></a>
                                    <p>
                                        Название товара, очень длинное
                                    </p>
                                </div>
                                <div
                                        class="d-flex justify-content-between flex-md-column flex-xl-row reviews-product-card__price-wrapper"
                                >
                                    <div
                                            class="product-card__price reviews-product-card__price d-flex justify-content-between"
                                    >
                                        <div
                                                class="product-card__price--actual  reviews-product-card__price--actual"
                                        >
                                            <p>
                                                <del>120 грн</del>
                                                11 100 грн
                                            </p>
                                        </div>
                                    </div>
                                    <div
                                            class="product-card__reviews reviews-product-card__reviews d-md-flex justify-content-md-between flex-xl-column"
                                    >
                                        <div
                                                class="product-card__reviews-stars reviews-stars"
                                        >
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
                                </div>
                            </div>
                        </div>
                        <div
                                class="reviews-product-card__reviews-wrapper col-md-6 col-xxl-8"
                        >
                            <div class="reviews-product-card__customer-review">
                                <div class="d-flex justify-content-between">
                                    <h3>
                                        Имя Фамилия
                                    </h3>
                                    <div class="product-card__reviews-stars reviews-stars">
                                        <input
                                                type="hidden"
                                                class="rating"
                                                disabled
                                                value="1"
                                        />
                                    </div>
                                </div>
                                <p>
                                    Lorem Ipsum - это текст-"рыба", часто используемый в
                                    печати и вэб-дизайне. Lorem Ipsum является стандартной
                                    "рыбой" для текстов на латинице с начала XVI века.
                                </p>
                                <a class="reviews-product-card__link text-left" href="#"
                                >подробнее</a
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="goods-section product-categories">
    <div class="container">
        <div class="goods-section__desc product-categories__desc text-center">
            <h2>
                Cад
            </h2>
            <div class="goods-section__bg-elem"></div>
        </div>
        <div class="d-xl-flex justify-content-xl-between">
            <div class="card-stock col-xl-4 px-0">
                <a href="#" class="card-stock__link"></a>
                <div class="card-stock__img">
                    <picture>
                        <source
                                media="(min-width: 1200px)"
                                type="image/webp"
                                srcset="/img/stock-img_xl.webp"
                        />
                        <source
                                media="(min-width: 1200px)"
                                srcset="/img/stock-img_xl.jpg"
                        />
                        <!--	<source
              media="(min-width: 768px)"
              type="image/webp"
              srcset="<%= require('./assets//img/top_banner-md.webp') %>"
            />
            <source
              media="(min-width: 768px)"
              srcset="<%= require('./assets//img/top_banner-md.jpg') %>"
            /> -->
                        <source
                                type="image/webp"
                                srcset="/img/stock-img_sm.webp"
                        />
                        <img
                                src="/img/stock-img_sm.jpg"
                                alt="top banner"
                                title="top banner"
                                class="top-banner__img"
                        />
                    </picture>
                </div>
                <div
                        class="card-stock__desc card-stock__desc--bg-secondary text-center"
                >
                    <h3>
                        Акционное декоративное освещение
                    </h3>
                    <p>
                        50% на уличное освещение
                    </p>
                </div>
            </div>
            <div class="slider-wrapper col-xl-8 px-0">
                <div
                        class="swiper-container"
                        data-breakpoints='{
        "200": { "slidesPerView": "1", "spaceBetween": "0" },
        "371": { "slidesPerView": "2", "spaceBetween": "10" },
        "390": {"slidesPerView": "2", "spaceBetween": "30" },
        "768": {"slidesPerView": "3", "spaceBetween": "30"},
        "1200": {"slidesPerView": "4", "spaceBetween": "0"} }'
                        data-media="(min-width: 200px)"
                >
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="card product-card">
                                <div class="product-card__status-wrap">
                                    <div
                                            class="product-card__status product-card__status--new"
                                    >
                                        <p>new</p>
                                    </div>
                                    <div
                                            class="product-card__status product-card__status--sale"
                                    >
                                        <p>-20%</p>
                                    </div>
                                </div>
                                <button class="product-card__favorites">
                                    <svg width="27" height="24">
                                        <use
                                                xlink:href="/img/spritemap.svg#sprite-heart-outline"
                                        ></use>
                                    </svg>
                                </button>
                                <div class="product-card__img">
                                    <picture>
                                        <source
                                                type="image/webp"
                                                srcset="/img/product-card-img.webp"
                                        />
                                        <img
                                                src="/img/product-card-img.jpg"
                                                alt="product card img"
                                                title="product card img"
                                        />
                                    </picture>
                                </div>
                                <div class="product-card__body">
                                    <div class="product-card__title">
                                        <a href="#" class="product-card__goods-link"></a>
                                        <p>
                                            Название товара, очень длинное название товара
                                        </p>
                                    </div>
                                    <div
                                            class="product-card__reviews d-flex justify-content-between"
                                    >
                                        <div
                                                class="product-card__reviews-stars reviews-stars"
                                        >
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
                                    <div
                                            class="product-card__price d-flex justify-content-between"
                                    >
                                        <div class="product-card__price--actual">
                                            <p>
                                                11 100 грн
                                                <del>120 грн</del>
                                            </p>
                                        </div>
                                        <div
                                                class="product-card__cart open-popup"
                                                data-popup="#cartPopup"
                                        >
                                            <button>
                                                <svg width="24" height="22">
                                                    <use
                                                            xlink:href="/img/spritemap.svg#sprite-shopping-cart"
                                                    ></use>
                                                </svg>
                                                Купить
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="card product-card">
                                <div class="product-card__status-wrap">
                                    <div
                                            class="product-card__status product-card__status--new"
                                    >
                                        <p>new</p>
                                    </div>
                                    <div
                                            class="product-card__status product-card__status--sale"
                                    >
                                        <p>-20%</p>
                                    </div>
                                </div>
                                <button class="product-card__favorites">
                                    <svg width="27" height="24">
                                        <use
                                                xlink:href="/img/spritemap.svg#sprite-heart-outline"
                                        ></use>
                                    </svg>
                                </button>
                                <div class="product-card__img">
                                    <picture>
                                        <source
                                                type="image/webp"
                                                srcset="/img/product-card-img.webp"
                                        />
                                        <img
                                                src="/img/product-card-img.jpg"
                                                alt="product card img"
                                                title="product card img"
                                        />
                                    </picture>
                                </div>
                                <div class="product-card__body">
                                    <div class="product-card__title">
                                        <a href="#" class="product-card__goods-link"></a>
                                        <p>
                                            Название товара, очень длинное название товара
                                        </p>
                                    </div>
                                    <div
                                            class="product-card__reviews d-flex justify-content-between"
                                    >
                                        <div
                                                class="product-card__reviews-stars reviews-stars"
                                        >
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
                                    <div
                                            class="product-card__price d-flex justify-content-between"
                                    >
                                        <div class="product-card__price--not-available">
                                            <p>Нет в наличии</p>
                                        </div>
                                        <div
                                                class="product-card__cart product-card__cart--not-available"
                                        >
                                            <button>
                                                <svg width="24" height="22">
                                                    <use
                                                            xlink:href="/img/spritemap.svg#sprite-shopping-cart"
                                                    ></use>
                                                </svg>
                                                Предзаказ
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="card product-card">
                                <div class="product-card__status-wrap">
                                    <div
                                            class="product-card__status product-card__status--new"
                                    >
                                        <p>new</p>
                                    </div>
                                    <div
                                            class="product-card__status product-card__status--sale"
                                    >
                                        <p>-20%</p>
                                    </div>
                                </div>
                                <button class="product-card__favorites">
                                    <svg width="27" height="24">
                                        <use
                                                xlink:href="/img/spritemap.svg#sprite-heart-outline"
                                        ></use>
                                    </svg>
                                </button>
                                <div class="product-card__img">
                                    <picture>
                                        <source
                                                type="image/webp"
                                                srcset="/img/product-card-img.webp"
                                        />
                                        <img
                                                src="<%= require('./assets//img/product-card-img.jpg') %>"
                                                alt="product card img"
                                                title="product card img"
                                        />
                                    </picture>
                                </div>
                                <div class="product-card__body">
                                    <div class="product-card__title">
                                        <a href="#" class="product-card__goods-link"></a>
                                        <p>
                                            Название товара, очень длинное название товара
                                        </p>
                                    </div>
                                    <div
                                            class="product-card__reviews d-flex justify-content-between"
                                    >
                                        <div
                                                class="product-card__reviews-stars reviews-stars"
                                        >
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
                                    <div
                                            class="product-card__price d-flex justify-content-between"
                                    >
                                        <div class="product-card__price--actual">
                                            <p>
                                                11 100 грн
                                                <del>120 грн</del>
                                            </p>
                                        </div>
                                        <div
                                                class="product-card__cart open-popup"
                                                data-popup="#cartPopup"
                                        >
                                            <button>
                                                <svg width="24" height="22">
                                                    <use
                                                            xlink:href="/img/spritemap.svg#sprite-shopping-cart"
                                                    ></use>
                                                </svg>
                                                Купить
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="card product-card">
                                <div class="product-card__status-wrap">
                                    <div
                                            class="product-card__status product-card__status--new"
                                    >
                                        <p>new</p>
                                    </div>
                                    <div
                                            class="product-card__status product-card__status--sale"
                                    >
                                        <p>-20%</p>
                                    </div>
                                </div>
                                <button class="product-card__favorites">
                                    <svg width="27" height="24">
                                        <use
                                                xlink:href="/img/spritemap.svg#sprite-heart-outline"
                                        ></use>
                                    </svg>
                                </button>
                                <div class="product-card__img">
                                    <picture>
                                        <source
                                                type="image/webp"
                                                srcset="/img/product-card-img.webp"
                                        />
                                        <img
                                                src="/img/product-card-img.jpg"
                                                alt="product card img"
                                                title="product card img"
                                        />
                                    </picture>
                                </div>
                                <div class="product-card__body">
                                    <div class="product-card__title">
                                        <a href="#" class="product-card__goods-link"></a>
                                        <p>
                                            Название товара, очень длинное название товара
                                        </p>
                                    </div>
                                    <div
                                            class="product-card__reviews d-flex justify-content-between"
                                    >
                                        <div
                                                class="product-card__reviews-stars reviews-stars"
                                        >
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
                                    <div
                                            class="product-card__price d-flex justify-content-between"
                                    >
                                        <div class="product-card__price--actual">
                                            <p>
                                                11 100 грн
                                                <del>120 грн</del>
                                            </p>
                                        </div>
                                        <div
                                                class="product-card__cart open-popup"
                                                data-popup="#cartPopup"
                                        >
                                            <button>
                                                <svg width="24" height="22">
                                                    <use
                                                            xlink:href="/img/spritemap.svg#sprite-shopping-cart"
                                                    ></use>
                                                </svg>
                                                Купить
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
                <div class="reviews-product-card">
                    <div class="row justify-content-xxl-between">
                        <div
                                class="reviews-product-card__desc-wrapper d-flex col-md-6 col-xxl-4"
                        >
                            <div class="product-card__img reviews-product-card__img">
                                <picture>
                                    <source
                                            type="image/webp"
                                            srcset="/img/product-card-img.webp"
                                    />
                                    <img
                                            src="/img/product-card-img.jpg"
                                            alt="product card img"
                                            title="product card img"
                                    />
                                </picture>
                            </div>
                            <div class="reviews-product-card__body d-flex flex-column">
                                <div
                                        class="product-card__title reviews-product-card__title"
                                >
                                    <a href="#" class="product-card__goods-link"></a>
                                    <p>
                                        Название товара, очень длинное
                                    </p>
                                </div>
                                <div
                                        class="d-flex justify-content-between flex-md-column flex-xl-row reviews-product-card__price-wrapper"
                                >
                                    <div
                                            class="product-card__price reviews-product-card__price d-flex justify-content-between"
                                    >
                                        <div
                                                class="product-card__price--actual  reviews-product-card__price--actual"
                                        >
                                            <p>
                                                <del>120 грн</del>
                                                11 100 грн
                                            </p>
                                        </div>
                                    </div>
                                    <div
                                            class="product-card__reviews reviews-product-card__reviews d-md-flex justify-content-md-between flex-xl-column"
                                    >
                                        <div
                                                class="product-card__reviews-stars reviews-stars"
                                        >
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
                                </div>
                            </div>
                        </div>
                        <div
                                class="reviews-product-card__reviews-wrapper col-md-6 col-xxl-8"
                        >
                            <div class="reviews-product-card__customer-review">
                                <div class="d-flex justify-content-between">
                                    <h3>
                                        Имя Фамилия
                                    </h3>
                                    <div class="product-card__reviews-stars reviews-stars">
                                        <input
                                                type="hidden"
                                                class="rating"
                                                disabled
                                                value="1"
                                        />
                                    </div>
                                </div>
                                <p>
                                    Lorem Ipsum - это текст-"рыба", часто используемый в
                                    печати и вэб-дизайне. Lorem Ipsum является стандартной
                                    "рыбой" для текстов на латинице с начала XVI века.
                                </p>
                                <a
                                        class="reviews-product-card__link text-left text-md-right"
                                        href="#"
                                >подробнее</a
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="goods-section product-categories">
    <div class="container">
        <div class="goods-section__desc product-categories__desc text-center">
            <h2>
                Товары для детей
            </h2>
            <div class="goods-section__bg-elem"></div>
        </div>
        <div class="d-xl-flex justify-content-xl-between">
            <div class="card-stock col-xl-4 px-0">
                <a href="#" class="card-stock__link"></a>
                <div class="card-stock__img">
                    <picture>
                        <source
                                media="(min-width: 1200px)"
                                type="image/webp"
                                srcset="/img/stock-img_xl.webp"
                        />
                        <source
                                media="(min-width: 1200px)"
                                srcset="/img/stock-img_xl.jpg"
                        />
                        <!--	<source
              media="(min-width: 768px)"
              type="image/webp"
              srcset="<%= require('./assets//img/top_banner-md.webp') %>"
            />
            <source
              media="(min-width: 768px)"
              srcset="<%= require('./assets//img/top_banner-md.jpg') %>"
            /> -->
                        <source
                                type="image/webp"
                                srcset="/img/stock-img_sm.webp"
                        />
                        <img
                                src="/img/stock-img_sm.jpg"
                                alt="top banner"
                                title="top banner"
                                class="top-banner__img"
                        />
                    </picture>
                </div>
                <div
                        class="card-stock__desc card-stock__desc--bg-secondary text-center"
                >
                    <h3>
                        Суперцены на товары для детей
                    </h3>
                    <p>
                        -50% на детские игрушки
                    </p>
                </div>
            </div>
            <div class="slider-wrapper col-xl-8 px-0">
                <div
                        class="swiper-container"
                        data-breakpoints='{
        "200": { "slidesPerView": "1", "spaceBetween": "0" },
        "371": { "slidesPerView": "2", "spaceBetween": "10" },
        "390": {"slidesPerView": "2", "spaceBetween": "30" },
        "768": {"slidesPerView": "3", "spaceBetween": "30"},
        "1200": {"slidesPerView": "4", "spaceBetween": "0"} }'
                        data-media="(min-width: 200px)"
                >
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="card product-card">
                                <div class="product-card__status-wrap">
                                    <div
                                            class="product-card__status product-card__status--new"
                                    >
                                        <p>new</p>
                                    </div>
                                    <div
                                            class="product-card__status product-card__status--sale"
                                    >
                                        <p>-20%</p>
                                    </div>
                                </div>
                                <button class="product-card__favorites">
                                    <svg width="27" height="24">
                                        <use
                                                xlink:href="/img/spritemap.svg#sprite-heart-outline"
                                        ></use>
                                    </svg>
                                </button>
                                <div class="product-card__img">
                                    <picture>
                                        <source
                                                type="image/webp"
                                                srcset="/img/product-card-img.webp"
                                        />
                                        <img
                                                src="/img/product-card-img.jpg"
                                                alt="product card img"
                                                title="product card img"
                                        />
                                    </picture>
                                </div>
                                <div class="product-card__body">
                                    <div class="product-card__title">
                                        <a href="#" class="product-card__goods-link"></a>
                                        <p>
                                            Название товара, очень длинное название товара
                                        </p>
                                    </div>
                                    <div
                                            class="product-card__reviews d-flex justify-content-between"
                                    >
                                        <div
                                                class="product-card__reviews-stars reviews-stars"
                                        >
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
                                    <div
                                            class="product-card__price d-flex justify-content-between"
                                    >
                                        <div class="product-card__price--not-available">
                                            <p>Нет в наличии</p>
                                        </div>
                                        <div
                                                class="product-card__cart product-card__cart--not-available"
                                        >
                                            <button>
                                                <svg width="24" height="22">
                                                    <use
                                                            xlink:href="/img/spritemap.svg#sprite-shopping-cart"
                                                    ></use>
                                                </svg>
                                                Предзаказ
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="card product-card">
                                <div class="product-card__status-wrap">
                                    <div
                                            class="product-card__status product-card__status--new"
                                    >
                                        <p>new</p>
                                    </div>
                                    <div
                                            class="product-card__status product-card__status--sale"
                                    >
                                        <p>-20%</p>
                                    </div>
                                </div>
                                <button class="product-card__favorites">
                                    <svg width="27" height="24">
                                        <use
                                                xlink:href="/img/spritemap.svg#sprite-heart-outline"
                                        ></use>
                                    </svg>
                                </button>
                                <div class="product-card__img">
                                    <picture>
                                        <source
                                                type="image/webp"
                                                srcset="/img/product-card-img.webp"
                                        />
                                        <img
                                                src="/img/product-card-img.jpg"
                                                alt="product card img"
                                                title="product card img"
                                        />
                                    </picture>
                                </div>
                                <div class="product-card__body">
                                    <div class="product-card__title">
                                        <a href="#" class="product-card__goods-link"></a>
                                        <p>
                                            Название товара, очень длинное название товара
                                        </p>
                                    </div>
                                    <div
                                            class="product-card__reviews d-flex justify-content-between"
                                    >
                                        <div
                                                class="product-card__reviews-stars reviews-stars"
                                        >
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
                                    <div
                                            class="product-card__price d-flex justify-content-between"
                                    >
                                        <div class="product-card__price--actual">
                                            <p>
                                                11 100 грн
                                                <del>120 грн</del>
                                            </p>
                                        </div>
                                        <div
                                                class="product-card__cart open-popup"
                                                data-popup="#cartPopup"
                                        >
                                            <button>
                                                <svg width="24" height="22">
                                                    <use
                                                            xlink:href="/img/spritemap.svg#sprite-shopping-cart"
                                                    ></use>
                                                </svg>
                                                Купить
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="card product-card">
                                <div class="product-card__status-wrap">
                                    <div
                                            class="product-card__status product-card__status--new"
                                    >
                                        <p>new</p>
                                    </div>
                                    <div
                                            class="product-card__status product-card__status--sale"
                                    >
                                        <p>-20%</p>
                                    </div>
                                </div>
                                <button class="product-card__favorites">
                                    <svg width="27" height="24">
                                        <use
                                                xlink:href="/img/spritemap.svg#sprite-heart-outline"
                                        ></use>
                                    </svg>
                                </button>
                                <div class="product-card__img">
                                    <picture>
                                        <source
                                                type="image/webp"
                                                srcset="/img/product-card-img.webp"
                                        />
                                        <img
                                                src="/img/product-card-img.jpg"
                                                alt="product card img"
                                                title="product card img"
                                        />
                                    </picture>
                                </div>
                                <div class="product-card__body">
                                    <div class="product-card__title">
                                        <a href="#" class="product-card__goods-link"></a>
                                        <p>
                                            Название товара, очень длинное название товара
                                        </p>
                                    </div>
                                    <div
                                            class="product-card__reviews d-flex justify-content-between"
                                    >
                                        <div
                                                class="product-card__reviews-stars reviews-stars"
                                        >
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
                                    <div
                                            class="product-card__price d-flex justify-content-between"
                                    >
                                        <div class="product-card__price--actual">
                                            <p>
                                                11 100 грн
                                                <del>120 грн</del>
                                            </p>
                                        </div>
                                        <div
                                                class="product-card__cart open-popup"
                                                data-popup="#cartPopup"
                                        >
                                            <button>
                                                <svg width="24" height="22">
                                                    <use
                                                            xlink:href="/img/spritemap.svg#sprite-shopping-cart"
                                                    ></use>
                                                </svg>
                                                Купить
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="card product-card">
                                <div class="product-card__status-wrap">
                                    <div
                                            class="product-card__status product-card__status--new"
                                    >
                                        <p>new</p>
                                    </div>
                                    <div
                                            class="product-card__status product-card__status--sale"
                                    >
                                        <p>-20%</p>
                                    </div>
                                </div>
                                <button class="product-card__favorites active">
                                    <svg width="27" height="24">
                                        <use
                                                xlink:href="/img/spritemap.svg#sprite-heart-outline"
                                        ></use>
                                    </svg>
                                </button>
                                <div class="product-card__img">
                                    <picture>
                                        <source
                                                type="image/webp"
                                                srcset="/img/product-card-img.webp"
                                        />
                                        <img
                                                src="/img/product-card-img.jpg"
                                                alt="product card img"
                                                title="product card img"
                                        />
                                    </picture>
                                </div>
                                <div class="product-card__body">
                                    <div class="product-card__title">
                                        <a href="#" class="product-card__goods-link"></a>
                                        <p>
                                            Название товара, очень длинное название товара
                                        </p>
                                    </div>
                                    <div
                                            class="product-card__reviews d-flex justify-content-between"
                                    >
                                        <div
                                                class="product-card__reviews-stars reviews-stars"
                                        >
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
                                    <div
                                            class="product-card__price d-flex justify-content-between"
                                    >
                                        <div class="product-card__price--actual">
                                            <p>
                                                11 100 грн
                                                <del>120 грн</del>
                                            </p>
                                        </div>
                                        <div
                                                class="product-card__cart open-popup"
                                                data-popup="#cartPopup"
                                        >
                                            <button>
                                                <svg width="24" height="22">
                                                    <use
                                                            xlink:href="/img/spritemap.svg#sprite-shopping-cart"
                                                    ></use>
                                                </svg>
                                                Купить
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
                <div class="reviews-product-card">
                    <div class="row justify-content-xxl-between">
                        <div
                                class="reviews-product-card__desc-wrapper d-flex col-md-6 col-xxl-4"
                        >
                            <div class="product-card__img reviews-product-card__img">
                                <picture>
                                    <source
                                            type="image/webp"
                                            srcset="/img/product-card-img.webp"
                                    />
                                    <img
                                            src="/img/product-card-img.jpg"
                                            alt="product card img"
                                            title="product card img"
                                    />
                                </picture>
                            </div>
                            <div class="reviews-product-card__body d-flex flex-column">
                                <div
                                        class="product-card__title reviews-product-card__title"
                                >
                                    <a href="#" class="product-card__goods-link"></a>
                                    <p>
                                        Название товара, очень длинное
                                    </p>
                                </div>
                                <div
                                        class="d-flex justify-content-between flex-md-column flex-xl-row reviews-product-card__price-wrapper"
                                >
                                    <div
                                            class="product-card__price reviews-product-card__price d-flex justify-content-between"
                                    >
                                        <div
                                                class="product-card__price--actual  reviews-product-card__price--actual"
                                        >
                                            <p>
                                                <del>120 грн</del>
                                                11 100 грн
                                            </p>
                                        </div>
                                    </div>
                                    <div
                                            class="product-card__reviews reviews-product-card__reviews d-md-flex justify-content-md-between flex-xl-column"
                                    >
                                        <div
                                                class="product-card__reviews-stars reviews-stars"
                                        >
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
                                </div>
                            </div>
                        </div>
                        <div
                                class="reviews-product-card__reviews-wrapper col-md-6 col-xxl-8"
                        >
                            <div class="reviews-product-card__customer-review">
                                <div class="d-flex justify-content-between">
                                    <h3>
                                        Имя Фамилия
                                    </h3>
                                    <div class="product-card__reviews-stars reviews-stars">
                                        <input
                                                type="hidden"
                                                class="rating"
                                                disabled
                                                value="1"
                                        />
                                    </div>
                                </div>
                                <p>
                                    Lorem Ipsum - это текст-"рыба", часто используемый в
                                    печати и вэб-дизайне. Lorem Ipsum является стандартной
                                    "рыбой" для текстов на латинице с начала XVI века.
                                </p>
                                <a
                                        class="reviews-product-card__link text-left text-md-right"
                                        href="#"
                                >подробнее</a
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="goods-section product-categories">
    <div class="container">
        <div class="goods-section__desc product-categories__desc text-center">
            <h2>
                Канцтовары и книги
            </h2>
            <div class="goods-section__bg-elem"></div>
        </div>
        <div class="d-xl-flex justify-content-xl-between">
            <div class="card-stock col-xl-4 px-0">
                <a href="#" class="card-stock__link"></a>
                <div class="card-stock__img">
                    <picture>
                        <source
                                media="(min-width: 1200px)"
                                type="image/webp"
                                srcset="/img/stock-img_xl.webp"
                        />
                        <source
                                media="(min-width: 1200px)"
                                srcset="/img/stock-img_xl.jpg"
                        />
                        <!--	<source
              media="(min-width: 768px)"
              type="image/webp"
              srcset="<%= require('./assets//img/top_banner-md.webp') %>"
            />
            <source
              media="(min-width: 768px)"
              srcset="<%= require('./assets//img/top_banner-md.jpg') %>"
            /> -->
                        <source
                                type="image/webp"
                                srcset="/img/stock-img_sm.webp"
                        />
                        <img
                                src="/img/stock-img_sm.jpg"
                                alt="top banner"
                                title="top banner"
                                class="top-banner__img"
                        />
                    </picture>
                </div>
                <div
                        class="card-stock__desc card-stock__desc--bg-secondary text-center"
                >
                    <h3>
                        Распродажа канцелярских наборов
                    </h3>
                    <p>
                        -50% на всю канцелярию
                    </p>
                </div>
            </div>
            <div class="slider-wrapper col-xl-8 px-0">
                <div
                        class="swiper-container"
                        data-breakpoints='{
        "200": { "slidesPerView": "1", "spaceBetween": "0" },
        "371": { "slidesPerView": "2", "spaceBetween": "10" },
        "390": {"slidesPerView": "2", "spaceBetween": "30" },
        "768": {"slidesPerView": "3", "spaceBetween": "30"},
        "1200": {"slidesPerView": "4", "spaceBetween": "0"} }'
                        data-media="(min-width: 200px)"
                >
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="card product-card">
                                <div class="product-card__status-wrap">
                                    <div
                                            class="product-card__status product-card__status--new"
                                    >
                                        <p>new</p>
                                    </div>
                                    <div
                                            class="product-card__status product-card__status--sale"
                                    >
                                        <p>-20%</p>
                                    </div>
                                </div>
                                <button class="product-card__favorites">
                                    <svg width="27" height="24">
                                        <use
                                                xlink:href="/img/spritemap.svg#sprite-heart-outline"
                                        ></use>
                                    </svg>
                                </button>
                                <div class="product-card__img">
                                    <picture>
                                        <source
                                                type="image/webp"
                                                srcset="/img/product-card-img.webp"
                                        />
                                        <img
                                                src="/img/product-card-img.jpg"
                                                alt="product card img"
                                                title="product card img"
                                        />
                                    </picture>
                                </div>
                                <div class="product-card__body">
                                    <div class="product-card__title">
                                        <a href="#" class="product-card__goods-link"></a>
                                        <p>
                                            Название товара, очень длинное название товара
                                        </p>
                                    </div>
                                    <div
                                            class="product-card__reviews d-flex justify-content-between"
                                    >
                                        <div
                                                class="product-card__reviews-stars reviews-stars"
                                        >
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
                                    <div
                                            class="product-card__price d-flex justify-content-between"
                                    >
                                        <div class="product-card__price--not-available">
                                            <p>
                                                Нет в наличии
                                            </p>
                                        </div>
                                        <div
                                                class="product-card__cart product-card__cart--not-available"
                                        >
                                            <button>
                                                <svg width="24" height="22">
                                                    <use
                                                            xlink:href="/img/spritemap.svg#sprite-shopping-cart"
                                                    ></use>
                                                </svg>
                                                Предзаказ
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="card product-card">
                                <div class="product-card__status-wrap">
                                    <div
                                            class="product-card__status product-card__status--new"
                                    >
                                        <p>new</p>
                                    </div>
                                    <div
                                            class="product-card__status product-card__status--sale"
                                    >
                                        <p>-20%</p>
                                    </div>
                                </div>
                                <button class="product-card__favorites">
                                    <svg width="27" height="24">
                                        <use
                                                xlink:href="/img/spritemap.svg#sprite-heart-outline"
                                        ></use>
                                    </svg>
                                </button>
                                <div class="product-card__img">
                                    <picture>
                                        <source
                                                type="image/webp"
                                                srcset="/img/product-card-img.webp"
                                        />
                                        <img
                                                src="/img/product-card-img.jpg"
                                                alt="product card img"
                                                title="product card img"
                                        />
                                    </picture>
                                </div>
                                <div class="product-card__body">
                                    <div class="product-card__title">
                                        <a href="#" class="product-card__goods-link"></a>
                                        <p>
                                            Название товара, очень длинное название товара
                                        </p>
                                    </div>
                                    <div
                                            class="product-card__reviews d-flex justify-content-between"
                                    >
                                        <div
                                                class="product-card__reviews-stars reviews-stars"
                                        >
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
                                    <div
                                            class="product-card__price d-flex justify-content-between"
                                    >
                                        <div class="product-card__price--actual">
                                            <p>
                                                11 100 грн
                                                <del>120 грн</del>
                                            </p>
                                        </div>
                                        <div
                                                class="product-card__cart open-popup"
                                                data-popup="#cartPopup"
                                        >
                                            <button>
                                                <svg width="24" height="22">
                                                    <use
                                                            xlink:href="/img/spritemap.svg#sprite-shopping-cart"
                                                    ></use>
                                                </svg>
                                                Купить
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="card product-card">
                                <div class="product-card__status-wrap">
                                    <div
                                            class="product-card__status product-card__status--new"
                                    >
                                        <p>new</p>
                                    </div>
                                    <div
                                            class="product-card__status product-card__status--sale"
                                    >
                                        <p>-20%</p>
                                    </div>
                                </div>
                                <button class="product-card__favorites">
                                    <svg width="27" height="24">
                                        <use
                                                xlink:href="/img/spritemap.svg#sprite-heart-outline"
                                        ></use>
                                    </svg>
                                </button>
                                <div class="product-card__img">
                                    <picture>
                                        <source
                                                type="image/webp"
                                                srcset="/img/product-card-img.webp"
                                        />
                                        <img
                                                src="/img/product-card-img.jpg"
                                                alt="product card img"
                                                title="product card img"
                                        />
                                    </picture>
                                </div>
                                <div class="product-card__body">
                                    <div class="product-card__title">
                                        <a href="#" class="product-card__goods-link"></a>
                                        <p>
                                            Название товара, очень длинное название товара
                                        </p>
                                    </div>
                                    <div
                                            class="product-card__reviews d-flex justify-content-between"
                                    >
                                        <div
                                                class="product-card__reviews-stars reviews-stars"
                                        >
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
                                    <div
                                            class="product-card__price d-flex justify-content-between"
                                    >
                                        <div class="product-card__price--actual">
                                            <p>
                                                11 100 грн
                                                <del>120 грн</del>
                                            </p>
                                        </div>
                                        <div
                                                class="product-card__cart open-popup"
                                                data-popup="#cartPopup"
                                        >
                                            <button>
                                                <svg width="24" height="22">
                                                    <use
                                                            xlink:href="/img/spritemap.svg#sprite-shopping-cart"
                                                    ></use>
                                                </svg>
                                                Купить
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="card product-card">
                                <div class="product-card__status-wrap">
                                    <div
                                            class="product-card__status product-card__status--new"
                                    >
                                        <p>new</p>
                                    </div>
                                    <div
                                            class="product-card__status product-card__status--sale"
                                    >
                                        <p>-20%</p>
                                    </div>
                                </div>
                                <button class="product-card__favorites">
                                    <svg width="27" height="24">
                                        <use
                                                xlink:href="/img/spritemap.svg#sprite-heart-outline"
                                        ></use>
                                    </svg>
                                </button>
                                <div class="product-card__img">
                                    <picture>
                                        <source
                                                type="image/webp"
                                                srcset="/img/product-card-img.webp"
                                        />
                                        <img
                                                src="/img/product-card-img.jpg"
                                                alt="product card img"
                                                title="product card img"
                                        />
                                    </picture>
                                </div>
                                <div class="product-card__body">
                                    <div class="product-card__title">
                                        <a href="#" class="product-card__goods-link"></a>
                                        <p>
                                            Название товара, очень длинное название товара
                                        </p>
                                    </div>
                                    <div
                                            class="product-card__reviews d-flex justify-content-between"
                                    >
                                        <div
                                                class="product-card__reviews-stars reviews-stars"
                                        >
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
                                    <div
                                            class="product-card__price d-flex justify-content-between"
                                    >
                                        <div class="product-card__price--actual">
                                            <p>
                                                11 100 грн
                                                <del>120 грн</del>
                                            </p>
                                        </div>
                                        <div
                                                class="product-card__cart open-popup"
                                                data-popup="#cartPopup"
                                        >
                                            <button>
                                                <svg width="24" height="22">
                                                    <use
                                                            xlink:href="/img/spritemap.svg#sprite-shopping-cart"
                                                    ></use>
                                                </svg>
                                                Купить
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
                <div class="reviews-product-card">
                    <div class="row justify-content-xxl-between">
                        <div
                                class="reviews-product-card__desc-wrapper d-flex col-md-6 col-xxl-4"
                        >
                            <div class="product-card__img reviews-product-card__img">
                                <picture>
                                    <source
                                            type="image/webp"
                                            srcset="/img/product-card-img.webp"
                                    />
                                    <img
                                            src="/img/product-card-img.jpg"
                                            alt="product card img"
                                            title="product card img"
                                    />
                                </picture>
                            </div>
                            <div class="reviews-product-card__body d-flex flex-column">
                                <div
                                        class="product-card__title reviews-product-card__title"
                                >
                                    <a href="#" class="product-card__goods-link"></a>
                                    <p>
                                        Название товара, очень длинное
                                    </p>
                                </div>
                                <div
                                        class="d-flex justify-content-between flex-md-column flex-xl-row reviews-product-card__price-wrapper"
                                >
                                    <div
                                            class="product-card__price reviews-product-card__price d-flex justify-content-between"
                                    >
                                        <div
                                                class="product-card__price--actual  reviews-product-card__price--actual"
                                        >
                                            <p>
                                                <del>120 грн</del>
                                                11 100 грн
                                            </p>
                                        </div>
                                    </div>
                                    <div
                                            class="product-card__reviews reviews-product-card__reviews d-md-flex justify-content-md-between flex-xl-column"
                                    >
                                        <div
                                                class="product-card__reviews-stars reviews-stars"
                                        >
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
                                </div>
                            </div>
                        </div>
                        <div
                                class="reviews-product-card__reviews-wrapper col-md-6 col-xxl-8"
                        >
                            <div class="reviews-product-card__customer-review">
                                <div class="d-flex justify-content-between">
                                    <h3>
                                        Имя Фамилия
                                    </h3>
                                    <div class="product-card__reviews-stars reviews-stars">
                                        <input
                                                type="hidden"
                                                class="rating"
                                                disabled
                                                value="1"
                                        />
                                    </div>
                                </div>
                                <p>
                                    Lorem Ipsum - это текст-"рыба", часто используемый в
                                    печати и вэб-дизайне. Lorem Ipsum является стандартной
                                    "рыбой" для текстов на латинице с начала XVI века.
                                </p>
                                <a
                                        class="reviews-product-card__link text-left text-md-right"
                                        href="#"
                                >подробнее</a
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= BlogWidget::widget(); ?>

<section class="section-seo">
    <div class="container">
        <div class="section-seo__desc">
            <h1>
                Заголовок Сео Текста
            </h1>
            <p>
                Lorem Ipsum - это текст-"рыба", часто используемый в печати и
                вэб-дизайне. Lorem Ipsum является стандартной "рыбой" для текстов
                на латинице с начала XVI века. В то время некий безымянный
                печатник создал большую коллекцию размеров и форм шрифтов,
                используя Lorem Ipsum для распечатки образцов. Lorem Ipsum не
                только успешно пережил без заметных изменений пять веков, но и
                перешагнул в электронный дизайн. Его популяризации в новое время
                послужили публикация листов Letraset с образцами Lorem Ipsum в
                60-х годах и, в более недавнее время, программы электронной
                вёрстки типа Aldus PageMaker, в шаблонах которых используется
                Lorem Ipsum.
            </p>
            <p>
                Многие думают, что Lorem Ipsum - взятый с потолка псевдо-латинский
                набор слов, но это не совсем так. Его корни уходят в один фрагмент
                классической латыни 45 года н.э., то есть более двух тысячелетий
                назад. Ричард МакКлинток, профессор латыни из колледжа
                Hampden-Sydney, штат Вирджиния, взял одно из самых странных слов в
                Lorem Ipsum, "consectetur", и занялся его поисками в классической
                латинской литературе. В результате он нашёл неоспоримый
                первоисточник Lorem Ipsum в разделах 1.10.32 и 1.10.33 книги "de
                Finibus Bonorum et Malorum" ("О пределах добра и зла"), написанной
                Цицероном в 45 году н.э. Этот трактат по теории этики был очень
                популярен в эпоху Возрождения. Первая строка Lorem Ipsum, "Lorem
                ipsum dolor sit amet..", происходит от одной из строк в разделе
                1.10.32
            </p>
            <p>
                Классический текст Lorem Ipsum, используемый с XVI века, приведён
                ниже. Также даны разделы 1.10.32 и 1.10.33 "de Finibus Bonorum et
                Malorum" Цицерона и их английский перевод, сделанный H. Rackham,
                1914 год.
            </p>
        </div>
        <div class="section-seo__read-more">
            <span id="readMore">Читать далее</span>
        </div>
    </div>
</section>
