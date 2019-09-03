<?php

namespace backend\modules\content\tests\fixtures;

use yii\test\ActiveFixture;

class PageMetaFixture extends ActiveFixture
{
    public $modelClass = 'backend\modules\content\models\PageMeta';
    public $dataFile = 'backend/modules/content/tests/fixtures/data/page/page_meta.php';
}