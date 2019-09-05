<?php

use backend\modules\blog\entities\Category;
use backend\modules\blog\entities\CategoryLang;
use backend\modules\blog\tests\fixtures\LangFixture;
use backend\modules\blog\tests\fixtures\CategoryFixture;
use backend\modules\blog\tests\fixtures\CategoryLangFixture;
use Codeception\Test\Unit;

class CategoryTest extends Unit 
{

    public $tester;

    public function _fixtures() 
    {
        
    }

    public function _before() 
    {
        $this->tester->haveFixtures([
            'lang' => [
                'class' => LangFixture::className(),
            ],
            'category' => [
                'class' => CategoryFixture::className(),
            ],
            'category-lang' => [
                'class' => CategoryLangFixture::className(),
            ]
        ]);
    }

    public function _after() 
    {
        Category::deleteAll();
        CategoryLang::deleteAll();
    }

    public function testSuccessCreate() 
    {
        
    }
    
    public function testErrorCreate() 
    {
        
    }

    public function testEmptyCreate() 
    {
        
    }

    public function testSuccessUpdate() 
    {
        
    }
    
    public function testErrorUpdate() 
    {
        
    }

    public function testEmptyUpdate() 
    {
        
    }
}
