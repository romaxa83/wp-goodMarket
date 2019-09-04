<?php

use backend\modules\banners\tests\fixtures\BannerFixture;
use backend\modules\banners\tests\fixtures\BannerLangFixture;
use backend\modules\banners\tests\fixtures\LangFixture;
use backend\modules\banners\models\Banner;
use backend\modules\banners\models\BannerLang;
use Codeception\Test\Unit;
use backend\modules\banners\tests\fixtures\DataEmptyFixture;
use backend\modules\banners\tests\fixtures\DataFixture;

class BannerTest extends Unit {

    public $tester;

    public function _fixtures() {
        return [
            'data' => DataFixture::className(),
            'data_empty' => DataEmptyFixture::className(),
        ];
    }

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
        $this->assertTrue(BannerLang::saveAll(new Banner(), new BannerLang(), $this->tester->grabFixture('data')->data));
    }

    public function testEmpty() {
        $model = new Banner();
        $this->assertTrue($model->load($this->tester->grabFixture('data_empty')->data));
        $this->assertFalse($model->validate());
        expect_that($model->getErrors('status'));
        expect($model->getFirstError('status'))
                ->equals('Необходимо заполнить «Опубликовать».');
    }

}
