<?php

namespace backend\modules\settings\models;


/**
 * @property int $id
 * @property string $alias
 * @property string $title
 * @property string $description
 * @property string $image
 * @property int $status
 */
class ProductStaticData extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'static_data';
    }

    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['alias','title','description'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alias' => 'Alias',
            'title' => 'Title',
            'description' => 'Description',
            'image' => 'Image',
            'status' => 'Status',
        ];
    }


}
