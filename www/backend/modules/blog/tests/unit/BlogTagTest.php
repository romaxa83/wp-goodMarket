<?php 
namespace backend\modules\blog\tests\unit;

use common\models\Lang;
use backend\widgets\langwidget\LangWidget;

use backend\modules\blog\repository\TagRepository;
use backend\modules\blog\services\TagService;

use backend\modules\blog\entities\Tag;
use backend\modules\blog\entities\TagLang;
use backend\modules\blog\forms\TagForm;
//db Fixture
use backend\modules\blog\tests\fixtures\dbFixture\LangFixture;
use backend\modules\blog\tests\fixtures\dbFixture\TagFixture;
use backend\modules\blog\tests\fixtures\dbFixture\TagLangFixture;
//array Fixture
use backend\modules\blog\tests\fixtures\arrayFixture\DataTagFixture;
use backend\modules\blog\tests\fixtures\arrayFixture\DataEmptyTagFixture;

use Codeception\Test\Unit;

class BlogTagTest extends Unit 
{
    public $tester;
    private $service;

    public function _before() 
    {
        $this->service = new TagService(new TagRepository());

        $this->tester->haveFixtures([
            'lang' => [
                'class' => LangFixture::className(),
            ],
            'tag' => [
                'class' => TagFixture::className(),
            ],
            'tagLang' => [
                'class' => TagLangFixture::className(),
            ]
        ]);
    }

    public function _after() 
    {
        Tag::deleteAll();
        TagLang::deleteAll();
        Lang::deleteAll();
    }

    public function testSuccessCreate()
    {
        $data = $this->tester->grabFixture('dataTag')->data['create'];
        
        $form = new TagForm();
        $langModel = new TagLang();

        $this->assertTrue($form->load($data));
        $this->assertTrue($form->validate());
        $this->assertTrue(LangWidget::validate($langModel,$data));

        $baseModel = $this->service->create($form);
        $this->assertInstanceOf(Tag::class,$baseModel);

        $langModel->saveLang($data['TagLang'],$baseModel->id);

        $langArray = Lang::find()->asArray()->indexBy('alias')->all();
        $baseModelDb = Tag::find()->where(['id' => $baseModel->id])->asArray()->one();
        $langRuModelDb = TagLang::find()->where(['tag_id' => $baseModel->id])->andWhere(['lang_id' => $langArray['ru']['id']])->asArray()->one();
        $langEnModelDb = TagLang::find()->where(['tag_id' => $baseModel->id])->andWhere(['lang_id' => $langArray['en']['id']])->asArray()->one();

        $this->assertEquals($baseModelDb['alias'], $form->getAttributes()['alias']);
        
        $this->assertEquals($langRuModelDb['title'], $data['TagLang']['ru']['title']);
        $this->assertEquals($langEnModelDb['title'], $data['TagLang']['en']['title']);
    }

    public function testCreateEmpty()
    {
        $data = $this->tester->grabFixture('dataEmptyTag')->data;

        $form = new TagForm();
        $langModel = new TagLang();

        $this->assertTrue($form->load($data)); //assertTrue because structure is observed
        $this->assertFalse($form->validate()); 
        $this->assertFalse(LangWidget::validate($langModel,$data));
    }

    public function testSuccessUpdate()
    {
        $data = $this->tester->grabFixture('dataTag')->data['update'];

        $baseModel = Tag::find()->with(['manyLang','aliasLang'])->one();

        $this->assertNotEmpty($baseModel['manyLang']);
        $this->assertNotEmpty($baseModel['aliasLang']);

        $form = new TagForm($baseModel);
        $langModel = new TagLang();

        $this->assertTrue($form->load($data));
        $this->assertTrue($form->validate());
        $this->assertTrue(LangWidget::validate($langModel,$data));

        $this->service->edit($baseModel->id, $form);
        $langModel->updateLang($data['TagLang'],$baseModel->id);

        $langArray = Lang::find()->asArray()->indexBy('alias')->all();
        $baseModelDb = Tag::find()->where(['id' => $baseModel->id])->asArray()->one();
        $langRuModelDb = TagLang::find()->where(['tag_id' => $baseModel->id])->andWhere(['lang_id' => $langArray['ru']['id']])->asArray()->one();
        $langEnModelDb = TagLang::find()->where(['tag_id' => $baseModel->id])->andWhere(['lang_id' => $langArray['en']['id']])->asArray()->one();
        
        $this->assertEquals($baseModelDb['alias'], $form->getAttributes()['alias']);

        $this->assertEquals($langRuModelDb['title'], $data['TagLang']['ru']['title']);
        $this->assertEquals($langEnModelDb['title'], $data['TagLang']['en']['title']);
    }

    public function testEmptyUpdate()
    {
        $data = $this->tester->grabFixture('dataEmptyTag')->data;

        $baseModel = Tag::find()->with(['manyLang','aliasLang'])->one();
        $this->assertNotEmpty($baseModel['manyLang']);
        $this->assertNotEmpty($baseModel['aliasLang']);

        $form = new TagForm($baseModel);
        $langModel = new TagLang();

        $this->assertTrue($form->load($data)); //assertTrue because structure is observed
        $this->assertFalse($form->validate()); 
        $this->assertFalse(LangWidget::validate($langModel,$data));
    }

    public function testSuccessDelete()
    {
        $tag = Tag::find()->with(['manyLang','aliasLang'])->one();

        $this->service->remove($tag);

        $this->assertEmpty(Tag::find()->where(['id' => $tag->id])->one());
        $this->assertEmpty(TagLang::find()->where(['tag_id' => $tag->id])->all());
    }
}
?>