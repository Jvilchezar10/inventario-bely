<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;


class ClientTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware, SoftDeletes;

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
    public function test_client_screen_can_be_rendered(): void
    {
        $response = $this->get('/inventario/clientes');
        $response->assertStatus(200);

        $response->assertViewIs('admin.client');
        $response->assertViewHas(['clientId', 'columns', 'data']);
    }
    /**
     * Test the getData method of ClientController.
     *
     * @return void
     */
    public function test_client_get_data()
    {
        $clientes = Client::factory()->count(5)->create();

        $response = $this->postJson(route('clients.data'), [
            ['name' => 'id',],
            ['name' => 'nombre completo',],
            ['name' => 'DNI',],
            ['name' => 'número de celular',],
            ['name' => 'creado en',],
            ['name' => 'actualizado en',],
        ]);

        $response->assertStatus(200);
        $response->assertJsonCount($clientes->count(), 'data');
    }

    public function test_client_creation()
    {
        // Usamos el factory para crear un cliente
        $client = Client::factory()->create();

        // Verificamos que el cliente se haya creado correctamente
        $this->assertInstanceOf(Client::class, $client);
        $this->assertDatabaseHas('clients', [
            'full_name' => $client->full_name,
            'DNI' => $client->DNI,
            'phone' => $client->phone,
        ]);
    }

    public function test_client_Search()
    {
        $client = Client::factory()->count(5)->create();
        $term = $client->first()->full_name;

        $response = $this->postJson('/inventario/clientes/search', ['cli' => $term]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'text',
            ],
        ]);
    }
}
