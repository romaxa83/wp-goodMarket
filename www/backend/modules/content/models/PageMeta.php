<?php

namespace backend\modules\content\models;

use Yii;
use common\models\Lang;
/**
 * This is the model class for table "page_meta".
 *
 * @property int $id
 * @property int $page_id
 * @property string $title
 * @property string $description
 * @property string $keywords
 *
 * @property Page $page
 */
class PageMeta extends \yii\db\ActiveRecord 
{

    /**
     * {@inheritdoc}
     */
    public static function tableName() 
    {
        return 'page_meta';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() 
    {
        return [
            [['page_id'], 'integer'],
            [['page_id'], 'exist', 'skipOnError' => true, 'targetClass' => Page::className(), 'targetAttribute' => ['page_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() 
    {
        return [
            'id' => 'ID',
            'page_id' => 'Page ID'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage() 
    {
        return $this->hasOne(Page::className(), ['id' => 'page_id']);
    }

    public function getOneLang()
    {   
        return $this->hasOne(PageMetaLang::class, ['meta_id' => 'id']);
    }

    public function getManyLang()
    {   
        return $this->hasMany(PageMetaLang::class, ['meta_id' => 'id']);
    }

    public function getAliasLang()
    {   
        return $this->hasMany(Lang::class, ['id' => 'lang_id'])->via('manyLang');
    }

}
