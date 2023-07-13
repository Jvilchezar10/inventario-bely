<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ProvidersImport;
use App\Models\Provider;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class ProviderController extends Controller
{
    function __construct()
    {
        // Middleware para los permisos
        $this->middleware('permission:provider-list|provider-create|provider-edit|provider-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:provider-create', ['only' => ['store']]);
        $this->middleware('permission:provider-edit', ['only' => ['update']]);
        $this->middleware('permission:provider-delete', ['only' => ['destroy']]);
    }
    public function index()
    {
        $providerId = 0;
        $columns = [
            'id',
            'proveedor',
            'DNI',
            'RUC',
            'número de celular',
            'contacto',
            'número de contacto',
            'creado en',
            'actualizado en',
            'opciones'
        ];
        $data = [];
        return view('admin.provider', compact('providerId', 'columns', 'data'));
    }

    public function getData(Request $request)
    {
        try {
            if ($request->ajax()) {
                if (!Auth::user()->can('provider-list')) {
                    abort(403, 'Permisos denegados');
                    throw new \Exception('No tienes permiso para listar proveedores.');
                }

                $providers = Provider::all();
                $data = $this->transformProviders($providers);
                return response()->json(['data' => $data], Response::HTTP_OK);
            } else {
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    private function transformProviders($providers)
    {
        return $providers->map(function ($provider) {
            return [
                'id' => $provider->id,
                'proveedor' => $provider->provider,
                'DNI' => $provider->DNI,
                'RUC' => $provider->RUC,
                'número de celular' => $provider->phone,
                'contacto' => $provider->contact,
                'número de contacto' => $provider->contact_phone,
                'creado en' => optional($provider->created_at)->toDateTimeString(),
                'actualizado en' => optional($provider->updated_at)->toDateTimeString(),
            ];
        });
    }

    public function search(Request $request)
    {
        $term = $request->input('prov');

        try {
            $providers =  Provider::where(function ($query) use ($term) {
                $query->where('provider', 'like', '%' . $term . '%');
            })->get();

            $data = [];

            foreach ($providers as $provider) {
                $data[] = [
                    'id' => $provider->id,
                    'text' => $provider->provider
                ];
            }

            return response()->json($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }


    public function store(Request $request)
    {
        if (!Auth::user()->can('provider-create')) {
            // Session::flash('error', 'Permisos denegados');
            abort(403, 'Permisos denegados');
        }

        $validatedData = $request->validate([
            'i_provider' => 'required',
            'i_DNI' => 'required',
            'i_RUC' => 'required',
            'i_phone' => 'required',
            'i_contact' => 'required',
            'i_contact_phone' => 'required',
        ]);
        // $provider = Provider::create($validatedData);
        $provider = Provider::create([
            'provider' => $validatedData['i_provider'],
            'DNI' => $validatedData['i_DNI'],
            'RUC' => $validatedData['i_RUC'],
            'phone' => $validatedData['i_phone'],
            'contact' => $validatedData['i_contact'],
            'contact_phone' => $validatedData['i_contact_phone'],
        ]);

        return response()->json(['message' => 'Proveedor creada con éxito', 'provider' => $provider]);
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->can('provider-edit')) {
            abort(403, 'Permisos denegados');
        }

        $validatedData = $request->validate([
            'e_provider' => '',
            'e_DNI' => '',
            'e_RUC' => '',
            'e_phone' => '',
            'e_contact' => '',
            'e_contact_phone' => '',
        ]);
        // $provider = Provider::create($validatedData);
        $provider = Provider::findOrFail($id);
        $provider->update([
            'provider' => $validatedData['e_provider'],
            'DNI' => $validatedData['e_DNI'],
            'RUC' => $validatedData['e_RUC'],
            'phone' => $validatedData['e_phone'],
            'contact' => $validatedData['e_contact'],
            'contact_phone' => $validatedData['e_contact_phone'],
        ]);


        return response()->json(['message' => 'Proveedor actualizado con éxito', 'provider' => $provider]);
    }

    public function destroy($id)
    {
        if (!Auth::user()->can('provider-delete')) {
            abort(403, 'Permisos denegados');
        }

        $provider = Provider::findOrFail($id);
        $provider->delete();

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
        $employeesImport = new providersImport();
        $imported = $employeesImport->import($filePath);

        if ($imported) {
            // Redireccionar o mostrar un mensaje de éxito
            return redirect()->back()->with('success', 'La importación del archivo Excel se realizó correctamente.');
        } else {
            // Redireccionar o mostrar un mensaje de error
            return redirect()->back()->with(['error', 'Ocurrió un error durante la importación del archivo Excel.']);
        }
    }
}
