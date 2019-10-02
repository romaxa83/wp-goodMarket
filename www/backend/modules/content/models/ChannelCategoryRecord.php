<?php

namespace backend\modules\content\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "channel_category_record".
 *
 * @property int $id
 * @property int $category_id
 * @property int $record_id
 *
 * @property ChannelCategory $category
 * @property ChannelRecord $record
 */
class ChannelCategoryRecord extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'channel_category_record';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'record_id'], 'required'],
            [['category_id', 'record_id'], 'integer'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ChannelCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['record_id'], 'exist', 'skipOnError' => true, 'targetClass' => ChannelRecord::className(), 'targetAttribute' => ['record_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'record_id' => 'Record ID',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(ChannelCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getRecord()
    {
        return $this->hasOne(ChannelRecord::className(), ['id' => 'record_id']);
    }

    public static function getRecordActiveCategoriesId($record_id)
    {
        $categories =  self::find()
            ->select('category_id')
            ->where(['record_id' => $record_id])
            ->asArray()
            ->all();

        $ids = array();
        foreach($categories as $category) {
            $ids[] = $category['category_id'];
        }

        return $ids;
    }
}