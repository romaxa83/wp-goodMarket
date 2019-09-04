<?php

namespace backend\modules\banners\tests\fixtures;

use yii\test\ActiveFixture;

class BannerLangFixture extends ActiveFixture {

    public $modelClass = 'backend\modules\banners\models\BannerLang';
    public $dataFile = 'backend/modules/banners/tests/_data/banner-lang.php';
    public $depends = [
        'backend\modules\banners\tests\fixtures\LangFixture',
    ];

}
