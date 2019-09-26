<?php

use backend\modules\reviews\models\AnswerForm;
use backend\modules\reviews\models\ReviewForm;
use backend\modules\reviews\models\Reviews;
use backend\modules\reviews\tests\fixtures\DataReviewsFixture;
use backend\modules\reviews\tests\fixtures\DataEmptyFixture;
use backend\modules\reviews\tests\fixtures\ReviewsFixture;
use Codeception\Test\Unit;

class ReviewsTest extends Unit {

    /**
     * @var \UnitTester
     */
    public $tester;

    public function _fixtures() {
        return [
            'data_reviews' => DataReviewsFixture::className(),
            'data_empty' => DataEmptyFixture::className(),
        ];
    }

    /* подгрузка данных в тестовую бд, перед тестами */
    public function _before() {
        $this->tester->haveFixtures([
            'reviews' => [
                'class' => ReviewsFixture::className(),
            ]
        ]);
    }

    public function _after() {
        Reviews::deleteAll();
    }

    public function testCreateReviewSuccess() {
        $data = $this->tester->grabFixture('data_reviews')->data;
        $model = new Reviews();
        $form = new ReviewForm();

        $this->assertTrue($form->load($data));
        $this->assertTrue($form->validate());

        $this->assertTrue($model->load($data));
        $this->assertTrue($model->save());
    }

    public function testCreateAnswerSuccess() {
        $data = $this->tester->grabFixture('data_reviews')->data;
        $data['Reviews']['answer_id'] = 1;
        $model = new Reviews();
        $form = new AnswerForm();

        $this->assertTrue($form->load($data));
        $this->assertTrue($form->validate());

        $this->assertTrue($model->load($data));
        $this->assertTrue($model->save());
    }

    public function testEmptyCreate() {
        $data = $this->tester->grabFixture('data_empty')->data;
        $model = new Reviews();
        $form = new ReviewForm();

        $this->assertTrue($form->load($data));
        $this->assertFalse($form->validate());

        $this->assertTrue($model->load($data));
        $this->assertFalse($model->save());
    }

    public function testEditReviewSuccess() {
        $data = $this->tester->grabFixture('data_reviews')->data;
        $review = Reviews::find()->one();

        $this->assertTrue($review->load($data));
        $this->assertTrue($review->save());
    }

    public function testSuccessDelete() {
        $review = Reviews::find()->one();
        $review->delete();
        $this->assertEmpty(Reviews::find()->where(['id' => $review->id])->one());
    }


}
