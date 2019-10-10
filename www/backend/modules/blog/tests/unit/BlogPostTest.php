<?php 
namespace backend\modules\blog\tests\unit;

use common\models\Lang;
use backend\widgets\langwidget\LangWidget;

use backend\modules\blog\repository\TagRepository;
use backend\modules\blog\repository\TagAssignmentsRepository;
use backend\modules\blog\repository\PostRepository;
use backend\modules\blog\repository\CategoryRepository;
use backend\modules\blog\repository\MetaRepository;

use backend\modules\blog\services\PostService;

use backend\modules\blog\entities\Post;
use backend\modules\blog\entities\PostLang;
use backend\modules\seo\models\SeoMeta;

use backend\modules\blog\forms\PostForm;
//db Fixture
use backend\modules\blog\tests\fixtures\dbFixture\LangFixture;
use backend\modules\blog\tests\fixtures\dbFixture\CategoryFixture;
use backend\modules\blog\tests\fixtures\dbFixture\CategoryLangFixture;
use backend\modules\blog\tests\fixtures\dbFixture\TagFixture;
use backend\modules\blog\tests\fixtures\dbFixture\TagLangFixture;
use backend\modules\blog\tests\fixtures\dbFixture\ImageFixture;
use backend\modules\blog\tests\fixtures\dbFixture\PostFixture;
use backend\modules\blog\tests\fixtures\dbFixture\PostLangFixture;
use backend\modules\blog\tests\fixtures\dbFixture\SeoFixture;

use Codeception\Test\Unit;

class BlogPostTest extends Unit 
{
    public $tester;
    private $service;

    public function _before() 
    {
        $this->service = new PostService(
            new PostRepository(
                new CategoryRepository(),
                new TagRepository()
            ),
            new CategoryRepository(),
            new TagRepository(),
            new TagAssignmentsRepository(),
            new MetaRepository()
        );

        $this->tester->haveFixtures([
            'lang' => [
                'class' => LangFixture::className()
            ],
            'image' => [
                'class' => ImageFixture::className()
            ],
            'category' => [
                'class' => CategoryFixture::className()
            ],
            'categoryLang' => [
                'class' => CategoryLangFixture::className()
            ],
            'tag' => [
                'class' => TagFixture::className()
            ],
            'tagLang' => [
                'class' => TagLangFixture::className()
            ],
            'seo' => [
                'class' => SeoFixture::className()
            ],
            'post' => [
                'class' => PostFixture::className()
            ],
            'postLang' => [
                'class' => PostLangFixture::className()
            ]
        ]);
    }

    public function _after() 
    {
        Post::deleteAll();
        PostLang::deleteAll();
        SeoMeta::deleteAll();
        Lang::deleteAll();
    }

    public function testSuccessCreate()
    {
        $data = $this->tester->grabFixture('dataPost')->data['create'];

        $form = new PostForm();
        $langModel = new PostLang();

        $this->assertTrue($form->load($data));
        $this->assertTrue($form->validate());
        $this->assertTrue(LangWidget::validate($langModel,$data));

        $baseModel = $this->service->create($form);
        $this->assertInstanceOf(Post::class,$baseModel);

        $langModel->saveLang($data['PostLang'],$baseModel->id);
    }

    public function testCreateEmpty()
    {
        $data = $this->tester->grabFixture('dataEmptyPost')->data;

        $form = new PostForm();
        $langModel = new PostLang();

        $this->assertTrue($form->load($data)); //assertTrue because structure is observed
        $this->assertFalse($form->validate()); 
        $this->assertFalse(LangWidget::validate($langModel,$data));
    }

    public function testSuccessUpdate()
    {
        $data = $this->tester->grabFixture('dataPost')->data['update'];

        $baseModel = Post::find()->with(['manyLang','aliasLang'])->one();

        $this->assertNotEmpty($baseModel['manyLang']);
        $this->assertNotEmpty($baseModel['aliasLang']);

        $form = new PostForm($baseModel);
        $langModel = new PostLang();

        $this->assertTrue($form->load($data));
        $this->assertTrue($form->validate());
        $this->assertTrue(LangWidget::validate($langModel,$data));

        $this->service->edit($baseModel->id, $form);
        $langModel->updateLang($data['PostLang'],$baseModel->id);

        $langArray = Lang::find()->asArray()->indexBy('alias')->all();
        $baseModelDb = Post::find()->where(['id' => $baseModel->id])->asArray()->one();
        $langRuModelDb = PostLang::find()->where(['post_id' => $baseModel->id])->andWhere(['lang_id' => $langArray['ru']['id']])->asArray()->one();
        $langEnModelDb = PostLang::find()->where(['post_id' => $baseModel->id])->andWhere(['lang_id' => $langArray['en']['id']])->asArray()->one();
        
        $this->assertEquals($baseModelDb['alias'], $form->getAttributes()['alias']);

        $this->assertEquals($langRuModelDb['title'], $data['PostLang']['ru']['title']);
        $this->assertEquals($langEnModelDb['title'], $data['PostLang']['en']['title']);
    }

    public function testEmptyUpdate()
    {
        $data = $this->tester->grabFixture('dataEmptyPost')->data;

        $baseModel = Post::find()->with(['manyLang','aliasLang'])->one();
        $this->assertNotEmpty($baseModel['manyLang']);
        $this->assertNotEmpty($baseModel['aliasLang']);

        $form = new PostForm($baseModel);
        $langModel = new PostLang();

        $this->assertTrue($form->load($data)); //assertTrue because structure is observed
        $this->assertFalse($form->validate()); 
        $this->assertFalse(LangWidget::validate($langModel,$data));
    }

    public function testSuccessDelete()
    {
        $post = Post::find()->with(['manyLang','aliasLang'])->one();

        $this->service->remove($post);

        $this->assertEmpty(Post::find()->where(['id' => $post->id])->one());
        $this->assertEmpty(PostLang::find()->where(['post_id' => $post->id])->all());
    }
}
?>
