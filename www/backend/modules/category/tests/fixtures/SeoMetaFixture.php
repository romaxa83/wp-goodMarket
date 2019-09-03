<?php
namespace backend\modules\category\tests\fixtures;

use yii\test\ActiveFixture;

class SeoMetaFixture extends ActiveFixture
{
    public $modelClass = 'backend\modules\seo\models\SeoMeta';
    public $dataFile = 'backend/modules/category/tests/_data/seo-meta.php';
}
