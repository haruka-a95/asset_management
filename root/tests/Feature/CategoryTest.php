<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;
use App\Models\User;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_category_index_shows_categories()
    {
        $this->authenticateUser();

        $categories = Category::factory()->count(3)->create();

        $response = $this->get(route('categories.index'));

        $response->assertStatus(200);

        foreach($categories as $category){
            $response->assertSee($category->name);
        }
    }

    public function test_category_create_form_is_accessible()
    {
        $this->authenticateUser();

        $response = $this->get(route('categories.create'));

        $response->assertStatus(200);
        $response->assertSee('カテゴリ作成');
    }

    public function test_category_can_be_create()
    {
        $this->authenticateUser();

        $data = [
            'name' => '新カテゴリ',
        ];

        $response = $this->post(route('categories.store'), $data);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', $data);
    }

    public function test_category_edit_form_is_accessible()
    {
        $this->authenticateUser();

        $category = Category::factory()->create();

        $response = $this->get(route('categories.edit', $category));

        $response->assertStatus(200);

        $response->assertSee($category->name);
    }

    public function test_category_can_be_updated()
    {
        $this->authenticateUser();

        $category = Category::factory()->create();

        $data = [
            'name' => '変更後カテゴリ',
        ];

        $response = $this->put(route('categories.update', $category), $data);
         $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', $data);
    }

    public function test_category_can_be_deleted()
    {
        $this->authenticateUser();

        $category = Category::factory()->create();

        $response = $this->delete(route('categories.destroy', $category));

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
