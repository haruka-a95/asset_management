<?php

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\AssetController;
use Tests\TestCase;
use Mockery;
use Illuminate\Http\Request;
use App\Services\AssetService;
use App\Services\AssetNumberService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreAssetRequest;
use App\Models\Asset;
use App\Http\Requests\UpdateAssetRequest;

use function PHPUnit\Framework\assertInfinite;
use function PHPUnit\Framework\assertInstanceOf;

class AssetControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_returns_view_with_data()
    {
        $mockAssetService = Mockery::mock(AssetService::class);
        $mockNumberService = Mockery::mock(AssetNumberService::class);

        $mockAssetService->shouldReceive('getAssets')->once()->andReturn(['asset1']);
        $mockAssetService->shouldReceive('getCategories')->once()->andReturn(['cat1']);
        $mockAssetService->shouldReceive('getUsers')->once()->andReturn(['user']);

        $request = Request::create('/assets', 'GET');

        $controller =  new AssetController($mockAssetService, $mockNumberService);
        $response = $controller->index($request);

        $this->assertInstanceOf(View::class, $response);
        $this->assertEquals('assets.index', $response->name());
    }

    public function test_store_creates_assets_and_redirects()
    {
        $mockAssetService = Mockery::mock(AssetService::class);
        $mockNumberService = Mockery::mock(AssetNumberService::class);

        $request = Mockery::mock(StoreAssetRequest::class);
        $request->shouldReceive('validated')->once()->andReturn([
            'name' => 'テスト',
            'category_id' => 5,
        ]);

        $mockNumberService->shouldReceive('generate')
            ->once()
            ->with(5)
            ->andReturn('500001');

        $mockAssetService->shouldReceive('createAsset')->once()->with([
            'name' => 'テスト',
            'category_id' => 5,
            'asset_number' => '500001',
        ]);

        $controller = new AssetController($mockAssetService, $mockNumberService);
        $response = $controller->store($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('assets.index'), $response->getTargetUrl());

        //sessionメッセージ確認
        $this->assertEquals('資産を登録しました', $response->getSession()->get('success'));
    }

    public function test_create_returns_create_view_with_dependencies()
    {
        $mockAssetService = Mockery::mock(AssetService::class);
        $mockNumberService = Mockery::mock(AssetNumberService::class);

        $mockAssetService->shouldReceive('getCategories')->once()->andReturn(['カテゴリ1']);
        $mockAssetService->shouldReceive('getUsers')->once()->andReturn(['user1']);
        $mockAssetService->shouldReceive('getDepartments')->once()->andReturn(['部署1']);

        $controller = new AssetController($mockAssetService, $mockNumberService);
        $response = $controller->create();

        $this->assertInstanceOf(View::class, $response);
        $this->assertEquals('assets.create', $response->name());
    }

    public function test_edit_returns_edit_view_with_asset_and_dependencies()
    {
        $mockAssetService = Mockery::mock(AssetService::class);
        $mockNumberService = Mockery::mock(AssetNumberService::class);

        $mockAssetService->shouldReceive('getCategories')->once()->andReturn(['カテゴリ1']);
        $mockAssetService->shouldReceive('getUsers')->once()->andReturn(['user1']);
        $mockAssetService->shouldReceive('getDepartments')->once()->andReturn(['部署1']);

        $asset = new Asset();

        $controller = new AssetController($mockAssetService, $mockNumberService);
        $response = $controller->edit($asset);

        $this->assertInstanceOf(View::class, $response);
        $this->assertEquals('assets.edit', $response->name());
    }

    public function test_update_asset_and_redirects()
    {
        $mockAssetService = Mockery::mock(AssetService::class);
        $mockNumberService = Mockery::mock(AssetNumberService::class);

        $request = Mockery::mock(UpdateAssetRequest::class);
        $request->shouldReceive('validated')->once()->andReturn([
            'name' => '変更後名',
            'category_id' => 2,
        ]);

        $asset = Mockery::mock(Asset::class);

        $mockAssetService->shouldReceive('updateAsset')->once()->with($asset, [
            'name' => '変更後名',
            'category_id' => 2,
        ]);

        $controller = new AssetController($mockAssetService, $mockNumberService);
        $response = $controller->update($request, $asset);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(route('assets.index'), $response->getTargetUrl());
    }

    public function test_destroy_deletes_asset_and_redirects()
    {
        $mockAssetService = Mockery::mock(AssetService::class);
        $mockNumberService = Mockery::mock(AssetNumberService::class);

        $asset = Mockery::mock(Asset::class);

        $mockAssetService->shouldReceive('deleteAsset')->once()->with($asset);
        $controller = new AssetController($mockAssetService, $mockNumberService);

        $response = $controller->destroy($asset);
        $this->assertEquals(route('assets.index'), $response->getTargetUrl());
    }
}