<?php

namespace backend\modules\content\tests\fixtures;

use yii\test\ActiveFixture;

class ChannelFixture extends ActiveFixture
{
    public $modelClass = 'backend\modules\content\models\Channel';
    public $depends = [
        'backend\modules\content\tests\fixtures\SlugManagerFixture',
        'backend\modules\content\tests\fixtures\SeoDataFixture',
        'backend\modules\content\tests\fixtures\ChannelContentFixture',
        'backend\modules\content\tests\fixtures\ChannelRecordsCommonFieldFixture'
    ];
}