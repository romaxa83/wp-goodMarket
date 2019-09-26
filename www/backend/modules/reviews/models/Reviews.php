<?php

namespace backend\modules\reviews\models;

use backend\modules\product\models\ProductLang;
use common\models\User;
use Yii;
use \yii\helpers\ArrayHelper;
use backend\modules\product\models\Product;

/**
 * This is the model class for table "settings".
 *
 * @property int $id
 * @property string $name
 * @property string $body
 */
class Reviews extends \yii\db\ActiveRecord {

    public $title;
    public $first_name;
    public $last_name;
    public $stock_id;
    public $category_id;
    public $full_name;

    public static function tableName() {
        return 'reviews';
    }

    public function rules() {
        return [
            [['product_id', 'user_id', 'date', 'text', 'answer_id'], 'required'],
            [['product_id', 'user_id', 'answer_id', 'rating', 'publication'], 'number'],
            [['text'], 'string'],
            [['date'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
        ];
    }

    public static function getBackReviews() {
        $request = self::find()
                ->select(['concat(user.first_name," ",user.last_name) as full_name', 'product.*','reviews.*'])
                ->leftJoin('user', 'user.id=reviews.user_id')
                ->leftJoin('product', 'product.id=reviews.product_id')
                ->orderBy('(CASE WHEN reviews.answer_id = 0 THEN reviews.id  ELSE reviews.answer_id END) DESC, reviews.answer_id, reviews.id DESC')
                ->asArray()
                ->all();
        return $request;
    }

    public static function getReviews() {
        $request = self::find()
                ->select(['concat(user.first_name," ",user.last_name) as full_name', 'reviews.*'])
                ->leftJoin('user', 'user.id=reviews.user_id')
                ->leftJoin('product', 'product.id=reviews.product_id')
                ->joinWith('productLang')
                ->where(['answer_id' => 0])
                ->orderBy('reviews.date DESC')
                ->asArray()
                ->all();
        return $request;
    }

    public static function getAnswers() {
        $request = self::find()
                ->select(['concat(user.first_name," ",user.last_name) as full_name', 'reviews.*'])
                ->leftJoin('user', 'user.id=reviews.user_id')
                ->leftJoin('product', 'product.id=reviews.product_id')
                ->joinWith('productLang')
                ->where(['!=', 'answer_id', 0])
                ->asArray()
                ->all();
        return $request;
    }

    public static function getReviewsID() {
        $request = self::find()
                ->select('reviews.id')
                ->where('reviews.id > 0')
                ->leftJoin('user', 'reviews.user_id=user.id')
                ->leftJoin('product', 'reviews.product_id=product.id');
        return $request;
    }

    public static function getFrontReviews($condition) {
        $request = self::find()
                ->select(['user.username', 'user.first_name', 'user.last_name', 'concat(user.first_name," ",user.last_name) as full_name', 'reviews.*'])
                ->asArray()
                ->where($condition)
                ->andWhere('reviews.publication=1')
                ->leftJoin('user', 'reviews.user_id=user.id')
                ->orderBy('(CASE WHEN reviews.answer_id = 0 THEN reviews.id  ELSE reviews.answer_id END) DESC, reviews.answer_id, reviews.id DESC')
                ->all();
        return $request;
    }

    public function getFrontReviewOne() {
        $request = $this->find()
                ->select(['user.username', 'user.first_name', 'user.last_name', 'concat(user.first_name," ",user.last_name) as full_name', 'reviews.*'])
                ->asArray()
                ->where('reviews.id=' . $this->id . '')
                ->leftJoin('user', 'reviews.user_id=user.id')
                ->one();
        return $request;
    }

    public static function getCurrentPageReviews($one_page_count, $current_page) {
        $sub_query = self::find()
                ->select('id')
                ->where(['answer_id' => 0])
                ->asArray()
                ->limit($one_page_count)
                ->offset($one_page_count * ($current_page - 1))
                ->orderBy('(CASE WHEN reviews.answer_id = 0 THEN reviews.id  ELSE reviews.answer_id END) DESC, reviews.answer_id, reviews.id DESC')
                ->all();
        $sub_query = ArrayHelper::getColumn($sub_query, 'id');
        $request = self::find()
                ->where(['in', 'id', $sub_query])
                ->orWhere(['in', 'answer_id', $sub_query])
                ->count('*');
        return $request;
    }

    public static function getProdoctID($id) {
        $product_id = self::find()->select('product_id')->asArray()->where('id=' . $id)->one();
        return $product_id['product_id'];
    }

    public static function getProductsCount($product_id) {
        $count = self::find()->where('product_id=' . $product_id)->andWhere('answer_id=0')->count('*');
        return $count;
    }

    public static function getAverageRating($product_id) {
        $count = self::find()->where('product_id=' . $product_id)->andWhere('answer_id=0')->average('rating');
        return $count;
    }

    public static function getReview($id) {
        return self::find()->where(['id' => $id])->one();
    }

    public static function find() {
        return new ReviewsQuery(get_called_class());
    }

    public function getProduct() {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getProductLang() {
        return $this->hasMany(ProductLang::className(), ['product_id' => 'product_id']);
    }

}
