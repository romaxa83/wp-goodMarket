<?php

namespace backend\modules\content\tests\fixtures;

use yii\test\ActiveFixture;

class PageLangFixture extends ActiveFixture
{
    public $modelClass = 'backend\modules\content\models\PageLang';
    public $dataFile = 'backend/modules/content/tests/fixtures/data/page/page_lang.php';
    public $depends = [
        'backend\modules\content\tests\fixtures\PageFixture',
        'backend\modules\content\tests\fixtures\PageMetaLangFixture',
        'backend\modules\content\tests\fixtures\PageTextLangFixture'
    ];
}