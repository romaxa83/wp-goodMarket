<?php

namespace backend\modules\content\tests\unit\entities;

use backend\modules\content\models\Page;
use backend\modules\content\models\PageMeta;
use backend\modules\content\models\PageText;
use backend\modules\content\models\SlugManager;
use backend\modules\content\tests\fixtures\PageMetaFixture;
use backend\modules\content\tests\fixtures\SlugManagerFixture;
use backend\modules\content\tests\UnitTester;
use Codeception\Test\Unit;

class PageTextTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    /** @var $meta PageMeta */
    private $meta;

    /** @var $router SlugManager */
    private $router;

    public function _before()
    {
        $this->tester->haveFixtures([
            'meta' => [
                'class' => PageMetaFixture::className(),
            ],
            'router' => [
                'class' => SlugManagerFixture::className(),
            ]
        ]);

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
    public function it_create_content_for_page()
    {
        $page = new Page();
        $page->title = 'Main';
        $page->status = 1;

        $page->slugManager = $this->router;
        $page->pageMetas = $this->meta;

        $pageText = new PageText();
        $pageText->name = 'seo';
        $pageText->label = 'SEO Text';
        $pageText->type = 'editor';
        $pageText->text = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec rutrum congue leo eget malesuada.</p>';

        $page->pageText = $pageText;

        $page->save();
        $page->refresh();

        $this->assertInstanceOf(PageText::className(), $page->pageText[0]);
    }
}