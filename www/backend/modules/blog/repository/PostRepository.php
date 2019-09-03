<?php

namespace backend\modules\blog\repository;

use backend\modules\blog\entities\Post;
use yii\helpers\ArrayHelper;

class PostRepository
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;
    /**
     * @var TagRepository
     */
    private $tagRepository;

    public function __construct(
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
    }

    /**
     * @param $id
     * @return Post
     */
    public function get($id): Post
    {
        if (!$post = Post::findOne($id)) {
            throw new \DomainException('Post is not found.');
        }
        return $post;
    }

    public function getWithSeo($id)
    {
        if (!$post = Post::find()->where(['id' => $id])->with(['seo' => function($query){
            $query->andWhere(['alias' => 'post']);
        }])->one()) {
            throw new \DomainException('Post is not found.');
        }

        return $post;
    }

    /**
     * получение всех постов по алиасу категории
     * @param $alias
     * @return array|bool|\yii\db\ActiveRecord[]
     */
    public function getAllByCategoryAlias($alias,$limit=null)
    {
        if($category = $this->categoryRepository->findByAlias($alias)){
            if($posts = $this->getAllByCategory($category->id,$limit)){
                return $posts;
            }
            return false;
        }
        return false;
    }

    public function getAllByCategoryAliasCount($alias)
    {
        if($category = $this->categoryRepository->findByAlias($alias)){
            if($posts = $this->getAllByCategoryCount($category->id)){
                return $posts;
            }
            return false;
        }
        return false;
    }


    /**
     * получение всех постов и обзоров на отели по алиасу тега
     * @param $alias
     * @return array|bool|\yii\db\ActiveRecord[]
     */
    public function getAllByTagAlias($alias,$limit = null)
    {
        if($tag = $this->tagRepository->findByAlias($alias)){

            $all_posts = [];

            if($tag->posts && !($tag->hotelReview)) $all_posts = $tag->posts;
            if(!($tag->posts) && $tag->hotelReview) $all_posts = $tag->hotelReview;
            if($tag->posts && $tag->hotelReview) $all_posts = array_merge($tag->posts,$tag->hotelReview);

            if($limit){
                $limit_posts = [];
                for ($i = 0;$i < $limit;$i++) {
                    if(isset($all_posts[$i])){
                        $limit_posts[$i] = $all_posts[$i];
                    }
                }
                return $limit_posts;
            }
            return $all_posts;
        }
        return false;
    }

    public function getAllByTagAliasCount($alias)
    {
        if($tag = $this->tagRepository->findByAlias($alias)){
            if($posts = $tag->getPosts()->count()){
                return $posts;
            }
            return false;
        }
        return false;
    }

    /**
     * @return array|bool|\yii\db\ActiveRecord[]
     */
    public function getAll($limit=null)
    {
        if(!$posts = Post::find()->where(['status' => Post::ACTIVE])->orderBy(['published_at' => SORT_DESC])->limit($limit)->all()){
            return false;
        }
        return $posts;
    }

    public function getAllCount()
    {
        return Post::find()->where(['status' => Post::ACTIVE])->count();
    }

    public function getAllByCategory($category_id,$limit = null)
    {
        if(!$posts = Post::find()->where(['status' => Post::ACTIVE])->andWhere(['category_id' => $category_id])->orderBy(['published_at' => SORT_DESC])->limit($limit)->all()){
            return false;
        }
        return $posts;
    }
    public function getAllByCategoryCount($category_id)
    {
        if(!$posts = Post::find()->where(['status' => Post::ACTIVE])->andWhere(['category_id' => $category_id])->count()){
            return false;
        }
        return $posts;
    }

    /**
     * @param $id
     * @return bool
     */
    public function existsByCategory($id) : bool
    {
        return Post::find()->andWhere(['category_id' => $id])->exists();
    }

    /**
     * @param Post $post
     */
    public function save(Post $post): void
    {
        if (!$post->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @param Post $post
     */
    public function delete(Post $post): void
    {
        if (!$post->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

    /**
     * @param $position
     * @return Post|null
     */
    public function getByPosition($position): ?Post
    {
        if (!$post = Post::find()->where(['position' => $position])->one()) {
            return null;
        }
        return $post;
    }

    public function getMain()
    {
        $posts = Post::find()->where(['is_main' => Post::ON_MAIN_PAGE])->orderBy(['position' => SORT_ASC])->all();
        if(count($posts) == 4){
            return $posts;
        }
        return false;
    }

    /**
     * @return int|string
     */
    public function countOnMain()
    {
        return Post::find()->where(['is_main' => Post::ON_MAIN_PAGE])->count();
    }

    public function existPosition()
    {
        return array_column(Post::find()->select('position')->where(['is_main' => Post::ON_MAIN_PAGE])->asArray()->all(),'position') ;
    }

    /**
     * возвращает посты к которым привязаны страны
     * @return array|bool|\yii\db\ActiveRecord[]
     */
    public function getPostsAttachCountry()
    {
        if($posts = Post::find()->where(['not in','country_id','null'])->andWhere(['status' => Post::ACTIVE])->all()){
            return $posts;
        }
        return false;
    }

    /**
     * @param $id
     * @return array|bool|\yii\db\ActiveRecord[]
     */
    public function getAllByCountry($slug,$limit = null)
    {
        if($posts = Post::find()->where(['country_id' => $slug])->andWhere(['status' => Post::ACTIVE])->orderBy(['published_at' => SORT_DESC])->limit($limit)->all()){
            return $posts;
        }
        return false;
    }

    public function getAllByCountryCount($id)
    {
        if($posts = Post::find()->where(['country_id' => $id])->andWhere(['status' => Post::ACTIVE])->count()){
            return $posts;
        }
        return false;
    }

    /**
     * @param $country_id
     * @param $post_id
     * @param $limit
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getSimilarPostsByCountry($country_id, $post_id, $limit)
    {
        return Post::find()->where(['country_id' => $country_id])
            ->andWhere(['status' => Post::ACTIVE])->andWhere(['not in','id',$post_id])
            ->orderBy(['views' => SORT_DESC,'published_at' => SORT_DESC])->limit($limit)->all();

    }

    /**
     * @param $category_id
     * @param $post_id
     * @param $limit
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getSimilarPostsByCategory($category_id, $post_id, $limit)
    {
        return Post::find()->where(['category_id' => $category_id])
            ->andWhere(['status' => Post::ACTIVE])->andWhere(['not in','id',$post_id])
            ->orderBy(['views' => SORT_DESC,'published_at' => SORT_DESC])->limit($limit)->all();

    }

    /**
     * @param $category_id
     * @param $post_id
     * @param $limit
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getSimilarPostsByAnyCategory($category_id,$post_id, $limit)
    {
        return Post::find()
            ->andWhere(['status' => Post::ACTIVE])->andWhere(['not in','id',$post_id])
            ->andWhere(['not in','category_id',$category_id])
            ->orderBy(['views' => SORT_DESC,'published_at' => SORT_DESC])->limit($limit)->all();

    }

    public function getPostIdBySlug($slug)
    {
        $post = Post::find()->select('id')->where(['alias' => $slug])->one();
        if ($post === NULL)
            throw new \yii\web\HttpException(404);
        return $post->id;
    }
}
