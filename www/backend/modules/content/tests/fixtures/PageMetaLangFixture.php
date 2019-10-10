<?php

namespace backend\modules\content\tests\fixtures;

use yii\test\ActiveFixture;

class PageMetaLangFixture extends ActiveFixture
{
    public $modelClass = 'backend\modules\content\models\PageMetaLang';
    public $dataFile = 'backend/modules/content/tests/fixtures/data/pageMeta/page_meta_lang.php';
}