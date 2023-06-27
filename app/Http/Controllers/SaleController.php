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
        $combinedData = $request->json()->all();

        //$tableData = $combinedData['tableData'];
        $formData = $combinedData['formData'];
        //$total = $combinedData['total'];

         throw new \Exception('Contenido de formData: ' . json_encode($combinedData));

         return response()->json(['message' => 'Datos creados con éxito']);

        //$fsale = date("Y-m-d", strtotime($formData[4]['value']));

        // $newSales = Sale::create([

        // ]);

        // foreach ($tableDataWithModifiedKeys as $key => $value) {
        //     $product = Product::find($value['product_id']);
        //     $product->stock -= $value['quantity'];
        //     $product->save();
        // }


    }

}
