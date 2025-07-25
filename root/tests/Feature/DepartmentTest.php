<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Department;
use App\Models\User;

class DepartmentTest extends TestCase
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

    public function test_department_index_shows_departments()
    {
        $this->authenticateUser();

        $departments = Department::factory()->count(3)->create();

        $response = $this->get(route('departments.index'));

        $response->assertStatus(200);

        foreach($departments as $department){
            $response->assertSee($department->name);
        }
    }

    public function test_department_create_form_is_accessible()
    {
        $this->authenticateUser();

        $response = $this->get(route('departments.create'));

        $response->assertStatus(200);
        $response->assertSee('部署作成');
    }

    public function test_department_can_be_create()
    {
        $this->authenticateUser();

        $data = [
            'name' => '営業',
        ];

        $response = $this->post(route('departments.store'), $data);

        $response->assertRedirect(route('departments.index'));
        $this->assertDatabaseHas('departments', $data);
    }

    public function test_department_edit_form_is_accessible()
    {
        $this->authenticateUser();

        $department = Department::factory()->create();

        $response = $this->get(route('departments.edit', $department));

        $response->assertStatus(200);

        $response->assertSee($department->name);
    }

    public function test_department_can_be_updated()
    {
        $this->authenticateUser();

        $department = Department::factory()->create();

        $data = [
            'name' => '変更後名前',
        ];

        $response = $this->put(route('departments.update', $department), $data);
         $response->assertRedirect(route('departments.index'));
        $this->assertDatabaseHas('departments', $data);
    }

    public function test_department_can_be_deleted()
    {
        $this->authenticateUser();

        $department = Department::factory()->create();

        $response = $this->delete(route('departments.destroy', $department));

        $response->assertRedirect(route('departments.index'));
        $this->assertDatabaseMissing('departments', ['id' => $department->id]);
    }
}
