<?php

namespace backend\modules\content\tests\unit\entities;

use Yii;

use common\models\Lang;
use backend\modules\content\models\Page;
use backend\modules\content\models\PageLang;

use backend\modules\content\models\PageMeta;
use backend\modules\content\models\PageMetaLang;

use backend\modules\content\models\PageText;
use backend\modules\content\models\PageTextLang;

use backend\modules\content\tests\fixtures\PageFixture;
use backend\modules\content\tests\fixtures\PageLangFixture;

use backend\modules\content\tests\fixtures\PageMetaFixture;
use backend\modules\content\tests\fixtures\PageMetaLangFixture;

use backend\modules\content\tests\fixtures\LangFixture;

use backend\modules\content\models\SlugManager;
use backend\modules\content\tests\fixtures\SlugManagerFixture;

use backend\widgets\langwidget\LangWidget;

use backend\modules\content\tests\UnitTester;
use Codeception\Test\Unit;

class PageTest extends Unit
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
            'page' => [
                'class' => PageFixture::className()
            ],
            'pageLang' => [
                'class' => PageLangFixture::className()
            ],
            'pageMeta' => [
                'class' => PageMetaFixture::className()
            ],
            'pageMetaLang' => [
                'class' => PageMetaLangFixture::className()
            ],
            'router' => [
                'class' => SlugManagerFixture::className()
            ],
            'lang' => [
                'class' => LangFixture::className()
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
    public function testSuccessCreate()
    {
        $data = $this->tester->grabFixture('dataPageMeta')->data['create'];
        
        $page = new Page();
        $pageLang = new PageLang();

        $pageMeta = new PageMeta();
        $pageMetaLang = new PageMetaLang();

        $slug = new SlugManager();
        
        $this->assertTrue($page->load($data));
        $this->assertTrue(LangWidget::validate($pageLang,$data));
        $this->assertTrue(LangWidget::validate($pageMetaLang,$data));

        $this->assertTrue($page->savePage($page, $pageMeta, $slug,$data));

        $pageLang->saveLang($data['PageLang'],$page->id);
        $pageMetaLang->saveLang($data['PageMetaLang'],$page->id);

        $pageDb = Page::find()->where(['id' => $page->id])->asArray()->with('manyLang')->one();
        $pageMetaDb = PageMeta::find()->where(['page_id' => $page->id])->asArray()->with('manyLang')->one();

        $this->assertEquals($pageDb['manyLang'][0]['title'], $data['PageLang']['ru']['title']);
        $this->assertEquals($pageDb['manyLang'][1]['title'], $data['PageLang']['en']['title']);

        $this->assertEquals($pageMetaDb['manyLang'][0]['title'], $data['PageMetaLang']['ru']['title']);
        $this->assertEquals($pageMetaDb['manyLang'][1]['title'], $data['PageMetaLang']['en']['title']);
    }

    /** @test */
    public function testEmptyPageCreate()
    {
        $data = $this->tester->grabFixture('dataEmptyPage')->data;
        
        $page = new Page();
        $pageLang = new PageLang();

        $pageMeta = new PageMeta();
        $pageMetaLang = new PageMetaLang();

        $slug = new SlugManager();

        $this->assertTrue($page->load($data));
        
        $this->assertFalse(LangWidget::validate($pageLang,$data));
        $this->assertTrue(LangWidget::validate($pageMetaLang,$data));
    }

    /** @test */
    public function testEmptyMetaCreate()
    {
        $data = $this->tester->grabFixture('dataEmptyPageMeta')->data;

        $page = new Page();
        $pageLang = new PageLang();

        $pageMeta = new PageMeta();
        $pageMetaLang = new PageMetaLang();

        $slug = new SlugManager();

        $this->assertTrue($page->load($data));
        
        $this->assertTrue(LangWidget::validate($pageLang,$data));
        $this->assertFalse(LangWidget::validate($pageMetaLang,$data));
    }

    /** @test */
    public function testSuccessUpdate()
    {
        $data = $this->tester->grabFixture('dataPageMeta')->data['update'];
        
        $page = Page::find()->one();
        $pageLang = PageLang::find()->where(['page_id' => $page->id])->one();

        $pageMeta = PageMeta::find()->where(['page_id' => $page->id])->one();
        $pageMetaLang = PageMetaLang::find()->where(['meta_id' => $pageMeta->id])->one();

        $slug = new SlugManager();
        
        $this->assertTrue($page->load($data));
        $this->assertTrue(LangWidget::validate($pageLang,$data));
        $this->assertTrue(LangWidget::validate($pageMetaLang,$data));

        $this->assertTrue($page->savePage($page, $pageMeta, $slug,$data));

        $pageLang->updateLang($data['PageLang'],$page->id);
        $pageMetaLang->updateLang($data['PageMetaLang'],$pageMeta->id);

        $pageDb = Page::find()->where(['id' => $page->id])->asArray()->with('manyLang')->one();
        $pageMetaDb = PageMeta::find()->where(['page_id' => $page->id])->asArray()->with('manyLang')->one();

        $this->assertEquals($pageDb['manyLang'][0]['title'], $data['PageLang']['ru']['title']);
        $this->assertEquals($pageDb['manyLang'][1]['title'], $data['PageLang']['en']['title']);

        $this->assertEquals($pageMetaDb['manyLang'][0]['title'], $data['PageMetaLang']['ru']['title']);
        $this->assertEquals($pageMetaDb['manyLang'][1]['title'], $data['PageMetaLang']['en']['title']);
    }

    public function testSuccessDelete()
    {
        Page::deleteAll(['id' => 1]);
        
        $this->assertEquals(Page::find()->count(),0);
        $this->assertEquals(PageLang::find()->count(),0);

        $this->assertEquals(PageMeta::find()->count(),0);
        $this->assertEquals(PageMetaLang::find()->count(),0);

        $this->assertEquals(PageText::find()->count(),0);
        $this->assertEquals(PageTextLang::find()->count(),0);
    }
}