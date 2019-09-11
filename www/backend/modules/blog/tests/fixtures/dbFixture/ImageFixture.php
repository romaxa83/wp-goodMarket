<?php
namespace backend\modules\blog\tests\fixtures\dbFixture;

use yii\test\ActiveFixture;

class ImageFixture extends ActiveFixture 
{
    public $modelClass = 'backend\modules\filemanager\models\Mediafile';
    public $dataFile = 'backend/modules/blog/tests/_data/dbData/image.php';
}
