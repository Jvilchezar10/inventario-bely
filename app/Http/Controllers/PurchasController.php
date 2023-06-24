<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchas;
use Illuminate\Http\Response;

class PurchasController extends Controller
{
    public function index()
    {
        $purchasId = 0;
        $columns = [
            'id',
            'comprobante',
            'n° de comprobante',
            'empleado',
            'cod compra',
            'fecha de compra',
            'proveedor',
            'total',
            'creado en',
            'actualizado en',
            'opciones'
        ];
        $data = [];
        return view('admin.purchas', compact('purchasId', 'columns', 'data'));
    }

    public function getData(Request $request)
    {
        try {
            if ($request->ajax()) {
                $purchases = Purchas::all();
                $data = $this->transformPurchas($purchases);
                return response()->json(['data' => $data], Response::HTTP_OK);
            } else {
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    private function transformPurchas($purchases)
    {
        return $purchases->map(function ($purchas) {
            return [
                'id' => $purchas->id,
                'comprobante' => $purchas->proofofpayment->name,
                'n° de comprobante' => $purchas->voucher_number,
                'empleado' => optional($purchas->employee)->name . ' ' . optional($purchas->employee)->last_name,
                'cod compra' => $purchas->purchase_code,
                'fecha de compra' => $purchas->purchase_date,
                'proveedor' => $purchas->provider->provider,
                'total' => $purchas->total,
                'creado en' => optional($purchas->created_at)->toDateTimeString(),
                'actualizado en' => optional($purchas->updated_at)->toDateTimeString(),
            ];
        });
    }

    public function store(Request $request)
    {
        // $validatedData = $request->validate([
        //     'name' => 'required|max:255',
        //     'state' => 'required|in:vigente,descontinuado',
        // ]);

        // $purchas = Purchas::create($validatedData);

        // return response()->json(['message' => 'Categoría creada con éxito', 'purchas' => $purchas]);
    }

    public function update(Request $request, $id)
    {
        // $validatedData = $request->validate([
        //     'name' => 'required|max:255',
        //     'state' => 'required|in:vigente,descontinuado',
        // ]);

        // $purchas = Purchas::findOrFail($id);
        // $purchas->update($validatedData);

        // return response()->json(['message' => 'Categoría actualizada con éxito', 'purchas' => $purchas]);
    }

    public function destroy($id)
    {
        // $purchas = Purchas::findOrFail($id);
        // $purchas->delete();

        // return response()->json(['message' => 'Categoría eliminada con éxito']);
    }
}
