<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    use WithoutMiddleware;
    use SoftDeletes;

    protected $user;
    protected function setUp(): void
    {
        parent::setUp();

        // Crear un usuario y autenticarlo
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }
    /**
     * Prueba la función index().
     */
    public function test_categoria_screen_can_be_rendered(): void
    {
        $response = $this->get('/inventario/categorias');
        $response->assertStatus(200);

        $response->assertViewIs('admin.category');
        $response->assertViewHas(['categoryId', 'columns', 'data']);
    }

    public function test_category_creation()
    {
        // Usamos el factory para crear un cliente
        $category = Category::factory()->create();

        // Verificamos que el cliente se haya creado correctamente
        $this->assertInstanceOf(Category::class, $category);
        $this->assertDatabaseHas('categories', [
            'name' => $category->name,
            'state' => $category->state,
        ]);
    }

    public function test_category_Search()
    {
        $category = Category::factory()->count(5)->create();
        $term = $category->first()->name;

        $response = $this->postJson('/inventario/categorias/search', ['q' => $term]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'text',
            ],
        ]);
    }
}
