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

    /** @var $page Page */
    private $page;

    /** @var $content PageText */
    private $content;

    /** @var $meta PageMeta */
    private $meta;

    /** @var $router SlugManager */
    private $router;

    public function _fixtures()
    {
        return [
            'page' => PageFixture::class,
            'content' => PageTextFixture::class,
            'meta' => PageMetaFixture::class,
            'router' => SlugManagerFixture::class
        ];
}

    public function _before()
    {
        $this->page = $this->tester->grabFixture('page', 'page1');
        $this->content = $this->tester->grabFixture('content', 'content1');
        $this->meta = $this->tester->grabFixture('meta', 'meta1');
        $this->router = $this->tester->grabFixture('router', 'route1');
    }

    /** @test */
    public function it_create_page()
    {

    }
}