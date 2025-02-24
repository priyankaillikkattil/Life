<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class CategoryTest extends TestCase {
    use RefreshDatabase;

    public function test_admin_can_create_category() {
        $admin = User::factory()->create(['user_type' => 'admin']);
        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/categories', ['name' => 'New Category']);
        $response->assertStatus(201);
        $this->assertDatabaseHas('categories', ['name' => 'New Category']);
    }


    public function test_authenticated_user_can_view_categories() {
        $user = User::factory()->create(); 
        Sanctum::actingAs($user); 
    
        Category::factory()->count(3)->create();
    
        $response = $this->getJson('/api/categories');
        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }
    
    
}
