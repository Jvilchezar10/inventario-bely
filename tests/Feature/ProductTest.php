<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ProductTest extends TestCase
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
    public function test_producto_screen_can_be_rendered(): void
    {
        $response = $this->get('/inventario/productos');
        $response->assertStatus(200);

        $response->assertViewIs('admin.product');
        $response->assertViewHas(['productId', 'columns','data']);
    }

    public function test_producto_search()
    {
        $employee = Product::factory()->count(5)->create();
        $term = $employee->first()->name;

        $response = $this->postJson('/inventario/productos/search', ['pro' => $term]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'text',
            ],
        ]);
    }

    public function test_producto_ventas_search()
    {
        $employee = Product::factory()->count(5)->create();
        $term = $employee->first()->name;

        $response = $this->postJson('/inventario/productos/searchsales', ['pro' => $term]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'text',
            ],
        ]);
    }
}
