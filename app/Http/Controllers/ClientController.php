<?php

namespace App\Http\Controllers;

use App\Imports\ClientsImport;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Client;

class ClientController extends Controller
{
    function __construct()
    {
        // Middleware para los permisos
        $this->middleware('permission:client-list|client-create|client-edit|client-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:client-create', ['only' => ['store']]);
        $this->middleware('permission:client-edit', ['only' => ['update']]);
        $this->middleware('permission:client-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $clientId = 0;
        $columns = [
            'id',
            'nombre completo',
            'DNI',
            'número de celular',
            'creado en',
            'actualizado en',
            'opciones'
        ];
        $data = [];
        return view('admin.client', compact('clientId', 'columns', 'data'));
    }

    public function getData(Request $request)
    {
        try {
            if ($request->ajax()) {
                $clients = Client::all();
                $data = $this->transformClients($clients);
                return response()->json(['data' => $data], Response::HTTP_OK);
            } else {
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    private function transformClients($clients)
    {
        return $clients->map(function ($client) {
            return [
                'id' => $client->id,
                'nombre completo' => $client->full_name,
                'DNI' => $client->DNI,
                'número de celular' => $client->phone,
                'creado en' => optional($client->created_at)->toDateTimeString(),
                'actualizado en' => optional($client->updated_at)->toDateTimeString(),
            ];
        });
    }

    public function search(Request $request)
    {
        $term = $request->input('cli');

        try {
            $clients =  Client::where(function ($query) use ($term) {
                $query->where('full_name', 'like', '%' . $term . '%');
            })->get();

            $data = [];

            foreach ($clients as $client) {
                $data[] = [
                    'id' => $client->id,
                    'text' => $client->full_name
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
            'i_name' => 'required',
            'i_DNI' => 'required|numeric|max:8',
            'i_phone' => 'required|numeric|max:9',
        ]);

        $client = Client::create([
            'full_name' => $validatedData['i_name'],
            'DNI' => $validatedData['i_DNI'],
            'phone' => $validatedData['i_phone'],
        ]);

        return response()->json(['message' => 'Proveedor creada con éxito', 'client' => $client]);
    }

    public function update(Request $request, $id)
    {

        $validatedData = $request->validate([
            'e_name' => 'required',
            'e_DNI' => 'required|numeric|max:8',
            'e_phone' => 'required|numeric|max:9',
        ]);
        // $client = Client::create($validatedData);
        $client = Client::findOrFail($id);
        $client->update([
            'full_name' => $validatedData['e_name'],
            'DNI' => $validatedData['e_DNI'],
            'phone' => $validatedData['e_phone'],
        ]);

        return response()->json(['message' => 'Proveedor actualizado con éxito', 'client' => $client]);
    }

    public function destroy($id)
    {

        $client = Client::findOrFail($id);
        $client->delete();

        return response()->json(['message' => 'Proveedor eliminado con éxito']);
    }

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls',
        ]);

        // Obtener la ruta temporal del archivo cargado
        $filePath = $request->file('excel_file')->getRealPath();

        // Instanciar la clase de importación y llamar al método import
        $clientsImport = new ClientsImport();
        $imported = $clientsImport->import($filePath);

        if ($imported) {
            // Redireccionar o mostrar un mensaje de éxito
            return redirect()->back()->with('success', 'La importación del archivo Excel se realizó correctamente.');
        } else {
            // Redireccionar o mostrar un mensaje de error
            return redirect()->back()->with(['error', 'Ocurrió un error durante la importación del archivo Excel.']);
        }
    }
}
