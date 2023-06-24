<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use Illuminate\Http\Response;

class SaleController extends Controller
{
    public function index()
    {
        $saleId = 0;
        $columns = [
            'id',
            'comprobante',
            'n° de comprobante',
            'empleado',
            'cod venta',
            'fecha de venta',
            'cliente',
            'total',
            'creado en',
            'actualizado en',
            'opciones'
        ];
        $data = [];
        return view('admin.sale', compact('saleId', 'columns', 'data'));
    }

    public function getData(Request $request)
    {
        try {
            if ($request->ajax()) {
                $sales = Sale::all();
                $data = $this->transformSales($sales);
                return response()->json(['data' => $data], Response::HTTP_OK);
            } else {
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    private function transformSales($sales)
    {
        return $sales->map(function ($sale) {
            return [
                'id' => $sale->id,
                'comprobante' => $sale->proofofpayment->name,
                'n° de comprobante' => $sale->voucher_number,
                'empleado' => optional($sale->employee)->name . ' ' . optional($sale->employee)->last_name,
                'cod compra' => $sale->sale_code,
                'fecha de compra' => $sale->sale_date,
                'proveedor' => $sale->provider->provider,
                'total' => $sale->total,
                'creado en' => optional($sale->created_at)->toDateTimeString(),
                'actualizado en' => optional($sale->updated_at)->toDateTimeString(),
            ];
        });
    }

    public function store(Request $request)
    {
        // $validatedData = $request->validate([
        //     'name' => 'required|max:255',
        //     'state' => 'required|in:vigente,descontinuado',
        // ]);

        // $sale = Sale::create($validatedData);

        // return response()->json(['message' => 'Categoría creada con éxito', 'sale' => $sale]);
    }

    public function update(Request $request, $id)
    {
        // $validatedData = $request->validate([
        //     'name' => 'required|max:255',
        //     'state' => 'required|in:vigente,descontinuado',
        // ]);

        // $sale = Sale::findOrFail($id);
        // $sale->update($validatedData);

        // return response()->json(['message' => 'Categoría actualizada con éxito', 'sale' => $sale]);
    }

    public function destroy($id)
    {
        // $sale = Sale::findOrFail($id);
        // $sale->delete();

        // return response()->json(['message' => 'Categoría eliminada con éxito']);
    }
}
