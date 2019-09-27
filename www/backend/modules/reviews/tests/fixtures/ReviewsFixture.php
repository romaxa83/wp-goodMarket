<?php
namespace backend\modules\reviews\tests\fixtures;

use yii\test\ActiveFixture;

class ReviewsFixture extends ActiveFixture
{
    public $modelClass = 'backend\modules\reviews\models\Reviews';
    public $dataFile = 'backend/modules/reviews/tests/_data/dbData/reviews.php';
}
