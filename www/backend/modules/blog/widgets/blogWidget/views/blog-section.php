<div class="container">
    <div class="goods-section__desc section-news__desc text-center">
      <h2>
        Новости
      </h2>
      <div class="goods-section__bg-elem"></div>
    </div>
    <div class="slider-wrapper">
    <div
        class="swiper-container"
        data-breakpoints='{
        "200": { "slidesPerView": "1", "spaceBetween": "30" },
        "414": {"slidesPerView": "auto", "spaceBetween": "30" },
        "1024": {"slidesPerView": "3", "spaceBetween": "15" },
        "1200": {"slidesPerView": "3", "spaceBetween": "30"} }'
        data-media="(min-width: 200px)"
    >
        <div class="swiper-wrapper">
            <?php foreach ($postBlog as $onePost) : ?>
            <div class="swiper-slide news-card-wrapper">
                <article class="news-card">
                    <div class="news-card__img">
                        <picture>
                        <source
                            type="image/webp"
                            srcset="/img/article_img.webp"
                        />
                        <img
                            src="/img/article_img.jpg"
                            alt="top banner"
                            title="top banner"
                            class="top-banner__img"
                        />
                        </picture>
                    </div>
                    <div class="news-card__date">
                        <p><?= date('Y-m-d', $onePost['published_at']) ?></p>
                    </div>
                    <div class="news-card__desc">
                        <h2><?= $onePost['title'] ?></h2>
                        <p><?= $onePost['description'] ?></p>
                    </div>
                    <div class="news-card__read-more text-right text-md-left">
                        <a href="<?= \yii\helpers\Url::to(['blog/' . $onePost['alias']]) ?>">подробнее</a>
                    </div>
                </article>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="swiper-pagination d-xl-none"></div>
    </div>
        <div class="text-center">
            <a href="<?= \yii\helpers\Url::to(['blog']) ?>" class="btn btn-primary">Все новости</a>
        </div>
    </div>
  </div>