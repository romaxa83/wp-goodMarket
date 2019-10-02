<?php

namespace backend\modules\content\tests\fixtures;

use yii\test\ActiveFixture;

class PageFixture extends ActiveFixture
{
    public $modelClass = 'backend\modules\content\models\Page';
    public $dataFile = 'backend/modules/content/tests/fixtures/data/page/page.php';
    public $depends = [
        'backend\modules\content\tests\fixtures\SlugManagerFixture',
        'backend\modules\content\tests\fixtures\PageMetaFixture',
        'backend\modules\content\tests\fixtures\PageTextFixture'
    ];
}