<?php

namespace backend\modules\banners\tests\fixtures;

use yii\test\ActiveFixture;

class BannerFixture extends ActiveFixture {

    public $modelClass = 'backend\modules\banners\models\Banner';
    public $dataFile = 'backend/modules/banners/tests/_data/banner.php';
    public $depends = [
        'backend\modules\banners\tests\fixtures\BannerLangFixture'
    ];

}
