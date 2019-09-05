<?php

namespace backend\modules\content\tests\unit\entities;

use backend\modules\content\models\Channel;
use backend\modules\content\models\ChannelContent;
use backend\modules\content\models\ChannelRecord;
use backend\modules\content\models\ChannelRecordsCommonField;
use backend\modules\content\models\SeoData;
use backend\modules\content\models\SlugManager;
use backend\modules\content\tests\fixtures\ChannelContentFixture;
use backend\modules\content\tests\fixtures\ChannelRecordFixture;
use backend\modules\content\tests\fixtures\ChannelRecordsCommonFieldFixture;
use backend\modules\content\tests\fixtures\SeoDataFixture;
use backend\modules\content\tests\fixtures\SlugManagerFixture;
use backend\modules\content\tests\UnitTester;
use Codeception\Test\Unit;

class ChannelTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    /** @var $router SlugManager */
    private $router;

    /** @var $seo SeoData */
    private $seo;

    /** @var $commonFields ChannelRecordsCommonField */
    private $commonFields;

    /** @var $channelContent ChannelContent */
    private $channelContent;

    /** @var $channelRecords ChannelRecord */
    private $channelRecords;

    protected function _before()
    {
        $this->tester->haveFixtures([
            'seo' => SeoDataFixture::className(),
            'commonFields' => ChannelRecordsCommonFieldFixture::className(),
            'channelContent' => ChannelContentFixture::className(),
            'router' => SlugManagerFixture::className(),
            'records' => ChannelRecordFixture::className()
        ]);

        $this->router = $this->tester->grabFixture('router', 'route2');
        $this->seo = $this->tester->grabFixture('seo', 'seo2');
        $this->commonFields = $this->tester->grabFixture('commonFields', 'field1');
        $this->channelContent = $this->tester->grabFixture('channelContent', 'content1');
        $this->channelRecords = $this->tester->grabFixture('records', 'record1');
    }

    protected function _after()
    {
        SlugManager::deleteAll();
        SeoData::deleteAll();
        ChannelRecordsCommonField::deleteAll();
        ChannelContent::deleteAll();
    }

    /** @test */
    public function it_create_channel()
    {
        $channel = new Channel();

        $channel->title = 'Blog';
        $channel->record_structure = 'a:1:{i:0;a:3:{s:4:"name";s:7:"content";s:5:"label";s:14:"Контент";s:4:"type";s:6:"editor";}}';
        $channel->status = 1;
        $channel->created_at = '2019-09-02';
        $channel->updated_at = '2019-09-02';

        $channel->seoData = $this->seo;
        $channel->slugManager = $this->router;
        $channel->channelContent = $this->channelContent;
        $channel->channelRecordsCommonField = $this->commonFields;

        $status = $channel->save();
        $this->assertTrue($status);
    }

    /** @test */
    public function it_require_assigned_meta()
    {
        $channel = new Channel();

        $channel->title = 'Blog';
        $channel->record_structure = 'a:1:{i:0;a:3:{s:4:"name";s:7:"content";s:5:"label";s:14:"Контент";s:4:"type";s:6:"editor";}}';
        $channel->status = 1;
        $channel->created_at = '2019-09-02';
        $channel->updated_at = '2019-09-02';

        $channel->slugManager = $this->router;
        $channel->channelContent = $this->channelContent;
        $channel->channelRecordsCommonField = $this->commonFields;

        $status = $channel->save();
        $this->assertFalse($status);

        $channel->seoData = $this->seo;
        $this->assertInstanceOf(SeoData::className(), $channel->seoData);
    }

    /** @test */
    public function it_require_assigned_route()
    {
        $channel = new Channel();

        $channel->title = 'Blog';
        $channel->record_structure = 'a:1:{i:0;a:3:{s:4:"name";s:7:"content";s:5:"label";s:14:"Контент";s:4:"type";s:6:"editor";}}';
        $channel->status = 1;
        $channel->created_at = '2019-09-02';
        $channel->updated_at = '2019-09-02';

        $channel->seoData = $this->seo;
        $channel->channelContent = $this->channelContent;
        $channel->channelRecordsCommonField = $this->commonFields;

        $status = $channel->save();
        $this->assertFalse($status);

        $channel->slugManager = $this->router;
        $this->assertInstanceOf(SlugManager::className(), $channel->slugManager);
    }

    /** @test */
    public function it_has_records()
    {
        $channel = new Channel();

        $channel->title = 'Blog';
        $channel->record_structure = 'a:1:{i:0;a:3:{s:4:"name";s:7:"content";s:5:"label";s:14:"Контент";s:4:"type";s:6:"editor";}}';
        $channel->status = 1;
        $channel->created_at = '2019-09-02';
        $channel->updated_at = '2019-09-02';

        $channel->seoData = $this->seo;
        $channel->slugManager = $this->router;
        $channel->channelContent = $this->channelContent;
        $channel->channelRecordsCommonField = $this->commonFields;

        $channel->save();
        $channel->refresh();

        $this->channelRecords->channel_id = $channel->id;
        $this->channelRecords->save();

        $this->assertCount(1, $channel->channelRecords);
    }
}