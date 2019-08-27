<?php

namespace backend\widgets;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use backend\modules\seo\models\SeoMeta;
use backend\widgets\langwidget\LangWidget;

class SeoWidget extends Widget {

    public $id;
    public $languages;
    public $fields;

    public function init() {
        $this->languages;
        $this->fields = [
            ['name' => 'h1', 'type' => 'text', 'label' => 'Заголовок'],
            ['name' => 'title', 'type' => 'text', 'label' => 'Название'],
            ['name' => 'keywords', 'type' => 'text', 'label' => 'Ключевые слова'],
            ['name' => 'description', 'type' => 'textarea', 'label' => 'Описание'],
            ['name' => 'seo_text', 'type' => 'widget', 'label' => 'SEO текст']
        ];
        parent::init();
    }

    public function run() {
        $seo_data = [];
        $this->languages = ArrayHelper::map(LangWidget::getActiveLanguageData(['alias','lang']), 'alias', 'lang');
        $seo = SeoMeta::find()->asArray()->where(['page_id' => $this->id])->all();
        foreach ($seo as $k => $v)
            $seo_data[$v['language']] = $v;
        return $this->render('seo/index', [
                    'languages' => $this->languages,
                    'fields' => $this->fields,
                    'seo_data' => $seo_data
        ]);
    }

    public static function save($id,$alias,$data) {
        foreach ($data as $k => $v) {
            $seo = SeoMeta::find()->where(['page_id' => $id])->andWhere(['language' => $k])->one();
            if ($seo === NULL)
                $seo = new SeoMeta();
            $seo->page_id = $id;
            $seo->h1 = isset($v['h1']) ? $v['h1'] : NULL;
            $seo->title = isset($v['title']) ? $v['title'] : NULL;
            $seo->keywords = isset($v['keywords']) ? $v['keywords'] : NULL;
            $seo->description = isset($v['description']) ? $v['description'] : NULL;
            $seo->seo_text = isset($v['seo_text']) ? $v['seo_text'] : NULL;
            $seo->language = $k;
            $seo->alias = $alias;
            $seo->parent_id = ($k == 'ru') ? NULL : $id;
            $seo->save();
        }
        return $seo->id;
    }

}
