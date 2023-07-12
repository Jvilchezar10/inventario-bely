<?php

namespace Tests\Feature;

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
        $response = $this->get('/inventario/categorias', ['X-CSRF-TOKEN' => csrf_token()]);
        $response->assertStatus(200);

        $response->assertViewIs('admin.category');
        $response->assertViewHas(['categoryId', 'columns', 'data']);
    }
}
