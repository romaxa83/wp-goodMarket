<?php
namespace backend\modules\content\tests\fixtures;

use yii\test\ActiveFixture;

class LangFixture extends ActiveFixture 
{
    public $modelClass = 'common\models\Lang';
    public $dataFile = 'backend/modules/content/tests/fixtures/data/lang.php';
}