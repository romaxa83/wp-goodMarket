<?php

namespace backend\modules\content\models;

use Yii;

/**
 * This is the model class for table "page_text".
 *
 * @property int $id
 * @property int $page_id
 * @property string $name
 * @property string $label
 * @property string $type
 * @property string $text
 *
 * @property Page $page
 */
class PageTextLang extends \yii\db\ActiveRecord 
{

    /**
     * {@inheritdoc}
     */
    public static function tableName() 
    {
        return 'page_text_lang';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() 
    {
        return [
            [['name', 'label', 'type'], 'required'],
            [['page_id','category_id'], 'integer'],
            [['name', 'label', 'type'], 'string'],
            [['page_id'], 'exist', 'skipOnError' => true, 'targetClass' => Page::className(), 'targetAttribute' => ['page_id' => 'id']],
            ['category_id', 'unique', 'when' => function(){
                return !self::find()->where(['page_id' => $this->page_id])->andWhere(['category_id' => $this->category_id])->exists();
            }],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() 
    {
        return [
            'id' => 'ID',
            'page_id' => 'Page ID',
            'name' => 'Название блока',
            'label' => 'Подпись',
            'text' => 'Контент',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage() 
    {
        return $this->hasOne(Page::className(), ['id' => 'page_id']);
    }

    public static function preparePostData($content)
    {
        return array_map(function($row) {
            if(!isset($row['text']) || !is_array($row['text']) ) {
                return $row;
            }
            $row['text'] = serialize($row['text']);
            return $row;
        }, $content);
    }
}