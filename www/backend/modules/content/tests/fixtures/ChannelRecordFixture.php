<?php

namespace backend\modules\content\tests\fixtures;

use yii\test\ActiveFixture;

class ChannelRecordFixture extends ActiveFixture
{
    public $modelClass = 'backend\modules\content\models\Channel';
    public $depends = [
        'backend\modules\content\tests\fixtures\SlugManagerFixture',
        'backend\modules\content\tests\fixtures\SeoDataFixture',
        'backend\modules\content\tests\fixtures\ChannelRecordContentFixture',
        'backend\modules\content\tests\fixtures\ChannelRecordsCommonFieldFixture',
        'backend\modules\content\tests\fixtures\ChannelCategoryFixture'
    ];
}