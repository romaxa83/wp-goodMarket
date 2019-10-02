<?php

namespace backend\modules\content\tests\unit\entities;

use backend\modules\content\models\Channel;
use backend\modules\content\models\ChannelCategory;
use backend\modules\content\models\ChannelContent;
use backend\modules\content\models\ChannelRecord;
use backend\modules\content\models\ChannelRecordContent;
use backend\modules\content\models\ChannelRecordsCommonField;
use backend\modules\content\models\SeoData;
use backend\modules\content\models\SlugManager;
use backend\modules\content\tests\fixtures\ChannelCategoryFixture;
use backend\modules\content\tests\fixtures\ChannelFixture;
use backend\modules\content\tests\fixtures\ChannelRecordContentFixture;
use backend\modules\content\tests\fixtures\ChannelRecordsCommonFieldFixture;
use backend\modules\content\tests\fixtures\SeoDataFixture;
use backend\modules\content\tests\fixtures\SlugManagerFixture;
use backend\modules\content\tests\UnitTester;
use Codeception\Test\Unit;

class ChannelRecordTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    /** @var $router SlugManager */
    private $router;

    /** @var $seo SeoData */
    private $seo;

    /** @var $content ChannelRecordContent */
    private $content;

    /** @var $commonFields ChannelRecordsCommonField */
    private $commonFields;

    /** @var $category ChannelCategory */
    private $category;

    /** @var $channel Channel */
    private $channel;

    protected function _before()
    {
        $this->tester->haveFixtures([
            'router' => SlugManagerFixture::className(),
            'seo' => SeoDataFixture::className(),
            'content' => ChannelRecordContentFixture::className(),
            'commonFields' => ChannelRecordsCommonFieldFixture::className(),
            'category' => ChannelCategoryFixture::className(),
            'channel' => ChannelFixture::className()
        ]);

        $this->router = $this->tester->grabFixture('router', 'route3');
        $this->seo = $this->tester->grabFixture('seo', 'seo1');
        $this->content = $this->tester->grabFixture('content', 'content1');
        $this->commonFields = $this->tester->grabFixture('commonFields', 'field1');
        $this->category = $this->tester->grabFixture('content', 'category1');
        $this->channel = $this->tester->grabFixture('channel', 'channel1');
    }

    protected function _after()
    {
        SlugManager::deleteAll();
        SeoData::deleteAll();
        ChannelRecordsCommonField::deleteAll();
        ChannelRecordContent::deleteAll();
        ChannelCategory::deleteAll();
        ChannelContent::deleteAll();
        Channel::deleteAll();
    }

    /** @test */
    public function it_require_assigned_route()
    {
        $channelRecord = new ChannelRecord();
        $channelRecord->channel_id = $this->channel->id;
        $channelRecord->title = 'Post 1';
        $channelRecord->status = 1;
        $channelRecord->created_at = '2019-09-02';
        $channelRecord->updated_at = '2019-09-02';

        $channelRecord->seoData = $this->seo;

        $this->assertFalse($channelRecord->save());

        $channelRecord->slugManager = $this->router;
        $this->assertInstanceOf(SlugManager::className(), $channelRecord->slugManager);
    }

    /** @test */
    public function it_require_assigned_meta()
    {
        $channelRecord = new ChannelRecord();
        $channelRecord->channel_id = $this->channel->id;
        $channelRecord->title = 'Post 1';
        $channelRecord->status = 1;
        $channelRecord->created_at = '2019-09-02';
        $channelRecord->updated_at = '2019-09-02';

        $channelRecord->slugManager = $this->router;

        $this->assertFalse($channelRecord->save());

        $channelRecord->seoData = $this->seo;
        $this->assertInstanceOf(SeoData::className(), $channelRecord->seoData);
    }

    /** @test */
    public function it_create_channel_record()
    {
        $channelRecord = new ChannelRecord();
        $channelRecord->channel_id = $this->channel->id;
        $channelRecord->title = 'Post 1';
        $channelRecord->status = 1;
        $channelRecord->created_at = '2019-09-02';
        $channelRecord->updated_at = '2019-09-02';

        $channelRecord->seoData = $this->seo;
        $channelRecord->slugManager = $this->router;

        $status = $channelRecord->save();
        $this->assertTrue($status);
    }

    /** @test */
    public function record_belong_to_channel()
    {
        $channelRecord = new ChannelRecord();
        $channelRecord->channel_id = $this->channel->id;
        $channelRecord->title = 'Post 1';
        $channelRecord->status = 1;
        $channelRecord->created_at = '2019-09-02';
        $channelRecord->updated_at = '2019-09-02';

        $channelRecord->seoData = $this->seo;
        $channelRecord->slugManager = $this->router;

        $channelRecord->save();
        $channelRecord->refresh();

        $this->assertEquals($channelRecord->channel_id, $this->channel->id);
    }
}