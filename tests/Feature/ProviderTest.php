<?php

namespace Tests\Feature;

use App\Models\Provider;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ProviderTest extends TestCase
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
    public function test_proveedor_screen_can_be_rendered(): void
    {
        $response = $this->get('/inventario/proveedores');
        $response->assertStatus(200);

        $response->assertViewIs('admin.provider');
        $response->assertViewHas(['providerId', 'columns', 'data']);
    }

    public function test_provider_creation()
    {
        // Usamos el factory para crear un providere
        $provider = Provider::factory()->create();

        // Verificamos que el providere se haya creado correctamente
        $this->assertInstanceOf(Provider::class, $provider);
        $this->assertDatabaseHas('providers', [
            'provider' => $provider->provider,
            'DNI' => $provider->DNI,
            'RUC' => $provider->RUC,
            'phone' => $provider->phone,
            'contact' => $provider->contact,
            'contact_phone' => $provider->contact_phone
        ]);
    }

    public function test_provider_Search()
    {
        $provider = Provider::factory()->count(5)->create();
        $term = $provider->first()->provider;

        $response = $this->postJson('/inventario/proveedores/search', ['prov' => $term]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'text',
            ],
        ]);
    }
}
