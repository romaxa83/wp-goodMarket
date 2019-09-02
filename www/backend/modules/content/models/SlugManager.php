<?php

namespace backend\modules\content\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "slug_manager".
 *
 * @property int $id
 * @property string $slug
 * @property string $route
 * @property string $template
 */
class SlugManager extends ActiveRecord {

    public function rules() {
        return [
            [['slug', 'route'], 'required'],
            [['slug', 'route', 'template'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'slug' => 'Псевдоним',
            'route' => 'Путь',
            'template' => 'Шаблон',
        ];
    }

}
