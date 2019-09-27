<?php

namespace backend\modules\content\tests\unit\entities;

use backend\modules\content\models\Page;
use backend\modules\content\models\PageMeta;
use backend\modules\content\models\SlugManager;
use backend\modules\content\tests\fixtures\SlugManagerFixture;
use backend\modules\content\tests\UnitTester;
use Codeception\Test\Unit;

class PageMetaTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    /** @var $router SlugManager */
    private $router;

    public function _before()
    {
        $this->tester->haveFixtures([
            'router' => [
                'class' => SlugManagerFixture::className(),
            ]
        ]);

        $this->router = $this->tester->grabFixture('router', 'route1');
    }

    protected function _after()
    {
        SlugManager::deleteAll();
        PageMeta::deleteAll();
        Page::deleteAll();
    }

    /** @test */
    public function meta_title_can_not_be_empty()
    {
        $page = new Page();
        $page->title = 'Main';
        $page->status = 1;

        $page->slugManager = $this->router;

        $pageMeta = new PageMeta();
        $pageMeta->title = '';
        $pageMeta->description = 'Description of main page';
        $pageMeta->keywords = '';
        $page->pageMetas = $pageMeta;

        $status = $page->save();
        $this->assertFalse($status);
    }

    /** @test */
    public function meta_description_can_not_be_empty()
    {
        $page = new Page();
        $page->title = 'Main';
        $page->status = 1;

        $page->slugManager = $this->router;

        $pageMeta = new PageMeta();
        $pageMeta->title = 'Main';
        $pageMeta->description = '';
        $pageMeta->keywords = '';
        $page->pageMetas = $pageMeta;

        $status = $page->save();
        $this->assertFalse($status);
    }

    /** @test */
    public function it_create_page_meta()
    {
        $page = new Page();
        $page->title = 'Main';
        $page->status = 1;

        $page->slugManager = $this->router;

        $pageMeta = new PageMeta();
        $pageMeta->title = 'Main';
        $pageMeta->description = 'Description of main page';
        $pageMeta->keywords = '';
        $page->pageMetas = $pageMeta;

        $page->save();
        $page->refresh();

        $this->assertInstanceOf(PageMeta::className(), $page->pageMetas);
    }

    /** @test */
    public function page_meta_saved_with_correct_data()
    {
        $page = new Page();
        $page->title = 'Main';
        $page->status = 1;

        $page->slugManager = $this->router;

        $pageMeta = new PageMeta();
        $pageMeta->title = 'Main';
        $pageMeta->description = 'Description of main page';
        $pageMeta->keywords = 'main page, home';
        $page->pageMetas = $pageMeta;

        $page->save();
        $page->refresh();

        $this->assertEquals($page->pageMetas->title, 'Main');
        $this->assertEquals($page->pageMetas->description, 'Description of main page');
        $this->assertEquals($page->pageMetas->keywords, 'main page, home');
    }
}