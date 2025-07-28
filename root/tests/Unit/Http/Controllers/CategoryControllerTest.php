<?php
namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\CategoryController;
use Tests\TestCase;
use Mockery;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Category;
use App\Services\CategoryService;
use App\Http\Requests\StoreCategoryRequest;

use function PHPUnit\Framework\assertInfinite;
use function PHPUnit\Framework\assertInstanceOf;

class CategoryControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_returns_view_with_data()
    {
        $mockCategoryService = Mockery::mock(CategoryService::class);

        $mockCategoryService->shouldReceive('getAllCategories')->once()->andReturn(['カテゴリ1']);

        $request = Request::create('/categories', 'GET');

        $controller =  new CategoryController($mockCategoryService);
        $response = $controller->index($request);

        $this->assertInstanceOf(View::class, $response);
        $this->assertEquals('categories.index', $response->name());
    }

    public function test_create_store_categories_and_redirect()
    {
        $mockCategoryService = Mockery::mock(CategoryService::class);

        $request = Mockery::mock(StoreCategoryRequest::class);
        $request->shouldReceive('validated')->once()->andReturn([
            'name' => 'テストカテゴリ',
        ]);

        $request->shouldReceive('only')->once()->with('name')->andReturn([
        'name' => 'テストカテゴリ',
        ]);

        $mockCategoryService->shouldReceive('createCategory')->once()->with([
            'name' => 'テストカテゴリ',
        ]);

        $controller = new CategoryController($mockCategoryService);

        $response = $controller->store($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('categories.index'), $response->getTargetUrl());

        //sessionメッセージ確認
        $this->assertEquals('カテゴリを作成しました', $response->getSession()->get('success'));
    }

    public function test_edit_returns_edit_view_with_category()
    {
        $mockCategoryService = Mockery::mock(CategoryService::class);

        $category = new Category(['name' => '編集カテゴリ']);

        $controller = new CategoryController($mockCategoryService);

        $response = $controller->edit($category);

        $this->assertInstanceOf(View::class, $response);//返すのはビューのみ
        $this->assertEquals('categories.edit', $response->name());

        $viewData = $response->getData();
        $this->assertArrayHasKey('category', $viewData);
        $this->assertSame($category, $viewData['category']);
    }

    public function test_update_category_and_redirect()
    {
        $mockCategoryService = Mockery::mock(CategoryService::class);

        $request = Mockery::mock(StoreCategoryRequest::class);
        $request->shouldReceive('validated')->once()->andReturn([
            'name' => '変更後名',
        ]);

        $request->shouldReceive('only')->once()->with('name')->andReturn([
        'name' => '変更後名',
        ]);

        $category = Mockery::mock(Category::class);

        $mockCategoryService->shouldReceive('updateCategory')->once()->with($category, [
            'name' => '変更後名',
        ]);

        $controller = new CategoryController($mockCategoryService);
        $response = $controller->update($request, $category);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('categories.index'), $response->getTargetUrl());
    }

    public function test_destroy_category_and_redirect()
    {
        $mockCategoryService = Mockery::mock(CategoryService::class);

        $category = Mockery::mock(Category::class);

        $mockCategoryService->shouldReceive('deleteCategory')->once()->with($category);
        $controller = new CategoryController($mockCategoryService);

        $response = $controller->destroy($category);
        $this->assertEquals(route('categories.index'), $response->getTargetUrl());
    }
}