<?php

namespace backend\modules\content\tests\fixtures;

use yii\test\ActiveFixture;

class SeoDataFixture extends ActiveFixture
{
    public $modelClass = 'backend\modules\content\models\SeoData';
    public $dataFile = 'backend/modules/content/tests/fixtures/data/seo_data.php';
}