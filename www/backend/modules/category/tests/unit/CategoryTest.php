<?php

use backend\modules\category\tests\fixtures\CategoryFixture;
use backend\modules\category\tests\fixtures\CategoryLangFixture;
use backend\modules\category\tests\fixtures\LangFixture;
use backend\modules\category\tests\fixtures\SeoMetaFixture;
use backend\modules\category\models\Category;
use backend\modules\category\models\CategoryLang;
use backend\modules\seo\models\SeoMeta;
use backend\widgets\SeoWidget;
use Codeception\Test\Unit;

class CategoryTest extends Unit {

    public $data = [
        'Category' => [
            'alias' => 'category_5',
            'parent_id' => 0,
            'rating' => 1,
            'media_id' => 1,
            'publish' => 1,
            'Language' => [
                'ru' => [
                    'name' => 'категория_5'
                ],
                'eng' => [
                    'name' => 'category_5'
                ]
            ],
        ],
        'SEO' => [
            'ru' => [
                'h1' => 'header_5_ru',
                'title' => 'title_5',
                'keywords' => 'keywords_5',
                'description' => 'category_5',
                'seo_text' => '<p><span class="label-red">Label</span></p>',
            ],
            'eng' => [
                'h1' => 'header_5_eng',
                'title' => 'title_5',
                'keywords' => 'keywords_5',
                'description' => 'category_5',
                'seo_text' => '<p><span class="label-red">Label</span></p>',
            ]
        ]
    ];

    /**
     * @var \UnitTester
     */
    public $tester;

    /* подгрузка данных в тестовую бд, перед тестами */
    public function _before() {
        $this->tester->haveFixtures([
            'category' => [
                'class' => CategoryFixture::className(),
            ],
            'category-lang' => [
                'class' => CategoryLangFixture::className(),
            ],
            'seo-meta' => [
                'class' => SeoMetaFixture::className(),
            ],
            'lang' => [
                'class' => LangFixture::className(),
            ]
        ]);
    }

    public function _after() {
        Category::deleteAll();
        CategoryLang::deleteAll();
        SeoMeta::deleteAll();
    }

    public function testCreateSuccess() {
        $model = new Category();
        $model->scenario = Category::ADDED_CATEGORY;
        $this->assertTrue($model->load($this->data));
        $this->assertTrue($model->save());
    }

    public function testCreateSuccessWithLangAndSeo() {
        $model = new Category();
        $model->scenario = Category::ADDED_CATEGORY;
        $this->assertTrue($model->load($this->data));
        $this->assertTrue($model->save());
        $this->assertTrue(CategoryLang::saveAll($model->id, $this->data['Category']['Language']));
        $this->assertGreaterThan(0, SeoWidget::save($model->id, 'category', $this->data['SEO']));
    }

    public function testEmpty() {
        $data = [
            'Category' => [
                'alias' => null,
                'rating' => null,
                'media_id' => null,
                'publish' => null,
            ]
        ];
        $model = new Category();
        $model->scenario = Category::ADDED_CATEGORY;
        $this->assertTrue($model->load($data));
        $this->assertFalse($model->validate());

        expect_that($model->getErrors('alias'));
        expect($model->getFirstError('alias'))
            ->equals('Необходимо заполнить «Алиас».');

        expect_that($model->getErrors('rating'));
        expect($model->getFirstError('rating'))
            ->equals('Необходимо заполнить «Рейтинг».');

        expect_that($model->getErrors('media_id'));
        expect($model->getFirstError('media_id'))
            ->equals('Необходимо заполнить «Медиа».');

        expect_that($model->getErrors('publish'));
        expect($model->getFirstError('publish'))
            ->equals('Необходимо заполнить «Опубликовать».');
    }

    public function testEditWithLangAndSeo() {
        $category = $this->tester->grabFixture('category', 1);
        $category->scenario = Category::SAVED_CATEGORY;
        $categoryLang1 = $this->tester->grabFixture('category-lang', 1);
        $categoryLang2 = $this->tester->grabFixture('category-lang', 2);
        $seoMeta1 = $this->tester->grabFixture('seo-meta', 1);
        $seoMeta2 = $this->tester->grabFixture('seo-meta', 2);

        $this->assertTrue($category->load($this->data));
        $this->assertTrue($category->save());
        $this->assertTrue(CategoryLang::saveAll($category->id, $this->data['Category']['Language']));
        $this->assertGreaterThan(0, SeoWidget::save($category->id, 'category', $this->data['SEO']));

        expect($category->alias)->equals($this->data['Category']['alias']);
        expect($category->parent_id)->equals($this->data['Category']['parent_id']);
        expect($category->rating)->equals($this->data['Category']['rating']);
        expect($category->media_id)->equals($this->data['Category']['media_id']);
        expect($category->publish)->equals($this->data['Category']['publish']);
        expect(CategoryLang::find()->select('name')->where(['id' => $categoryLang1->id])->one()->name)->equals($this->data['Category']['Language']['ru']['name']);
        expect(CategoryLang::find()->select('name')->where(['id' => $categoryLang2->id])->one()->name)->equals($this->data['Category']['Language']['eng']['name']);
        $seoMeta1 = SeoMeta::find()->where(['id' => $seoMeta1->id])->one();
        $seoMeta2 = SeoMeta::find()->where(['id' => $seoMeta2->id])->one();
        expect($seoMeta1->h1)->equals($this->data['SEO']['ru']['h1']);
        expect($seoMeta1->title)->equals($this->data['SEO']['ru']['title']);
        expect($seoMeta1->keywords)->equals($this->data['SEO']['ru']['keywords']);
        expect($seoMeta1->description)->equals($this->data['SEO']['ru']['description']);
        expect($seoMeta1->seo_text)->equals($this->data['SEO']['ru']['seo_text']);
        expect($seoMeta2->h1)->equals($this->data['SEO']['eng']['h1']);
        expect($seoMeta2->title)->equals($this->data['SEO']['eng']['title']);
        expect($seoMeta2->keywords)->equals($this->data['SEO']['eng']['keywords']);
        expect($seoMeta2->description)->equals($this->data['SEO']['eng']['description']);
        expect($seoMeta2->seo_text)->equals($this->data['SEO']['eng']['seo_text']);
    }

    public function testDelete() {
        $category = $this->tester->grabFixture('category', 1);

        Category::deleteAll('id = :id', ['id' => $category->id]);
        SeoMeta::deleteAll(['page_id' => $category->id, 'alias' => 'category']);
        CategoryLang::deleteAll(['category_id' => $category->id]);

        $this->assertFalse(Category::find()->where(['id' => $category->id])->exists());
        $this->assertFalse(CategoryLang::find()->where(['category_id' => $category->id])->exists());
        $this->assertFalse(SeoMeta::find()->where(['page_id' => $category->id, 'alias' => 'category'])->exists());
    }

}
