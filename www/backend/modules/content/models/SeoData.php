<?php

namespace backend\modules\content\models;

use Yii;

/**
 * This is the model class for table "seo_data".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $keywords
 *
 * @property Channel $id0
 * @property ChannelRecord $id1
 */
class SeoData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'seo_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description'], 'required'],
            [['description'], 'string'],
            [['title', 'keywords'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'keywords' => 'Keywords',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId0()
    {
        return $this->hasOne(Channel::className(), ['seo_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getId1()
    {
        return $this->hasOne(ChannelRecord::className(), ['seo_id' => 'id']);
    }
}