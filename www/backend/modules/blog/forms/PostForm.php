<?php

namespace backend\modules\blog\forms;

use backend\modules\blog\entities\Post;
use backend\modules\blog\helpers\DateHelper;
use backend\modules\blog\repository\CategoryRepository;
use backend\modules\blog\repository\CountryReadRepository;
use backend\modules\blog\repository\PostRepository;
use backend\modules\blog\repository\TagRepository;
use backend\modules\blog\validators\AliasValidator;

class PostForm extends CompositeForm
{
    public $category_id;
    public $alias;
    public $media_id;
    public $status;
    public $published_at;

    public $_post;

    public function __construct(Post $post = null, $config = [])
    {
        if ($post) {
            $this->category_id = $post->category_id;
            $this->alias = $post->alias;
            $this->media_id = $post->media_id;
            $this->status = (int)$post->status;
            $this->published_at = DateHelper::convertUnixForPublished($post->published_at);

            $this->tags = new TagsForm($post);
            $this->meta = new MetaForm($post->seo);
            $this->_post = $post;
        } else {
            $this->tags = new TagsForm();
            $this->meta = new MetaForm();
        }
        parent::__construct($config);

    }

    public function rules(): array
    {
        return [
            [['category_id','alias','published_at'], 'required'],
            ['alias', 'string', 'max' => 255],
            [['category_id','media_id'], 'integer'],
            ['alias', AliasValidator::class],
            ['alias', 'unique', 'targetClass' => Post::class, 'filter' => $this->_post ? ['<>', 'id', $this->_post->id] : null],
            [['published_at','status'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название поста',
            'alias' => 'Алиас',
            'content' => 'Контент',
            'description' => 'Описание',
            'category_id' => 'Категория',
            'status' => 'Статус',
            'published_at' => 'Дата публикации'
        ];
    }

    /**
     * @return array
     */
    public function categoriesList(): array
    {
        $cat = new CategoryForm();
        return $cat->categoriesList(true);
    }
    /**
     * @return array
     */
    protected function internalForms(): array
    {
        return ['tags','meta'];
    }
}
