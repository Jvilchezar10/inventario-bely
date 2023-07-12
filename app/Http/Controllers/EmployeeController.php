<?php

namespace App\Http\Controllers;

use App\Imports\EmployeesImport;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Http\Response;

class EmployeeController extends Controller
{
    function __construct()
    {
        // Middleware para los permisos
        $this->middleware('permission:employee-list|employee-create|employee-edit|employee-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:employee-create', ['only' => ['store']]);
        $this->middleware('permission:employee-edit', ['only' => ['update']]);
        $this->middleware('permission:employee-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $employeeId = 0;
        $columns = [
            'id',
            'cod emp',
            'nombre',
            'apellido',
            'número de celular',
            'correo',
            'estado',
            'creado en',
            'actualizado en',
            'opciones'
        ];
        $data = [];
        return view('admin.employee', compact('employeeId', 'columns', 'data'));
    }

    public function getData(Request $request)
    {
        try {
            if ($request->ajax()) {
                $employees = Employee::all();
                $data = $this->transformEmployees($employees);
                return response()->json(['data' => $data], Response::HTTP_OK);
            } else {
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    private function transformEmployees($employees)
    {
        return $employees->map(function ($employee) {
            return [
                'id' => $employee->id,
                'cod emp' => $employee->cod_emp,
                'nombre' => $employee->name,
                'apellido' => $employee->last_name,
                'número de celular' => $employee->phone,
                'correo' => $employee->email,
                'estado' => $employee->state,
                'creado en' => optional($employee->created_at)->toDateTimeString(),
                'actualizado en' => optional($employee->updated_at)->toDateTimeString(),
            ];
        });
    }
    public function search(Request $request)
    {
        $term = $request->input('emp');
        try {
            $employees = Employee::where('name', 'like', '%' . $term . '%')->get();
            // Lógica para buscar los datos y devolver los resultados en formato JSON

            $data = [];
            foreach ($employees as $employee) {
                $data[] = [
                    'id' => $employee->id,
                    'text' => $employee->name . ' ' . $employee->last_name
                ];
            }
            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'i_cod_emp' => 'required',
            'i_name' => 'required',
            'i_last_name' => 'required',
            'i_phone' => 'required',
            'i_correo' => 'required|email',
            'i_state' => 'required|in:vigente,retirado',
        ]);

        $employee = Employee::create([
            'cod_emp' => $validatedData['i_cod_emp'],
            'name' => $validatedData['i_name'],
            'last_name' => $validatedData['i_last_name'],
            'phone' => $validatedData['i_phone'],
            'email' => $validatedData['i_correo'],
            'state' => $validatedData['i_state'],
        ]);

        return response()->json(['message' => 'Empleado creada con éxito', 'employee' => $employee]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'e_cod_emp' => 'required',
            'e_name' => 'required',
            'e_last_name' => 'required',
            'e_phone' => 'required',
            'e_correo' => 'required|email',
            'e_state' => 'required|in:vigente,retirado',
        ]);

        $employee = Employee::findOrFail($id);
        $employee->update([
            'cod_emp' => $validatedData['e_cod_emp'],
            'name' => $validatedData['e_name'],
            'last_name' => $validatedData['e_last_name'],
            'phone' => $validatedData['e_phone'],
            'email' => $validatedData['e_correo'],
            'state' => $validatedData['e_state'],
        ]);

        return response()->json(['message' => 'Empleado actualizado con éxito', 'employee' => $employee]);
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();

        return response()->json(['message' => 'Categoría eliminada con éxito']);
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls',
        ]);

        // Obtener la ruta temporal del archivo cargado
        $filePath = $request->file('excel_file')->getRealPath();

        // Instanciar la clase de importación y llamar al método import
        $employeesImport = new EmployeesImport();
        $imported = $employeesImport->import($filePath);


        if ($imported[1]) {
            // Redireccionar o mostrar un mensaje de éxito
            return redirect()->back()->with('success', json_decode($imported[0]));
        } else {
            //dd($imported[0]);
            // Redireccionar o mostrar un mensaje de error
            return redirect()->back()->with('errors', $imported[0]);
        }
    }
}
