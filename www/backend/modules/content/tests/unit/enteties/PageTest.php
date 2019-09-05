<?php


namespace backend\modules\content\tests\unit\entities;

use backend\modules\content\models\Page;
use backend\modules\content\models\PageMeta;
use backend\modules\content\models\PageText;
use backend\modules\content\models\SlugManager;
use backend\modules\content\tests\UnitTester;
use backend\modules\content\tests\fixtures\PageFixture;
use backend\modules\content\tests\fixtures\PageMetaFixture;
use backend\modules\content\tests\fixtures\PageTextFixture;
use backend\modules\content\tests\fixtures\SlugManagerFixture;
use Codeception\Test\Unit;


class PageTest extends Unit
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
    public function it_not_create_not_named_page()
    {
        $page = new Page();

        $page->pageMetas = $this->meta;
        $page->slugManager = $this->router;

        $status = $page->save();
        $this->assertFalse($status);
    }

    /** @test */
    public function it_require_assigned_route()
    {
        $page = new Page();
        $page->title = 'Главная';
        $page->status = 1;

        $page->pageMetas = $this->meta;

        $status = $page->save();
        $this->assertFalse($status);

        $page->slugManager = $this->router;
        $this->assertInstanceOf(SlugManager::className(), $page->slugManager);
    }

    /** @test */
    public function it_require_assigned_meta()
    {
        $page = new Page();
        $page->title = 'Главная';
        $page->status = 1;

        $page->slugManager = $this->router;

        $status = $page->save();
        $this->assertFalse($status);

        $page->pageMetas = $this->meta;
        $this->assertInstanceOf(PageMeta::className(), $page->pageMetas);
    }

    /** @test */
    public function it_create_page()
    {
        $page = new Page();
        $page->title = 'Главная';
        $page->status = 1;

        $page->pageMetas = $this->meta;
        $page->slugManager = $this->router;

        $status = $page->save();
        $this->assertTrue($status);
    }

    /** @test */
    public function it_can_be_assigned_with_content()
    {
        $page = new Page();
        $page->title = 'Главная';
        $page->status = 1;

        $page->pageMetas = $this->meta;
        $page->slugManager = $this->router;
        $page->pageText = $this->content;

        $status = $page->save();
        $this->assertTrue($status);
    }

    /** @test */
    public function it_save_page_with_correct_title_and_status()
    {
        $page = new Page();
        $page->title = 'Главная';
        $page->status = 1;

        $page->pageMetas = $this->meta;
        $page->slugManager = $this->router;

        $page->save();
        $page->refresh();

        $this->assertEquals($page->title, 'Главная');
        $this->assertEquals($page->status, 1);
    }
}