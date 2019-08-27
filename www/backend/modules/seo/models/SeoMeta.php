<?php

namespace backend\modules\seo\models;

use yii\web\NotFoundHttpException;
/**
 * Это класс модели для таблицы  "seo_meta".
 *
 * @property int $id
 * @property string $h1
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property string $seo_text
 * @property int $parent_id
 * @property int $language
 */
/**
 * @SWG\Definition(required={"id", "h1", "title", "keywords", "description", "seo_text",})
 * @SWG\Property(property="id", type="integer(11)")
 * @SWG\Property(property="h1", type="text")
 * @SWG\Property(property="title", type="text")
 * @SWG\Property(property="keywords", type="text")
 * @SWG\Property(property="description", type="text")
 * @SWG\Property(property="seo_text", type="text")
 */
class SeoMeta extends \yii\db\ActiveRecord
{
    /**
     * @var const Переменый cценария для валидатора
     */
    const SYSTEM_EDIT = 'system';
    const OTHER_EDIT = 'other';
    /**
     * @var string Переменый Cео(английский) для работы мультиязычности
     */
    public $h1_en;
    public $title_en;
    public $keywords_en;
    public $description_en;
    public $seo_text_en;
    /**
     * @var string Переменый Cео(английский) для работы мультиязычности
     */
    public $h1_ua;
    public $title_ua;
    public $keywords_ua;
    public $description_ua;
    public $seo_text_ua;
    /**
     * @see https://www.yiiframework.com/doc/api/2.0/yii-db-activerecord#tableName()-detail
     * return string 
     */
    public static function tableName()
    {
        return 'seo_meta';
    }

    /**
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-model#rules()-detail
     * @return array
     */
    public function rules()
    {
        return [
            [['h1', 'title', 'keywords', 'description', 'seo_text'], 'required', 'on' => self::SYSTEM_EDIT],
            [['h1', 'title', 'keywords', 'description'], 'string', 'on' => self::OTHER_EDIT],
        ];
    }

    /**
     * @see https://www.yiiframework.com/doc/api/2.0/yii-base-model#attributeLabels()-detail
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'h1' => 'H1',
            'title' => 'Title',
            'keywords' => 'Keywords',
            'description' => 'Description',
            'seo_text' => 'Seo Text',
            'alias' => 'Alias',
        ];
    }
    /**
    * Создает запись модели Cео.
    * @param array $arr
    * @return array where key is language , value is id row [language => id]
    * @throws \yii\web\NotFoundHttpException
    */
    public function CreateSeo($arr){
        $arr_id = [];
        foreach ($arr as $key => $value){
            if(substr($key, -2) != 'en' && substr($key, -2) != 'ua')$languages['ru'][] = $value;
            if(substr($key, -2) == 'en')$languages['en'][] = $value;
            if(substr($key, -2) == 'ua')$languages['ua'][] = $value;
        }
        foreach ($languages as $key => $language){
            if (!isset($id) && $key == 'ru'){
                $model = new SeoMeta();
                $model->h1 = $language[0];
                $model->title = $language[1];
                $model->keywords = $language[2];
                $model->description = $language[3];
                $model->seo_text = $language[4];
                $model->language = $key;
                $model->parent_id = NULL;
                $model->save(false);
                $id = $model->id;
                $arr_id[$key]['seo'] = $id;
            }else{
                $model = new SeoMeta();
                $model->h1 = $language[0];
                $model->title = $language[1];
                $model->keywords = $language[2];
                $model->description = $language[3];
                $model->seo_text = $language[4];
                $model->language = $key;
                $model->parent_id = $id;
                $model->save(false);
                $arr_id[$key]['seo'] = $model->id;
            }
        }
        return $arr_id;
    }
    /**
    * Обновляет запись модели Cео.
    * @param array $arr
    * @param integer $id
    * @throws \yii\web\NotFoundHttpException
    */
    public function SaveSeo($arr,$id){
        if($id != null){
            $groups = SeoMeta::find()->where(['id' => $id])->orWhere(['parent_id' => $id])->all();
            $group = \backend\controllers\BaseController::language($groups, ['h1','title','keywords','description','seo_text']);   
        }else{
            throw new NotFoundHttpException('parent_id был передан некорректно');
        }
        foreach ($arr as $key => $value){
            if(substr($key, -2) != 'en' && substr($key, -2) != 'ua')$languages['ru'][] = $value;
            if(substr($key, -2) == 'en')$languages['en'][] = $value;
            if(substr($key, -2) == 'ua')$languages['ua'][] = $value;
        }
        foreach ($groups as $item) {
            if ($item->language == 'ru') {
                $item->h1 = $languages['ru'][0];
                $item->title = $languages['ru'][1];
                $item->keywords = $languages['ru'][2];
                $item->description = $languages['ru'][3];
                $item->seo_text = $languages['ru'][4];
                $item->save(false);
            } else {
                foreach ($languages as $key => $value) {
                    if ($item->language == $key) {
                        $item->h1 = $value[0];
                        $item->title = $value[1];
                        $item->keywords = $value[2];
                        $item->description = $value[3];
                        $item->seo_text = $value[4];
                        $item->save(false);
                    }
                }
            }
        }
    }
    /**
    * удаляет записи Cео, на основе parent_id.
    * @param integer $parent_id
    * @throws \yii\web\NotFoundHttpException
    */
    public static function deleteSeo($parent_id){
        $models = SeoMeta::find()->where(['id'=>$parent_id])->orWhere(['parent_id'=>$parent_id])->all();
        if($models && $parent_id){
            foreach ($models as $model){
                $model->delete();
            }
        }else{
            throw new NotFoundHttpException('parent_id был передан некорректно');
        }
    }
    
    public static function SeoGenerator($value,$product){
        $data = ''; 
        if(!empty($value) && !empty($product)){
            $difference = isset($product['price_old']) ? $product['price_old'] - $product['trade_price'] : 0;
            $salePrice = ((float)$difference > 0) ? $difference : $product['trade_price'];
            $arrayTag = [
                0 => '{n}',
                1 => '{p}',
                2 => '{sp}',
                3 => '{c}',
                4 => '{m}',
                5 => '{sku}'
            ];
            $arrayValue = [
                0 => $product["product_name"],
                1 => $product["trade_price"],
                2 => $salePrice,
                3 => $product["category_name"],
                4 => $product["manufacturer"],
                5 => $product["vendor_code"]
            ];
            $data = str_replace($arrayTag, $arrayValue, $value);
        }
        return $data;
    }
}
