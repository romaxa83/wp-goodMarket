<?php

namespace backend\modules\content\tests\fixtures;

use yii\test\ActiveFixture;

class PageTextLangFixture extends ActiveFixture
{
    public $modelClass = 'backend\modules\content\models\PageTextLang';
    public $dataFile = 'backend/modules/content/tests/fixtures/data/pageText/page_text_lang.php';
}