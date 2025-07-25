<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Models\Category;
use Illuminate\Support\Arr;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    //一覧取得
    public function getAllCategories()
    {
        return $this->categoryRepository->all();
    }

    //作成
    public function createCategory(array $data)
    {
        return $this->categoryRepository->create($data);
    }

    //更新
    public function updateCategory(Category $category, array $data):bool
    {
        return $this->categoryRepository->update($category, $data);
    }

    //削除
    public function deleteCategory(Category $category):bool
    {
        return $this->categoryRepository->delete($category);
    }
}