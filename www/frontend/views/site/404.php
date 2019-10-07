<?php 
use yii\helpers\Url;
?>
<main class="main-content">
    <section class="error-section">
        <div class="error-section__container">
            <div class="error-section__img-wrapper">
                <img class="error-section__img" src="/img/404-error.png" alt="404 error" title="404 error">
            </div>
            <div class="error-section__desc-wrap">
                <h1 class="error-section__title">
                    Cтраница не найдена
                </h1>
                <p class="error-section__text"> 
                    Похоже здесь нет ничего, что можно купить, увы. 
                    Попробуйте начать поиски заново или вернутся 
                    назад.
                </p>
                <a href="<?= Url::toRoute('site/index'); ?>" class="error-section__btn btn btn-primary">На главную</a>
            </div>
        </div>
    </section>
</main>