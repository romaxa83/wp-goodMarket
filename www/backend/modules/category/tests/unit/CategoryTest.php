<?php

use backend\modules\category\models\Category;
use Codeception\Test\Unit;

class CategoryTest extends Unit {
    public function testTrue() {
        $this->assertTrue(true);
    }

    public function testCreateSuccess() {
        $data = [
            'Category' => [
                'alias' => 'category_1',
                'parent_id' => 1,
                'rating' => 1,
                'media_id' => 1,
                'publish' => 1,
            ],
            'SEO' => [
                'page_id' => 1,
                'h1' => 'header',
                'title' => 'title',
                'keywords' => 'keywords',
                'description' => 'category_1',
                'seo_text' => '<p><span class="label-red">Label</span></p>',
                'language' => 'eng',
                'parent_id' => 1,
                'alias' => 'category',
            ]
        ];
        $model = new Category();
        $model->scenario = Category::ADDED_CATEGORY;
        $this->assertTrue($model->load($data));
        $this->assertTrue($model->save());
    }
}
