<?php

namespace backend\modules\blog\services;

use backend\modules\blog\entities\Meta;
use backend\modules\blog\entities\Post;
use backend\modules\blog\entities\Tag;
use backend\modules\blog\forms\PostForm;
use backend\modules\blog\helpers\MessageHelper;
use backend\modules\blog\repository\MetaRepository;
use backend\modules\blog\repository\TagRepository;
use backend\modules\blog\repository\PostRepository;
use backend\modules\blog\repository\CategoryRepository;
use backend\modules\blog\repository\TagAssignmentsRepository;

class PostService
{
    private $post_repository;
    private $category_repository;
    private $tag_repository;
    private $tag_rel_repository;
    private $meta_repository;
    private $message;


    /**
     * PostService constructor.
     * @param PostRepository $posts
     * @param CategoryRepository $categories
     * @param TagRepository $tags
     * @param TagAssignmentsRepository $tags_rel
     * @param MetaRepository $meta
     */
    public function __construct(PostRepository $posts,
                                CategoryRepository $categories,
                                TagRepository $tags,
                                TagAssignmentsRepository $tags_rel,
                                MetaRepository $meta)
    {
        $this->post_repository = $posts;
        $this->category_repository = $categories;
        $this->tag_repository = $tags;
        $this->tag_rel_repository = $tags_rel;
        $this->meta_repository = $meta;

        $this->message = new MessageHelper();
    }

    /**https://agent.samo.ru/api/v1/_service/login?alias=Aosochenk&pwd=060315
     * @param PostForm $form
     * @return Post
     * @throws \Exception
     */
    public function create(PostForm $form) : Post
    {
        $category = $this->category_repository->get($form->category_id);

        $post = Post::create(
            $category->id,
            $form->country_id,
            \Yii::$app->user->identity->id??null,
            $form->title,
            $form->alias,
            $form->description,
            $form->content,
            $form->media_id,
            $form->status,
            $form->published_at);

        //обернуто в транзакцию
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $this->post_repository->save($post);

            $this->saveTags($form->tags->existing,$post->id);

            $this->meta_repository->save($meta = $this->createSeo($form->meta,$post->id,'post'));

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $post;
    }

    /**
     * @param $id
     * @param PostForm $form
     * @throws \Exception
     */
    public function edit($id, PostForm $form)
    {
        $post = $this->post_repository->get($id);
        $seo = $this->meta_repository->get($id,'post');
        $category = $this->category_repository->get($form->category_id);

        $post->edit(
            $category->id,
            $form->country_id,
            $form->title,
            $form->alias,
            $form->description,
            $form->content,
            $form->media_id,
            $form->status,
            $form->published_at);

        $seo->edit(
            $form->meta->h1,
            $form->meta->title,
            $form->meta->keywords,
            $form->meta->description,
            $form->meta->seo_text);

        $transaction = \Yii::$app->db->beginTransaction();
        try {
            $this->post_repository->save($post);

            $this->tag_rel_repository->delete($id);

            $this->saveTags($form->tags->existing,$post->id);

            $this->meta_repository->save($seo);

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return $post;
    }

    /**
     * @param $id
     */
    public function remove($id) : void
    {
        $post = $this->post_repository->get($id);
        $this->post_repository->delete($post);
        $this->tag_rel_repository->delete($id);
        $this->meta_repository->delete($id,'post');
    }

    /**
     * @param $id
     * @param $status
     */
    public function changeStatus($id, $status)
    {
        $post = $this->post_repository->get($id);

        if($post->is_main == 1){
            return $this->message->errorPost(1);
        }

        $post->status($status);
        $this->post_repository->save($post);

        return $this->message->successPost($status);
    }

    /**
     * @param $id
     * @param $status
     */
    public function inMain($id, $status)
    {
        $all_post_main = $this->post_repository->countOnMain();
        if($status == 1 && (int)$all_post_main >= 4){
            return $this->message->errorPost(2);
        }

        $post = $this->post_repository->get($id);

        if($post->status == 2){
            return $this->message->errorPost(4);
        }

        $post->mainPage($status,$this->getPosition($this->post_repository->existPosition(),$post));
        $this->post_repository->save($post);

        if(($count = $this->post_repository->countOnMain()) < 4){
            if($status == 1){
                $sub_str = 'Пост опубликован на ';
            } else {
                $sub_str = 'Пост снят с ';
            }
            return $this->message->successPost(
                $status,
                'str',
                $sub_str .'главной страницы.Чтобы посты выводились на главной,должно быть вабрано 4 поста,на данный момент выбрано ' .$count);
        }
        return $this->message->successPost($status,'post_main');
    }

    /**
     * @param $post_id
     * @param $position
     */
    public function setPosition($post_id, $position)
    {
        if($position == 0){
            return $this->message->errorPost(3);
        }

        $post = $this->post_repository->get($post_id);
        $last_position = $post->position;

        $post->setPosition($position);

        $anothe_post = $this->post_repository->getByPosition($position);
        if($last_position != 0 && $anothe_post){
            $anothe_post->setPosition($last_position);
            $this->post_repository->save($anothe_post);
        }

        $this->post_repository->save($post);

        return $this->message->successPost(1,'str','Изменены позиция поста на главной странице');
    }

    /**
     * @param $post_id
     * @return Post
     */
    public function addView($post_id):Post
    {
        $post = $this->post_repository->get($post_id);
        $post->addView();
        $this->post_repository->save($post);

        return $post;
    }

    /**
     * @param $country_id
     * @param $category_id
     * @param $post_id
     * @param $limit
     * @return array|bool|\yii\db\ActiveRecord[]
     */
    public function getSimilarPosts($country_id, $category_id, $post_id, $limit)
    {
        if($country_id && $posts = $this->post_repository->getSimilarPostsByCountry($country_id,$post_id,$limit)){
            if(count($posts) == $limit){
                return $posts;
            }

            $remain = $limit - count($posts);
            $remain_posts = $this->post_repository->getSimilarPostsByCategory($category_id,$post_id,$remain);
            $new_posts = array_merge($posts,$remain_posts);
            if(count($new_posts) == $limit){
                return $new_posts;
            }
            $new_remain = $limit - count($new_posts);
            $any_posts = $this->post_repository->getSimilarPostsByAnyCategory($category_id,$post_id,$new_remain);
            return array_merge($new_posts,$any_posts);
        } elseif ($category_id && $posts = $this->post_repository->getSimilarPostsByCategory($category_id,$post_id,$limit)){

            if(count($posts) == $limit){
                return $posts;
            }
            $remain = $limit - count($posts);
            $remain_posts = $this->post_repository->getSimilarPostsByAnyCategory($category_id,$post_id,$remain);
            return array_merge($posts,$remain_posts);

        } else {
            return $this->post_repository->getSimilarPostsByAnyCategory($category_id,$post_id,$limit);
        }
    }


    /**
     * @param $tags_existing
     * @param $post_id
     */
    private function saveTags($tags_existing, $post_id): void
    {
        $tags = [];
        foreach ($tags_existing as $tag_name) {
            $tag = $this->tag_repository->findByName($tag_name);
            if(!$tag){
                $tag = Tag::create($tag_name,Tag::generateAlias($tag_name));
                $this->tag_repository->save($tag);
            }
            $tags [] = $tag->id;
        }

        $this->tag_rel_repository->save($tags,$post_id);
    }

    /**
     * @param $form
     * @param $post_id
     * @return Meta
     */
    private function createSeo($form, $post_id,$alias)
    {
        return Meta::create(
            $post_id,
            $form->h1,
            $form->title,
            $form->keywords,
            $form->description,
            $form->seo_text,
            $alias);
    }

    private function getPosition($position,Post $post)
    {
        $list_position = $post->getArrayPosition();
        array_shift($list_position);
        $arr = array_diff($list_position,$position);
        return current($arr);
    }
}
