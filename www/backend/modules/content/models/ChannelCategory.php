<?php

namespace backend\modules\content\models;

use backend\modules\referenceBooks\models\Country;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "channel_category".
 *
 * @property int $id
 * @property int $channel_id
 * @property string $title
 * @property int $route_id
 * @property int $seo_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ChannelRecord[] $channelRecords
 * @property Channel $channel
 * @property SeoData $seoData
 * @property SlugManager $slugManager
 * @property ChannelCategoryContent $channelCategoryContent
 */
class ChannelCategory extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'channel_category';
    }

    public function behaviors() {
        return [
            'saveRelations' => [
                'class' => SaveRelationsBehavior::className(),
                'relations' => [
                    'channelCategoryContent' => ['cascadeDelete' => true],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['channel_id', 'title', 'status'], 'required'],
            [['channel_id', 'route_id', 'seo_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'channel_id' => 'Channel ID',
            'title' => 'Название',
            'route_id' => 'Route ID',
            'seo_id' => 'Seo ID',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public function getChannelRecords()
    {
        return $this->hasMany(ChannelRecord::className(), ['id' => 'record_id'])
            ->viaTable('channel_category_record', ['category_id' => 'id'])
            ->with([
                'channelRecordContent' => function(ActiveQuery $query) {
                    $query->indexBy('name');
                }
            ]);
    }

    public function getChannel()
    {
        return $this->hasOne(Channel::className(), ['id' => 'channel_id']);
    }

    public function getSeoData()
    {
        return $this->hasOne(SeoData::className(), ['id' => 'seo_id']);
    }

    public function getSlugManager() {
        return $this->hasOne(SlugManager::className(), ['id' => 'route_id']);
    }

    public function getChannelCategoryContent()
    {
        return $this->hasMany(ChannelCategoryContent::className(), ['channel_id' => 'id']);
    }

    public static function getStatusForRecord($categoryId, $recordId = null)
    {
        if(is_null($recordId)) return false;

        $ids = ChannelCategoryRecord::getRecordActiveCategoriesId($recordId);

        $status = in_array($categoryId, $ids);

        return $status;
    }

    public static function getActiveCategories($channelId)
    {
        $categories = self::find()
            ->where([
                'channel_id' => $channelId,
                'status' => 1
            ])
            ->with([
                'channelRecords' => function(ActiveQuery $query) {
                    $query->where('status=1');
                }
            ])
            ->asArray()
            ->all();

        $result = array();
        $allCountries = array();
        foreach($categories as $category) {
            $countries = array();
            foreach($category['channelRecords'] as $record) {
                $countryId = $record['channelRecordContent']['api_country_id']['content'];
                $country = Country::find()->where(['id' => $countryId])->asArray()->one();
                $countries[] = $country['alpha_2_code'];
                if(! in_array($country['alpha_2_code'], $allCountries)) {
                    $allCountries[] = $country['alpha_2_code'];
                }
            }
            $result[] = array(
                'id' => $category['id'],
                'title' => $category['title'],
                'countries' => $countries
            );
        }
        array_unshift($result, array(
            'id' => 0,
            'title' => 'Все',
            'countries' => $allCountries
        ));
        return $result;
    }
}