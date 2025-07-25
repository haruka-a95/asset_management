<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\AssetNumberService;
use App\Models\Asset;
use App\Models\Category;

class AssetNumberServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AssetNumberService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AssetNumberService();
    }

    public function test_generate_returns_first_number_when_no_assets_exist()
    {
        $category = Category::factory()->create();
        $categoryId = $category->id;

        $assetNumber = $this->service->generate($categoryId);

        // prefix = categoryId * 10 + 0001
        $expectedPrefix = $categoryId * 10;
        $this->assertEquals($expectedPrefix . '0001', $assetNumber);
    }

    public function test_generate_increments_number_based_on_latest_asset()
    {
        $category = Category::factory()->create();
        $categoryId = $category->id;
        $user = \App\Models\User::factory()->create();
        // 既に資産あり
        Asset::factory()->create([
            'category_id' => $categoryId,
            'asset_number' => ($categoryId * 10) . '0005',  // e.g. 30 + 0005
            'user_id' => $user->id,
        ]);

        $assetNumber = $this->service->generate($categoryId);

        $this->assertEquals(($categoryId * 10) . '0006', $assetNumber);
    }


    public function test_generate_is_isolated_between_categories()
    {

        $category1 = Category::factory()->create(); // ID = 1など
        $category2 = Category::factory()->create(); // ID = 2など

         // カテゴリ1に資産番号100001
        Asset::factory()->create([
            'category_id' => $category1->id,
            'asset_number' => ($category1->id * 10) . '0001',
        ]);

        // カテゴリ2に資産番号200001
        Asset::factory()->create([
            'category_id' => $category2->id,
            'asset_number' => ($category2->id * 10) . '0001',
        ]);

        $service = new AssetNumberService();

        $number1 = $service->generate($category1->id);
        $number2 = $service->generate($category2->id);

        $this->assertEquals(($category1->id * 10) . '0002', $number1);
        $this->assertEquals(($category2->id * 10) . '0002', $number2);
    }
}
