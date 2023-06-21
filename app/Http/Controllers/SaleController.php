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
            'sales_date',
            'client_id',
            'proof_payment_id',
            'employee_id',
            'created_at',
            'updated_at',
            'actions'
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
                'name' => $sale->name,
                'state' => $sale->state,
                'created_at' => optional($sale->created_at)->toDateTimeString(),
                'updated_at' => optional($sale->updated_at)->toDateTimeString(),
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
