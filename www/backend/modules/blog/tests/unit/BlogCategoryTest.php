<?php

use common\models\Lang;
use backend\widgets\langwidget\LangWidget;
use backend\modules\blog\repository\CategoryRepository;
use backend\modules\blog\repository\PostRepository;
use backend\modules\blog\repository\TagRepository;
use backend\modules\blog\services\CategoryService;
//models
use backend\modules\blog\entities\Category;
use backend\modules\blog\entities\CategoryLang;
use backend\modules\blog\forms\CategoryForm;
//db Fixture
use backend\modules\blog\tests\fixtures\dbFixture\LangFixture;
use backend\modules\blog\tests\fixtures\dbFixture\CategoryFixture;
use backend\modules\blog\tests\fixtures\dbFixture\CategoryLangFixture;
//array Fixture
use backend\modules\blog\tests\fixtures\arrayFixture\DataCategoryFixture;
use backend\modules\blog\tests\fixtures\arrayFixture\DataEmptyCategoryFixture;
//array Fixture
use backend\modules\blog\tests\fixtures\arrayFixture\DataTagFixture;
use backend\modules\blog\tests\fixtures\arrayFixture\DataEmptyTagFixture;

use Codeception\Test\Unit;

class BlogCategoryTest extends Unit 
{
    public $tester;
    private $service;

    public function _fixtures() 
    {
        return [
            'dataCategory' => DataCategoryFixture::className(),
            'dataEmptyCategory' => DataEmptyCategoryFixture::className(),
            'dataTag' => DataTagFixture::className(),
            'dataEmptyTag' => DataEmptyTagFixture::className()
        ];
    }

    public function _before() 
    {
        $this->service = new CategoryService(new CategoryRepository(),new PostRepository(new CategoryRepository(),new TagRepository()));

        $this->tester->haveFixtures([
            'lang' => [
                'class' => LangFixture::className(),
            ],
            'category' => [
                'class' => CategoryFixture::className(),
            ],
            'categoryLang' => [
                'class' => CategoryLangFixture::className(),
            ]
        ]);
    }

    public function _after() 
    {
        Category::deleteAll();
        CategoryLang::deleteAll();
        Lang::deleteAll();
    }

    public function testSuccessCreate() 
    {
        $data = $this->tester->grabFixture('dataCategory')->data['create'];
        
        $form = new CategoryForm();
        $langModel = new CategoryLang();

        $this->assertTrue($form->load($data));
        $this->assertTrue($form->validate());
        $this->assertTrue(LangWidget::validate($langModel,$data));

        $baseModel = $this->service->create($form);
        $this->assertInstanceOf(Category::class,$baseModel);

        $langModel->saveLang($data['CategoryLang'],$baseModel->id);
        
        $langArray = Lang::find()->asArray()->indexBy('alias')->all();
        $baseModelDb = Category::find()->where(['id' => $baseModel->id])->asArray()->one();
        $langRuModelDb = CategoryLang::find()->where(['category_id' => $baseModel->id])->andWhere(['lang_id' => $langArray['ru']['id']])->asArray()->one();
        $langEnModelDb = CategoryLang::find()->where(['category_id' => $baseModel->id])->andWhere(['lang_id' => $langArray['en']['id']])->asArray()->one();

        $this->assertEquals($baseModelDb['alias'], $form->getAttributes()['alias']);
        $this->assertEquals($baseModelDb['lft'], $baseModel['lft']);
        $this->assertEquals($baseModelDb['rgt'], $baseModel['rgt']);
        $this->assertEquals($baseModelDb['depth'], $baseModel['depth']);

        $this->assertEquals($langRuModelDb['title'], $data['CategoryLang']['ru']['title']);
        $this->assertEquals($langEnModelDb['title'], $data['CategoryLang']['en']['title']);
    }

    public function testEmptyCreate() 
    {
        $data = $this->tester->grabFixture('dataEmptyCategory')->data;

        $form = new CategoryForm();
        $langModel = new CategoryLang();

        $this->assertTrue($form->load($data)); //assertTrue because structure is observed
        $this->assertFalse($form->validate()); 
        $this->assertFalse(LangWidget::validate($langModel,$data));

        $this->expectException(DomainException::class);
        $baseModel = $this->service->create($form);
    }

    public function testSuccessUpdate() 
    {
        $data = $this->tester->grabFixture('dataCategory')->data['update'];

        $baseModel = Category::find()->where(['!=','id',1])->with(['manyLang','aliasLang'])->one();
        $this->assertNotEmpty($baseModel['manyLang']);
        $this->assertNotEmpty($baseModel['aliasLang']);

        $form = new CategoryForm($baseModel);
        $langModel = new CategoryLang();

        $this->assertTrue($form->load($data));
        $this->assertTrue($form->validate());
        $this->assertTrue(LangWidget::validate($langModel,$data));

        $this->service->edit($baseModel->id, $form);
        $langModel->updateLang($data['CategoryLang'],$baseModel->id);

        $langArray = Lang::find()->asArray()->indexBy('alias')->all();
        $baseModelDb = Category::find()->where(['id' => $baseModel->id])->asArray()->one();
        $langRuModelDb = CategoryLang::find()->where(['category_id' => $baseModel->id])->andWhere(['lang_id' => $langArray['ru']['id']])->asArray()->one();
        $langEnModelDb = CategoryLang::find()->where(['category_id' => $baseModel->id])->andWhere(['lang_id' => $langArray['en']['id']])->asArray()->one();
        
        $this->assertEquals($baseModelDb['alias'], $form->getAttributes()['alias']);
        $this->assertEquals($baseModelDb['lft'], $baseModel['lft']);
        $this->assertEquals($baseModelDb['rgt'], $baseModel['rgt']);
        $this->assertEquals($baseModelDb['depth'], $baseModel['depth']);

        $this->assertEquals($langRuModelDb['title'], $data['CategoryLang']['ru']['title']);
        $this->assertEquals($langEnModelDb['title'], $data['CategoryLang']['en']['title']);
    }
    
    public function testEmptyUpdate() 
    {
        $data = $this->tester->grabFixture('dataEmptyCategory')->data;

        $baseModel = Category::find()->where(['!=','id',1])->with(['manyLang','aliasLang'])->one();
        $this->assertNotEmpty($baseModel['manyLang']);
        $this->assertNotEmpty($baseModel['aliasLang']);

        $form = new CategoryForm($baseModel);
        $langModel = new CategoryLang();

        $this->assertTrue($form->load($data)); //assertTrue because structure is observed
        $this->assertFalse($form->validate()); 
        $this->assertFalse(LangWidget::validate($langModel,$data));
    }

    public function testSuccessDelete()
    {
        $category = Category::find()->where(['!=','id',1])->with(['manyLang','aliasLang'])->one();

        $this->service->remove($category->id);

        $this->assertEmpty(Category::find()->where(['id' => $category->id])->one());
        $this->assertEmpty(CategoryLang::find()->where(['category_id' => $category->id])->all());
    }
}