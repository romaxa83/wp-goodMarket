<?php
namespace backend\modules\category\tests\fixtures;

use yii\test\ActiveFixture;

class CategoryFixture extends ActiveFixture
{
    public $modelClass = 'backend\modules\category\models\Category';
    public $dataFile = 'backend/modules/category/tests/_data/category.php';
    public $depends = [
        'backend\modules\category\tests\fixtures\CategoryLangFixture',
        'backend\modules\category\tests\fixtures\SeoMetaFixture'
    ];
}
