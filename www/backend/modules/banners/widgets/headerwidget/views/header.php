<?php
use yii\helpers\Url;
?>

<div class="top-banner">
    <a href="#" class="top-banner__link"></a>
    <picture>
        <source
                media="(min-width: 1500px)"
                srcset="<?php echo Url::to('/admin/' . $header_resolutions['xxl'], TRUE); ?>"
        />
        <source
                media="(min-width: 1200px)"
                srcset="<?php echo Url::to('/admin/' . $header_resolutions['xl'], TRUE); ?>"
        />
        <source
                media="(min-width: 768px)"
                srcset="<?php echo Url::to('/admin/' . $header_resolutions['md'], TRUE); ?>"
        />
        <img
                src="<?php echo Url::to('/admin/' . $header_resolutions['sm'], TRUE); ?>"
                alt="<?php echo $banner_alt; ?>"
                title="top banner"
                class="top-banner__img"
        />
    </picture>
</div>
