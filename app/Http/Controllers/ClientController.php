<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Client;

class ClientController extends Controller
{
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
                'DNI'=> $client->DNI,
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
            'i_DNI' => 'required',
            'i_phone' => 'required',
        ]);
        // $client = Client::create($validatedData);
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
            'e_DNI' => 'required',
            'e_phone' => 'required',
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
}


