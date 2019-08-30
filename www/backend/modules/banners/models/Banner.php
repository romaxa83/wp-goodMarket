<?php

namespace backend\modules\banners\models;

use backend\modules\filemanager\models\Mediafile;
use backend\modules\banners\models\BannerLang;
use yii\db\ActiveRecord;

class Banner extends ActiveRecord {

    public $languageData;

    public static function tableName() {
        return 'banner';
    }

    public function rules() {
        return [
            [['text', 'title', 'alias'], 'string'],
            [['text', 'title', 'alias', 'media_id'], 'required', 'message' => 'Заполните поле'],
            [['publication'], 'integer'],
            ['image', 'image', 'extensions' => 'jpg, jpeg, gif, png', 'on' => ['insert', 'update']],
        ];
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'text' => 'Текст',
            'alias' => 'Ссылка',
            'status' => 'Опубликовать',
            'media_id' => 'Обложка'
        ];
    }

    public static function getFrontDataAll($alias) {
        $request = self::find()
                ->select('banners.*, filemanager_mediafile.*')
                ->where(['language' => $alias, 'publication' => 1])
                ->leftJoin('filemanager_mediafile', 'banners.media_id=filemanager_mediafile.id')
                ->orderBy('position ASC')
                ->asArray()
                ->all();
        return $request;
    }

    public static function getFrontDataOne($alias, $id) {
        $request = self::find()
                ->select('banners.*, filemanager_mediafile.*')
                ->where(['language' => $alias])
                ->andWhere(['id' => $id])
                ->orWhere(['parent' => $id])
                ->andWhere(['publication' => 1])
                ->leftJoin('filemanager_mediafile', 'banners.media_id=filemanager_mediafile.id')
                ->one();
        return $request;
    }

    public static function getBannersCount() {
        return self::find()->where(['parent' => 0])->count('*');
    }

    public static function getActiveStatusCount() {
        return self::find()->where(['parent' => 0])->andWhere(['publication' => 1])->count('*');
    }

    public static function getActiveElementOne() {
        return self::find()->select('id')->where(['parent' => 0])->andWhere(['publication' => 1])->asArray()->one();
    }

    public function getMedia() {
        return $this->hasOne(Mediafile::className(), ['id' => 'media_id']);
    }

    public function getBannerLang() {
        return $this->hasMany(BannerLang::className(), ['banner_id' => 'id']);
    }

}
