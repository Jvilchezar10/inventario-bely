<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Models\SalesDetail;
use Carbon\Carbon;
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

    public function store(Request $request)
    {
        $combinedData = $request->json()->all();

        $tableData = $combinedData['tableData'];
        $formData = $combinedData['formData'];
        $total = $combinedData['total'];

        //throw new \Exception('Contenido de formData: ' . json_encode($tableData));

        $fsale = Carbon::createFromFormat('d/m/Y', $formData[4]['value'])->format('Y-m-d');

        $newSale = Sale::Create([
            'employee_id' => ($formData[1]['value']),
            'client_id' => ($formData[2]['value']),
            'sales_code' => ($formData[3]['value']),
            'sales_date' => $fsale,
            'proof_of_payment_id' => ($formData[5]['value']),
            'voucher_number' => ($formData[6]['value']),
            'total' => $total,
        ]);

        $saleId = $newSale->id;


        $keyMappings = [
            '6' => 'product_id',
            '2' => 'price',
            '3' => 'quantity',
            '4' => 'subtotal',
        ];

        $tableDataWithModifiedKeys = array_map(function ($row) use ($keyMappings, $saleId) {
            $modifiedRow = [];

            foreach ($row as $key => $value) {
                if (array_key_exists($key, $keyMappings) && array_key_exists($key, $row)) {
                    $modifiedRow[$keyMappings[$key]] = $value;
                }
            }

            $modifiedRow['sale_id'] = $saleId; // Agregar purchaseId a cada fila modificada

            return $modifiedRow;
        }, $tableData);


        foreach ($tableDataWithModifiedKeys as $key => $value) {
            $product = Product::find($value['product_id']);
            $product->stock -= $value['quantity'];
            $product->save();
        }

        SalesDetail::insert($tableDataWithModifiedKeys);

        return response()->json(['message' => 'Datos creados con éxito']);
    }
}
