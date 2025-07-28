<?php
namespace Tests\Unit\Http\Controllers\Api;

use Tests\TestCase;
use Mockery;
use App\Services\AssetNumberService;
use App\Http\Controllers\Api\AssetApiController;
use Illuminate\Http\JsonResponse;

class AssetApiControllerTest extends TestCase
{
    public function teardown():void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_getNextAssetNumber_returns_json_response()
    {
        //AssetNumbersServiceのモック作成
        $mockService = Mockery::mock(AssetNumberService::class);
        $mockService->shouldReceive('generate')
            ->once()
            ->with(5) //例としてカテゴリID 5
            ->andReturn('500001');

        //コントローラーにモックを注入
        $controller = new AssetApiController($mockService);

        //メソッド実行
        $response = $controller->getNextAssetNumber(5);

        //JsonResponseを確認
        $this->assertInstanceOf(JsonResponse::class, $response);

        //JsonResponseの内容を確認
        $data = $response->getData(true);
        $this->assertArrayHasKey('asset_number', $data);
        $this->assertEquals('500001', $data['asset_number']);
    }
}
