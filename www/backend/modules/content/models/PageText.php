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
class PageText extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'page_text';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name', 'label', 'type'], 'required'],
            [['page_id'], 'integer'],
            [['name', 'label', 'type', 'text'], 'string'],
            [['page_id'], 'exist', 'skipOnError' => true, 'targetClass' => Page::className(), 'targetAttribute' => ['page_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
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
    public function getPage() {
        return $this->hasOne(Page::className(), ['id' => 'page_id']);
    }

}