<?php

use backend\modules\banners\tests\fixtures\BannerFixture;
use backend\modules\banners\tests\fixtures\BannerLangFixture;
use backend\modules\banners\tests\fixtures\LangFixture;
use backend\modules\banners\models\Banner;
use backend\modules\banners\models\BannerLang;
use Codeception\Test\Unit;

class BannerTest extends Unit {

    public $tester;
    public $data = [
        'Banner' => [
            'position' => 2,
            'status' => 1,
        ],
        'BannerLang' => [
            'ru' => [
                'media_id' => 1,
                'alias' => 'lorem-ipsum-eto-tekst-ryba-chasto-ispolzuemyj-v-pechati-i-veb-dizajne',
                'title' => 'Lorem Ipsum - это текст-"рыба", часто используемый в печати и вэб-дизайне.',
                'text' => 'Lorem Ipsum - это текст-"рыба", часто используемый в печати и вэб-дизайне. Lorem Ipsum является стандартной "рыбой" для текстов на латинице с начала XVI века. В то время некий безымянный печатник создал большую коллекцию размеров и форм шрифтов,'
            ],
            'en' => [
                'media_id' => 2,
                'alias' => 'lorem-ipsum-is-simply-dummy-text-of-the-printing-and-typesetting-industry',
                'title' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.',
                'text' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.'
            ]
        ],
    ];

    public function _before() {
        $this->tester->haveFixtures([
            'banner' => [
                'class' => BannerFixture::className(),
            ],
            'banner-lang' => [
                'class' => BannerLangFixture::className(),
            ],
            'lang' => [
                'class' => LangFixture::className(),
            ]
        ]);
    }

    public function _after() {
        Banner::deleteAll();
        BannerLang::deleteAll();
    }

    public function testCreate() {
        $this->assertTrue(BannerLang::saveAll(new Banner(), new BannerLang(), $this->data));
    }

    private function emptyData($array) {
        foreach ($array as $key => &$value) {
            if (empty($value)) {
                $array[$key] = null;
            } else {
                if (is_array($value)) {
                    $value = $this->emptyData($value);
                    if (empty($value)) {
                        $array[$key] = null;
                    }
                }
            }
        }
        return $array;
    }

    public function testEmpty() {
        $data = $this->emptyData($this->data);
        var_dump($data);
        exit();
        ob_flush();

        $model = new Banner();
        $this->assertTrue($model->load($data));
        $this->assertFalse($model->validate());

        expect_that($model->getErrors('status'));
        expect($model->getFirstError('status'))
                ->equals('Необходимо заполнить «Опубликовать».');
    }

//
//    public function testEditWithLangAndSeo() {
//        $category = $this->tester->grabFixture('category', 1);
//        $category->scenario = Category::SAVED_CATEGORY;
//        $categoryLang1 = $this->tester->grabFixture('category-lang', 1);
//        $categoryLang2 = $this->tester->grabFixture('category-lang', 2);
//        $seoMeta1 = $this->tester->grabFixture('seo-meta', 1);
//        $seoMeta2 = $this->tester->grabFixture('seo-meta', 2);
//
//        $this->assertTrue($category->load($this->data));
//        $this->assertTrue($category->save());
//        $this->assertTrue(CategoryLang::saveAll($category->id, $this->data['Category']['Language']));
//        $this->assertGreaterThan(0, SeoWidget::save($category->id, 'category', $this->data['SEO']));
//
//        expect($category->alias)->equals($this->data['Category']['alias']);
//        expect($category->parent_id)->equals($this->data['Category']['parent_id']);
//        expect($category->rating)->equals($this->data['Category']['rating']);
//        expect($category->media_id)->equals($this->data['Category']['media_id']);
//        expect($category->publish)->equals($this->data['Category']['publish']);
//        expect(CategoryLang::find()->select('name')->where(['id' => $categoryLang1->id])->one()->name)->equals($this->data['Category']['Language']['ru']['name']);
//        expect(CategoryLang::find()->select('name')->where(['id' => $categoryLang2->id])->one()->name)->equals($this->data['Category']['Language']['eng']['name']);
//        $seoMeta1 = SeoMeta::find()->where(['id' => $seoMeta1->id])->one();
//        $seoMeta2 = SeoMeta::find()->where(['id' => $seoMeta2->id])->one();
//        expect($seoMeta1->h1)->equals($this->data['SEO']['ru']['h1']);
//        expect($seoMeta1->title)->equals($this->data['SEO']['ru']['title']);
//        expect($seoMeta1->keywords)->equals($this->data['SEO']['ru']['keywords']);
//        expect($seoMeta1->description)->equals($this->data['SEO']['ru']['description']);
//        expect($seoMeta1->seo_text)->equals($this->data['SEO']['ru']['seo_text']);
//        expect($seoMeta2->h1)->equals($this->data['SEO']['eng']['h1']);
//        expect($seoMeta2->title)->equals($this->data['SEO']['eng']['title']);
//        expect($seoMeta2->keywords)->equals($this->data['SEO']['eng']['keywords']);
//        expect($seoMeta2->description)->equals($this->data['SEO']['eng']['description']);
//        expect($seoMeta2->seo_text)->equals($this->data['SEO']['eng']['seo_text']);
//    }
}
