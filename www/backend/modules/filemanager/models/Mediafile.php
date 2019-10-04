<?php

namespace backend\modules\filemanager\models;

use backend\modules\banners\models\BannerLang;
use Imagick;
use Yii;
use yii\web\UploadedFile;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\imagine\Image;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Inflector;
use backend\modules\filemanager\FileManager;
use backend\modules\filemanager\models\Owners;
use Imagine\Image\ImageInterface;
use common\service\ImportService;
use backend\modules\category\models\Category;
use backend\modules\product\models\Product;
/**
 * This is the model class for table "filemanager_mediafile".
 *
 * @property integer $id
 * @property string $filename
 * @property string $type
 * @property string $url
 * @property string $alt
 * @property integer $size
 * @property string $description
 * @property string $thumbs
 * @property integer $created_at
 * @property integer $updated_at
 * @property Owners[] $owners
 * @property Tag[] $tags
 */
class Mediafile extends ActiveRecord
{
    public $file;

    public static $imageFileTypes = ['image/gif', 'image/jpeg', 'image/png', 'image/jpg'];

    /**
     * @var array|null
     */
    protected $tagIds = null;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $linkTags = function ($event) {
            if ($this->tagIds === null) {
                return;
            }
            if (!is_array($this->tagIds)) {
                $this->tagIds = [];
            }
            $whereIds = $models = $newTagIds = [];
            foreach ($this->tagIds as $tagId) {
                if (empty($tagId)) {
                    continue;
                }
                if (preg_match("/^\d+$/", $tagId)) {
                    $whereIds[] = $tagId;
                    continue;
                }
                // если tagId не число, то значит надо создать новый тег
                if (!$tag = Tag::findOne(['name' => $tagId])) {
                    $tag = new Tag();
                    $tag->name = $tagId;
                    if (!$tag->save()) {
                        continue;
                    }
                }
                $newTagIds[] = $tag->id;
                $models[] = $tag;
            }

            $this->unlinkAll('tags', true);
            if ($whereIds) {
                $models = array_merge($models, Tag::find()->where(['id' => $whereIds])->all());
            }
            foreach ($models as $model) {
                $this->link('tags', $model);
            }
            // что бы после сохранения в значение были новые теги
            $this->tagIds = array_merge($whereIds, $newTagIds);
        };

        $this->on(static::EVENT_AFTER_INSERT, $linkTags);
        $this->on(static::EVENT_AFTER_UPDATE, $linkTags);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'filemanager_mediafile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['filename', 'type', 'url', 'size'], 'required'],
            [['url', 'alt', 'description', 'thumbs'], 'string'],
            [['created_at', 'updated_at', 'size'], 'integer'],
            [['filename', 'type'], 'string', 'max' => 255],
            [['file'], 'file'],
            [['tagIds'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => FileManager::t('main', 'ID'),
            'filename' => FileManager::t('main', 'filename'),
            'type' => FileManager::t('main', 'Type'),
            'url' => FileManager::t('main', 'Url'),
            'alt' => FileManager::t('main', 'Alt attribute'),
            'size' => FileManager::t('main', 'Size'),
            'description' => FileManager::t('main', 'Description'),
            'thumbs' => FileManager::t('main', 'Thumbnails'),
            'created_at' => FileManager::t('main', 'Created'),
            'updated_at' => FileManager::t('main', 'Updated'),
            'tagIds' => FileManager::t('main', 'Tags'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwners()
    {
        return $this->hasMany(Owners::className(), ['mediafile_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags() {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
            ->viaTable('filemanager_mediafile_tag', ['mediafile_id' => 'id']);
    }

    /**
     * @return array|null
     */
    public function getTagIds() {
        return $this->tagIds !== null ? $this->tagIds : array_map(function ($tag) {
            return $tag->id;
        }, $this->tags);
    }

    /**
     * @param $value
     */
    public function setTagIds($value) {
        $this->tagIds = $value;
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {

            foreach ($this->owners as $owner) {
                $owner->delete();
            }

            return true;
        } else {
            return false;
        }
    }

	public function afterDelete()
	{
		parent::afterDelete();
		Tag::removeUnusedTags();
	}

	public function afterSave($insert, $changedAttributes)
	{
		parent::afterSave($insert, $changedAttributes);
		Tag::removeUnusedTags();
	}

    /**
     * Save just uploaded file
     * @param array $routes routes from module settings
     * @param bool $rename
     * @return bool
     */
    public function saveUploadedFile(array $routes, $rename = false)
    {
        $year = date('Y', time());
        $month = date('m', time());
        $structure = "$routes[baseUrl]/$routes[uploadPath]/$year/$month";
        $basePath = Yii::getAlias($routes['basePath']);
        $absolutePath = $basePath.$structure;
        // create actual directory structure "yyyy/mm"
        if (!file_exists($absolutePath)) {
            mkdir($absolutePath, 0777, true);
        }

        // get file instance
        $this->file = UploadedFile::getInstance($this, 'file');
        //if a file with the same name already exist append a number
        $counter = 0;
        do{
            if($counter==0){
                $filename = Inflector::slug($this->file->baseName).'.'. $this->file->extension;
            }else{
                //if we don't want to rename we finish the call here
                if($rename == false){
                    return false;
                }
                $filename = Inflector::slug($this->file->baseName). $counter.'.'. $this->file->extension;
            }
            $url = "$structure/$filename";
            $counter++;
        } while(self::findByUrl($url)); // checks for existing url in db

        // save original uploaded file
        $this->file->saveAs("$absolutePath/$filename");
        $this->filename = $filename;
        $this->type = $this->file->type;
        $this->size = $this->file->size;
        $this->url = $url;

        if ($this->isImage()) {
            self::compress("$absolutePath/$filename");
            $this->size = filesize("$absolutePath/$filename");
        }

        return $this->save();
    }

    /**
     * Create thumbs for this image
     *
     * @param array $routes see routes in module config
     * @param array $presets thumbs presets. See in module config
     * @return bool
     */
    public function createThumbs(array $routes, array $presets)
    {
        $thumbs = [];
        $basePath = $basePath = Yii::getAlias($routes['basePath']);
        $originalFile = pathinfo($this->url);
        $dirname = $originalFile['dirname'];
        $filename = $originalFile['filename'];
        $extension = $originalFile['extension'];

        Image::$driver = [Image::DRIVER_GD2, Image::DRIVER_GMAGICK, Image::DRIVER_IMAGICK];

        foreach ($presets as $alias => $preset) {
            $width = $preset['size'][0];
            $height = $preset['size'][1];
            $mode = (isset($preset['mode']) ? $preset['mode'] : ImageInterface::THUMBNAIL_OUTBOUND);

            $thumbUrl = "$dirname/" . $this->getThumbFilename($filename, $extension, $alias, $width, $height);

            Image::thumbnail("$basePath/{$this->url}", $width, $height, $mode)->save("$basePath/$thumbUrl");

            $thumbs[$alias] = $thumbUrl;
        }

        $this->thumbs = serialize($thumbs);
        $this->detachBehavior('timestamp');

        // create default thumbnail
        $this->createDefaultThumb($routes);

        return $this->save();
    }

    /**
     * Create default thumbnail
     *
     * @param array $routes see routes in module config
     */
    public function createDefaultThumb(array $routes)
    {
        $originalFile = pathinfo($this->url);
        $dirname = $originalFile['dirname'];
        $filename = $originalFile['filename'];
        $extension = $originalFile['extension'];

        Image::$driver = [Image::DRIVER_GD2, Image::DRIVER_GMAGICK, Image::DRIVER_IMAGICK];

        $size = FileManager::getDefaultThumbSize();
        $width = $size[0];
        $height = $size[1];
        $thumbUrl = "$dirname/" . $this->getThumbFilename($filename, $extension, FileManager::DEFAULT_THUMB_ALIAS, $width, $height);
        $basePath = Yii::getAlias($routes['basePath']);
        try{
            Image::thumbnail("$basePath/{$this->url}", $width, $height)->save("$basePath/$thumbUrl");
        } catch (Exception $ex) {

        }
    }

    /**
     * Add owner to mediafiles table
     *
     * @param int $owner_id owner id
     * @param string $owner owner identification name
     * @param string $owner_attribute owner identification attribute
     * @return bool save result
     */
    public function addOwner($owner_id, $owner, $owner_attribute)
    {
        $mediafiles = new Owners();
        $mediafiles->mediafile_id = $this->id;
        $mediafiles->owner = $owner;
        $mediafiles->owner_id = $owner_id;
        $mediafiles->owner_attribute = $owner_attribute;

        return $mediafiles->save();
    }

    /**
     * Remove this mediafile owner
     *
     * @param int $owner_id owner id
     * @param string $owner owner identification name
     * @param string $owner_attribute owner identification attribute
     * @return bool delete result
     */
    public static function removeOwner($owner_id, $owner, $owner_attribute)
    {
        $mediafiles = Owners::findOne([
            'owner_id' => $owner_id,
            'owner' => $owner,
            'owner_attribute' => $owner_attribute,
        ]);

        if ($mediafiles) {
            return $mediafiles->delete();
        }

        return false;
    }

    /**
     * @return bool if type of this media file is image, return true;
     */
    public function isImage()
    {
        return in_array($this->type, self::$imageFileTypes);
    }

    /**
     * @param $baseUrl
     * @return string default thumbnail for image
     */
    public function getDefaultThumbUrl($baseUrl = '')
    {
        if ($this->isImage()) {
            $size = FileManager::getDefaultThumbSize();
            $originalFile = pathinfo($this->url);
            $dirname = $originalFile['dirname'];
            $filename = $originalFile['filename'];
            $extension = $originalFile['extension'];
            $width = $size[0];
            $height = $size[1];
            return "$dirname/" . $this->getThumbFilename($filename, $extension, FileManager::DEFAULT_THUMB_ALIAS, $width, $height);
        }
        return "$baseUrl/images/file.png";
    }

    /**
     * @param $baseUrl
     * @return string default thumbnail for image
     */
    public function getDefaultUploadThumbUrl($baseUrl = '')
    {
        $size = FileManager::getDefaultThumbSize();
        $originalFile = pathinfo($this->url);
        $dirname = $originalFile['dirname'];
        $filename = $originalFile['filename'];
        $extension = $originalFile['extension'];
        $width = $size[0];
        $height = $size[1];

        return "$dirname/" . $this->getThumbFilename($filename, $extension, FileManager::DEFAULT_THUMB_ALIAS, $width, $height);
    }

	/**
	 * Returns thumbnail name
	 *
	 * @param $original
	 * @param $extension
	 * @param $alias
	 * @param $width
	 * @param $height
	 *
	 * @return string
	 */
	protected function getThumbFilename($original, $extension, $alias, $width, $height)
	{
		/** @var Module $module */
		$module = FileManager::getInstance();

		return strtr($module->thumbFilenameTemplate, [
			'{width}'     => $width,
			'{height}'    => $height,
			'{alias}'     => $alias,
			'{original}'  => $original,
			'{extension}' => $extension,
		]);
	}

    /**
     * @return array thumbnails
     */
    public function getThumbs()
    {
        return unserialize($this->thumbs) ?: [];
    }

    /**
     * @param string $alias thumb alias
     * @return string thumb url
     */
    public function getThumbUrl($alias)
    {
        $thumbs = $this->getThumbs();

        if ($alias === 'original') {
            return $this->url;
        }

        return !empty($thumbs[$alias]) ? $thumbs[$alias] : '';
    }

    /**
     * Thumbnail image html tag
     *
     * @param string $alias thumbnail alias
     * @param array $options html options
     * @return string Html image tag
     */
    public function getThumbImage($alias, $options=[])
    {
        $url = $this->getThumbUrl($alias);

        if (empty($url)) {
            return '';
        }

        if (empty($options['alt'])) {
            $options['alt'] = $this->alt;
        }

        return Html::img($url, $options);
    }

    /**
     * @param Module $module
     * @return array images list
     */
    public function getImagesList(FileManager $module)
    {
        $thumbs = $this->getThumbs();
        $list = [];
        $originalImageSize = $this->getOriginalImageSize($module->routes);
        $list[$this->url] = FileManager::t('main', 'Original') . ' ' . $originalImageSize;

        foreach ($thumbs as $alias => $url) {
            $preset = $module->thumbs[$alias];
            $list[$url] = $preset['name'] . ' ' . $preset['size'][0] . ' × ' . $preset['size'][1];
        }
        return $list;
    }

    /**
     * Delete thumbnails for current image
     * @param array $routes see routes in module config
     */
    public function deleteThumbs(array $routes)
    {
        $deleted = true;
        $basePath = Yii::getAlias($routes['basePath']);

        foreach ($this->getThumbs() as $thumbUrl) {
            if(is_file($basePath.$thumbUrl)) {
            	unlink($basePath.$thumbUrl);
            }else{
                $deleted = false;
                break;
            }
        }
        $defaultThumbPath = "$basePath/{$this->getDefaultThumbUrl()}";
        if(is_file($defaultThumbPath)) {
            unlink($defaultThumbPath);
        }else{
            $deleted = false;
        }
        return $deleted;
    }

    /**
     * Delete file
     * @param array $routes see routes in module config
     * @return bool
     */
    public function deleteFile(array $routes)
    {
        $basePath = Yii::getAlias($routes['basePath']);
        $filePath = "$basePath{$this->url}";
        return is_file($filePath) ? unlink($filePath) : false;
    }

    /**
     * @return int last changes timestamp
     */
    public function getLastChanges()
    {
        return !empty($this->updated_at) ? $this->updated_at : $this->created_at;
    }

    /**
     * This method wrap getimagesize() function
     * @param array $routes see routes in module config
     * @param string $delimiter delimiter between width and height
     * @return string image size like '1366x768'
     */
    public function getOriginalImageSize(array $routes, $delimiter = ' × ')
    {
        $imageSizes = $this->getOriginalImageSizes($routes);
        return "$imageSizes[0]$delimiter$imageSizes[1]";
    }

    /**
     * This method wrap getimagesize() function
     * @param array $routes see routes in module config
     * @return array
     */
    public function getOriginalImageSizes(array $routes)
    {
        $basePath = Yii::getAlias($routes['basePath']);
        return getimagesize("$basePath/{$this->url}");
    }

    /**
     * @return string file size
     */
    public function getFileSize()
    {
        Yii::$app->formatter->sizeFormatBase = 1000;
        return Yii::$app->formatter->asShortSize($this->size, 0);
    }

    /**
     * Find model by url
     *
     * @param $url
     * @return static
     */
    public static function findByUrl($url)
    {
        return self::findOne(['url' => $url]);
    }

    /**
     * Search models by file types
     * @param array $types file types
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findByTypes(array $types)
    {
        return self::find()->filterWhere(['in', 'type', $types])->all();
    }

    public static function loadOneByOwner($owner, $owner_id, $owner_attribute)
    {
        $owner = Owners::findOne([
            'owner' => $owner,
            'owner_id' => $owner_id,
            'owner_attribute' => $owner_attribute,
        ]);

        if ($owner) {
            return $owner->mediafile;
        }

        return false;
    }

    public static function getCurlType($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        if(!$type){
            $type = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_FILENAME);
        }else{
            $type = (!strripos($type, ';') ? $type : stristr($type, ';', true));
        }
        return $type;
    }

    private function curlRun($url){
        // Проверим HTTP в адресе ссылки
        if (!preg_match("/^https?:/i", $url) && filter_var($url, FILTER_VALIDATE_URL)) {
            return ['status'=>false, 'message'=>'Указана некоректная ссылка на удаленный файл'];
        }

        $ch = curl_init($url);

        // Укажем настройки для cURL
        curl_setopt_array($ch, [
            CURLOPT_TIMEOUT => 60,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_NOPROGRESS => 0,
            CURLOPT_BUFFERSIZE => 1024,
            CURLOPT_PROGRESSFUNCTION => function ($ch, $dwnldSize, $dwnld, $upldSize, $upld) {
                if ($dwnld > 1024 * 1024 * 10) {
                    return false;
                }
            },
        ]);

        $data  = curl_exec($ch);    // Скачаем данные в переменную
        $info  = curl_getinfo($ch); // Получим информацию об операции
        $error = curl_errno($ch);   // Запишем код последней ошибки

        curl_close($ch);
        return ['status'=>true, 'data'=>$data, 'error'=>$error, 'info'=>$info];
    }

    public function loadUrlImg($url){
        $curl_request = $this->curlRun($url);
        if($curl_request['status']){

            if ($curl_request['error'] === CURLE_OPERATION_TIMEDOUT)  return ['status'=>false, 'message'=>'Превышен лимит ожидания'];
            if ($curl_request['error'] === CURLE_ABORTED_BY_CALLBACK) return ['status'=>false, 'message'=>'Размер изображения превышает 10 MB'];
            if ($curl_request['info']['http_code'] !== 200)           return ['status'=>false, 'message'=>'Файл не доступен'];

            $fi = finfo_open(FILEINFO_MIME_TYPE);
            $mime = (string) finfo_buffer($fi, $curl_request['data']);
            finfo_close($fi);

            if (strpos($mime, 'image') === false) return ['status'=>false, 'message'=>'Объект не является изображением'];

            return ['status' => true, 'data'=>$curl_request['data']];
        }
        return $curl_request;
    }

    public function saveParsingFile($url, array $routes, $rename = false, $seo){
        $year = date('Y', time());
        $month = date('m', time());
        $structure = "$routes[baseUrl]/$routes[uploadPath]/$year/$month";
        $basePath = Yii::getAlias($routes['basePath']);
        $path = $basePath.$structure;

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $load_result = $this->loadUrlImg($url);
        if(!$load_result['status']){
            return $load_result;
        }
        $data = $load_result['data'];
        $image = getimagesizefromstring($data);
        $extension = image_type_to_extension($image[2]);
        $pathinfo_url = pathinfo($url);
        $filename = $pathinfo_url['filename'].$extension;
        $absolutePath = "$path/$filename";

        $fp = fopen($absolutePath, "w+");
        if (!file_put_contents($absolutePath, $data)) {
            return ['status'=>false, 'message'=>'При сохранении изображения на диск произошла ошибка'];
        }
        fclose($fp);

        if(file_exists($absolutePath)){
            $url = "$structure/$filename";
            $pathinfo = pathinfo($absolutePath);
            $this->filename = $filename;
            $this->type = 'image/'.$pathinfo['extension'];
            $this->size = filesize($absolutePath);
            $this->url = $url;
            $this->alt = $seo['alt'];
            $this->description = $seo['description'];
            $saved = $this->save();
            $message = (!$this->save()) ? 'Картинка не сохранилась в базу' : '';
            return ['status'=>$saved, 'message'=>$message];
        }
        return ['status'=>false, 'message'=>'Не удалось сохранить картинку в базе'];
    }

    public function deleteEssenceImg($media_id, $def_img){
        $def_media = self::find()->where(['filename' => $def_img])->one();
        if($def_media === null) {return false;}
        $prod_count = Product::find()->where(['media_id' => $media_id])->count('*');
        $prod_result = Product::updateAll(['media_id'=>$def_media->id], ['=', 'media_id', $media_id]);
        $cat_count = Category::find()->where(['media_id' => $media_id])->count('*');
        $cat_result = Category::updateAll(['media_id'=>$def_media->id], ['=', 'media_id', $media_id]);
        //$vprod_result = VProduct::updateAll(['media_id'=>$def_media->id], ['=', 'media_id', $media_id]);
        return ($prod_result === $prod_count) && ($cat_result === $cat_count);
    }

    /**
     * Compress image
     */
    public static function compress($filePath) {
        if (file_exists($filePath)) {
            $imagick = new Imagick();

            $rawImage = file_get_contents($filePath);

            $imagick->readImageBlob($rawImage);
            $imagick->stripImage();

            // Compress image
            $imagick->setImageCompressionQuality(85);

            $image_types = getimagesize($filePath);

            // Set image as based its own type
            if ($image_types[2] === IMAGETYPE_JPEG)
            {
                $imagick->setImageFormat('jpeg');

                $imagick->setSamplingFactors(array('2x2', '1x1', '1x1'));

                $profiles = $imagick->getImageProfiles("icc", true);

                $imagick->stripImage();

                if(!empty($profiles)) {
                    $imagick->profileImage('icc', $profiles['icc']);
                }

                $imagick->setInterlaceScheme(Imagick::INTERLACE_JPEG);
                $imagick->setColorspace(Imagick::COLORSPACE_SRGB);
            }
            else if ($image_types[2] === IMAGETYPE_PNG)
            {
                $imagick->setImageFormat('png');
            }
            else if ($image_types[2] === IMAGETYPE_GIF)
            {
                $imagick->setImageFormat('gif');
            }

            $imagick->writeImage($filePath);

            // Destroy image from memory
            $imagick->destroy();
        }
    }

    public static function bannerCropping($filePath, $new_w, $new_h, $suffix, $focus = 'center') {
        if (file_exists($filePath)) {
            $imagick = new Imagick();

            $rawImage = file_get_contents($filePath);

            $imagick->readImageBlob($rawImage);
            $imagick->stripImage();

            $w = $imagick->getImageWidth();
            $h = $imagick->getImageHeight();

            if ($w > $h) {
                $resize_w = $w * $new_h / $h;
                $resize_h = $new_h;
            }
            else {
                $resize_w = $new_w;
                $resize_h = $h * $new_w / $w;
            }
            $imagick->resizeImage($resize_w, $resize_h, Imagick::FILTER_LANCZOS, 0.9);

            switch ($focus) {
                case 'northwest':
                    $imagick->cropImage($new_w, $new_h, 0, 0);
                    break;

                case 'center':
                    $imagick->cropImage($new_w, $new_h, ($resize_w - $new_w) / 2, ($resize_h - $new_h) / 2);
                    break;

                case 'northeast':
                    $imagick->cropImage($new_w, $new_h, $resize_w - $new_w, 0);
                    break;

                case 'southwest':
                    $imagick->cropImage($new_w, $new_h, 0, $resize_h - $new_h);
                    break;

                case 'southeast':
                    $imagick->cropImage($new_w, $new_h, $resize_w - $new_w, $resize_h - $new_h);
                    break;
            }

            $path = explode('.', $filePath);
            $filename = $path[count($path)-2] . "-$suffix";
            $path[count($path)-2] = $filename;
            $filePath = implode('.', $path);

            $imagick->writeImage($filePath);

            // Destroy image from memory
            $imagick->destroy();
        }
    }

}
