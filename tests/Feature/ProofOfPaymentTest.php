<?php

namespace Tests\Feature;

use App\Models\ProofOfPayment;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ProofOfPaymentTest extends TestCase
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
    public function test_comprobante_screen_can_be_rendered(): void
    {
        $response = $this->get('/inventario/comprobantes');
        $response->assertStatus(200);

        $response->assertViewIs('admin.proofofpayment');
        $response->assertViewHas(['proofofpaymentId', 'columns', 'data']);
    }

    public function test_proofofpayment_creation()
    {
        // Usamos el factory para crear un cliente
        $proofofpayment = ProofOfPayment::factory()->create();

        // Verificamos que el cliente se haya creado correctamente
        $this->assertInstanceOf(ProofOfPayment::class, $proofofpayment);
        $this->assertDatabaseHas('proof_of_payments', [
            'name' => $proofofpayment->name,
            'state' => $proofofpayment->state,
        ]);
    }

    public function test_proofofpayment_Search()
    {
        $proofofpayment = ProofOfPayment::factory()->count(5)->create();
        $term = $proofofpayment->first()->name;

        $response = $this->postJson('/inventario/comprobantes/search', ['q' => $term]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'text',
            ],
        ]);
    }
}
