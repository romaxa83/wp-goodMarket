<?php

namespace backend\modules\content\tests\unit\entities;

use backend\modules\content\models\Page;
use backend\modules\content\models\PageMeta;
use backend\modules\content\models\PageText;
use backend\modules\content\models\SlugManager;
use backend\modules\content\tests\fixtures\PageMetaFixture;
use backend\modules\content\tests\fixtures\PageTextFixture;
use backend\modules\content\tests\fixtures\SlugManagerFixture;
use backend\modules\content\tests\UnitTester;
use Codeception\Test\Unit;

class SlugManagerTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    /** @var $content PageText */
    private $content;

    /** @var $meta PageMeta */
    private $meta;

    /** @var $router SlugManager */
    private $router;

    public function _before()
    {
        $this->tester->haveFixtures([
            'content' => [
                'class' => PageTextFixture::className(),
            ],
            'meta' => [
                'class' => PageMetaFixture::className(),
            ],
            'router' => [
                'class' => SlugManagerFixture::className(),
            ]
        ]);

        $this->content = $this->tester->grabFixture('content', 'content1');
        $this->meta = $this->tester->grabFixture('meta', 'meta1');
        $this->router = $this->tester->grabFixture('router', 'route1');
    }

    protected function _after()
    {
        SlugManager::deleteAll();
        PageMeta::deleteAll();
        PageText::deleteAll();
        Page::deleteAll();
    }

    /** @test */
    public function it_create_route_while_creating_page()
    {
        $page = new Page();
        $page->status = 1;

        $page->pageMetas = $this->meta;
        $page->slugManager = $this->router;

        $page->save();
        $page->refresh();

        $this->assertInstanceOf(SlugManager::className(), $page->slugManager);
    }

    /** @test */
    public function a_route_can_be_assigned_to_page()
    {
        $page = new Page();
        $page->status = 1;

        $page->pageMetas = $this->meta;
        $page->slugManager = $this->router;

        $page->save();
        $page->refresh();
        $count = SlugManager::find()->where(['id' => $page->slug_id])->count();

        $this->assertEquals(1, $count);
    }
}