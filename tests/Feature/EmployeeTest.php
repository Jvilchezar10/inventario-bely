<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class EmployeeTest extends TestCase
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
    public function test_employee_screen_can_be_rendered(): void
    {
        $response = $this->get('/inventario/empleados', ['X-CSRF-TOKEN' => csrf_token()]);
        $response->assertStatus(200);

        $response->assertViewIs('admin.employee');
        $response->assertViewHas(['employeeId', 'columns', 'data']);
    }

    public function test_employee_creation()
    {
        // Usamos el factory para crear un employeee
        $employee = Employee::factory()->create();

        // Verificamos que el employeee se haya creado correctamente
        $this->assertInstanceOf(Employee::class, $employee);
        $this->assertDatabaseHas('employees', [
            'cod_emp' => $employee->cod_emp,
            'name' => $employee->name,
            'last_name' => $employee->last_name,
            'phone' => $employee->phone,
            'email' => $employee->email,
            'state' => $employee->state,
        ]);
    }

    public function test_employee_Search()
    {
        $employee = Employee::factory()->count(5)->create();
        $term = $employee->first()->name;

        $response = $this->postJson('/inventario/empleados/search', ['emp' => $term]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => [
                'id',
                'text',
            ],
        ]);
    }
}
