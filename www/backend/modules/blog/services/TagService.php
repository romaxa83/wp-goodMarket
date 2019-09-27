<?php
namespace backend\modules\blog\services;

use backend\modules\blog\entities\Tag;
use backend\modules\blog\forms\TagForm;
use backend\modules\blog\repository\TagRepository;

class TagService
{
    private $tags;

    public function __construct(TagRepository $tags)
    {
        $this->tags = $tags;
    }

    //принимает форму тег и создает тег
    public function create(TagForm $form): Tag
    {
        $tag = Tag::create(
            $form->title,
            $form->alias
        );
        $this->tags->save($tag);
        return $tag;
    }
    //принимает форму тег и редактирует тег
    public function edit($id, TagForm $form): void
    {
        $tag = $this->tags->get($id);
        $tag->edit(
            $form->title,
            $form->alias
        );
        $this->tags->save($tag);
    }

    public function changeStatus($id,$status): void
    {
        $tag = $this->tags->get($id);
        $tag->status(
            $status
        );
        $this->tags->save($tag);
    }

    public function remove($id): void
    {
        $tag = $this->tags->get($id);
        $this->tags->remove($tag);
    }
}
