<?php

namespace backend\modules\blog\services;

use backend\modules\blog\entities\Category;
use backend\modules\blog\forms\CategoryForm;
use backend\modules\blog\repository\CategoryReadRepository;
use backend\modules\blog\repository\CategoryRepository;
use backend\modules\blog\repository\PostRepository;

class CategoryService
{

    private $category_repository;
    private $post_repository;

    public function __construct(
        CategoryRepository $category_repository,
        PostRepository $post_repository
    )
    {
        $this->category_repository = $category_repository;
        $this->post_repository = $post_repository;
    }

    public function create($form) : Category
    {
        $parent = $this->category_repository->get($form->parent_id);
        $category = Category::create(
            $form->alias
        );

        $category->appendTo($parent);
        $this->category_repository->save($category);

        return $category;
    }

    public function edit($id,CategoryForm $form) : void
    {
        $category = $this->category_repository->get($id);
        $this->assertIsNotRoot($category);
        $category->edit(
            $form->alias
        );
        if(isset($form->parent_id) && !empty($form->parent_id)){
            $parent = $this->category_repository->get($form->parent_id);
            $category->appendTo($parent);
        }
        $this->category_repository->save($category);
    }

    public function changeStatus($id,$status): void
    {
        $category = $this->category_repository->get($id);
        $category->status(
            $status
        );
        $this->category_repository->save($category);
    }

    public function remove($id): void
    {
        $category = $this->category_repository->get($id);
        $this->assertIsNotRoot($category);
        if($this->post_repository->existsByCategory($category->id)){
            throw new \DomainException('Не могу удалить категорию с постами');
        }
        $this->category_repository->remove($category);
    }

    public function moveUp($id): void
    {
        $category = $this->category_repository->get($id);
        $this->assertIsNotRoot($category);
        if ($prev = $category->prev) {
            $category->insertBefore($prev);
        }
        $this->category_repository->save($category);
    }

    public function moveDown($id): void
    {
        $category = $this->category_repository->get($id);
        $this->assertIsNotRoot($category);
        if ($next = $category->next) {
            $category->insertAfter($next);
        }
        $this->category_repository->save($category);
    }

    private function assertIsNotRoot(Category $category) : void
    {
        if($category->isRoot()){
            throw new \DomainException('Невозможно редактировать категорию root.');
        }
    }

}
