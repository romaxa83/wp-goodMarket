<?php

namespace backend\modules\content\tests\fixtures;

use yii\test\ActiveFixture;

class PageTextFixture extends ActiveFixture
{
    public $modelClass = 'backend\modules\content\models\PageText';
    public $dataFile = 'backend/modules/content/tests/fixtures/data/pageText/page_text.php';
}