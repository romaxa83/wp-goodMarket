<?php 
use yii\helpers\Url;

$this->title = 'Good Market - внутренняя ошибка сервера'
?>
<main class="main-content">
    <section class="error-section" style="height:500px">
        <div class="error-section__container">
            <div class="error-section__desc-wrap">
                <h1 class="error-section__title">
                    Упс .....
                </h1>
                <p class="error-section__text"> 
                    Похоже что то пошле не так, увы. 
                    Попробуйте перезагрузить страницу или вернуться 
                    назад.
                </p>
                <a href="<?= Url::toRoute('site/index'); ?>" class="error-section__btn btn btn-primary">На главную</a>
            </div>
        </div>
    </section>
</main>