<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Asset;
use App\Models\User;
use App\Enums\AssetStatus;
use App\Models\Category;
use App\Services\AssetNumberService;
use App\Services\AssetService;

class AssetTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $mockAssetNumberService = \Mockery::mock(AssetNumberService::class);
        $mockAssetNumberService->shouldReceive('generate')->andReturn('100456');
        $this->app->instance(AssetNumberService::class, $mockAssetNumberService);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */

    protected function authenticateUser()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        return $user;
    }

    public function test_asset_index_shows_assets()
    {
        $this->authenticateUser();

        $assets = Asset::factory()->count(3)->create();

        $response = $this->get(route('assets.index'));

        $response->assertStatus(200);

        foreach($assets as $asset){
            $response->assertSee($asset->name);
        }
    }

    public function test_asset_create_form_is_accessible()
    {
        $this->authenticateUser();

        $response = $this->get(route('assets.create'));

        $response->assertStatus(200);
        $response->assertSee('資産登録');
    }

    public function test_asset_can_be_create()
    {
        $this->authenticateUser();
        $category = Category::factory()->create();

        $data = [
            'name' => 'パソコン',
            'price' => '100000',
            'acquisition_date' => '2025-01-01',
            'status' => AssetStatus::STORED->value,
            'category_id' => $category->id,
        ];

        $response = $this->post(route('assets.store'), $data);

        $response->assertRedirect(route('assets.index'));
        $this->assertDatabaseHas('assets', $data);
    }

    public function test_asset_edit_form_is_accessible()
    {
        $this->authenticateUser();

        $asset = Asset::factory()->create();

        $response = $this->get(route('assets.edit', $asset));

        $response->assertStatus(200);

        $response->assertSee($asset->name);
    }

    public function test_asset_can_be_updated()
    {
        $this->authenticateUser();

        $asset = Asset::factory()->create();

        $this->authenticateUser();
        $category = Category::factory()->create();

        $data = [
            'name' => 'パソコン',
            'price' => '100000',
            'acquisition_date' => '2025-01-01',
            'status' => AssetStatus::IN_USE->value,
            'category_id' => $category->id,
        ];

        $response = $this->put(route('assets.update', $asset), $data);
        $response->assertRedirect(route('assets.index'));
        $this->assertDatabaseHas('assets', $data);
    }

    public function test_asset_can_be_deleted()
    {
        $this->authenticateUser();

        $asset = Asset::factory()->create();

        $response = $this->delete(route('assets.destroy', $asset));

        $response->assertRedirect(route('assets.index'));
        $this->assertDatabaseMissing('assets', ['id' => $asset->id]);
    }

    public function in_use_assets_are_displayed_as_loaned()
    {
        $this->authenticateUser();

        $asset = Asset::factory()->create([
            'name' => 'パソコン',
            'status' => AssetStatus::IN_USE->value,
        ]);

        $response = $this->get(route('assets.index'));

        $response->assertStatus(200);
        $response->assertSee('パソコン');
        // 状態表示が貸出中なら、その文言がビューにあるか
        $response->assertSee('使用中');
    }

    /** @test */
    public function stored_assets_are_displayed_as_cannot_returned()
    {
        $this->authenticateUser();

        $asset = Asset::factory()->create([
            'name' => 'デスクトップPC',
            'status' => AssetStatus::STORED->value,
        ]);

        $response = $this->get(route('assets.index'));

        $response->assertStatus(200);
        $response->assertSee('デスクトップPC');
        // 返却不可の文言をビューに表示しているなら検証
        $response->assertSee('保管中');
    }
}
