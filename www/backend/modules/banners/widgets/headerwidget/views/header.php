<?php
use yii\helpers\Url;
?>

<div class="top-banner">
    <?php $lang = array_key_first($banner['bannerLang'])?>
    <?php if (isset($banner['bannerLang'][$lang]['media']['header_resolutions'])): ?>
        <?php $banner = $banner['bannerLang'][$lang] ?>
        <a href="<?php echo Url::to($banner['alias'], TRUE); ?>" class="top-banner__link"></a>
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
                    title="<?php echo $banner['title'] ?>"
                    class="top-banner__img"
            />
        </picture>
    <?php endif; ?>
</div>
