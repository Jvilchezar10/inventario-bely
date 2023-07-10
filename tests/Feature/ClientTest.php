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
    public function test_cliente_screen_can_be_rendered(): void
    {
        $response = $this->get('/inventario/clientes', ['X-CSRF-TOKEN' => csrf_token()]);
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
            'id',
            'nombre completo',
            'DNI',
            'número de celular',
            'creado en',
            'actualizado en',
        ], ['X-CSRF-TOKEN' => csrf_token()]);

        $response->assertStatus(200);
        $response->assertJsonCount($clientes->count(), 'data');
    }

    public function test_client_Search()
    {
        $client = Client::factory()->count(5)->create();
        $term = $client->first()->full_name;

        $response = $this->postJson('/inventario/clientes/search', ['cli' => $term], ['X-CSRF-TOKEN' => csrf_token()]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'text',
            ],
        ]);
    }

    public function test_client_Store()
    {
        $clientData = [
            'i_name' => 'John Doe',
            'i_DNI' => '12345678',
            'i_phone' => '123456789',
        ];

        $response = $this->postJson('/inventario/clientes', $clientData);
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Proveedor creada con éxito']);
        // Agrega aquí más aserciones según sea necesario
    }

    // public function test_client_Update()
    // {
    //     $client =  Client::factory()->create();

    //     $updatedData = [
    //         'e_name' => 'Updated Name',
    //         'e_DNI' => '87654321',
    //         'e_phone' => '987654321',
    //     ];

    //     $response = $this->putJson('/clientes/' . $client->id, $updatedData);
    //     $response->assertStatus(200);
    //     $response->assertJson(['message' => 'Proveedor actualizado con éxito']);
    //     // Agrega aquí más aserciones según sea necesario
    // }

    // public function test_client_Destroy()
    // {
    //     $client =  Client::factory()->create();

    //     $response = $this->deleteJson('/clientes/' . $client->id);
    //     $response->assertStatus(200);
    //     $response->assertJson(['message' => 'Proveedor eliminado con éxito']);
    //     // Agrega aquí más aserciones según sea necesario
    // }
}
