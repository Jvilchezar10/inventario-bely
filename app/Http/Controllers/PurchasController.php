<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchas;
use App\Models\Product;
use App\Models\PurchasesDetail;
use Carbon\Carbon;
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

    public function store(Request $request)
    {
        $combinedData = $request->json()->all();

        // Acceder a los datos del formulario y del datatable individualmente
        $formData = $combinedData['formData'];
        $tableData = $combinedData['tableData'];
        $total = $combinedData['total'];


        //USAME PARA VER LOS ERRORES
        //throw new \Exception('Contenido de formData: ' . json_encode($formData));

        $fpurchas = Carbon::createFromFormat('d/m/Y', $formData[5]['value'])->format('Y-m-d');

        $newPurchase = Purchas::create([
            'employee_id' => ($formData[1]['value']),
            'origin' => ($formData[2]['value']),
            'provider_id' => ($formData[3]['value']),
            'purchase_code' => ($formData[4]['value']),
            'purchase_date' => $fpurchas,
            'proof_of_payments_id' => ($formData[6]['value']),
            'voucher_number' => ($formData[7]['value']),
            'total' => $total,
        ]);

        throw new \Exception('Contenido de formData: ' . json_encode([$formData[5]['value'],$fpurchas, $newPurchase->purchase_date]));

        // Obtener el ID de la compra recién creada
        $purchaseId = $newPurchase->id;

        // Asociar el ID de la compra a cada fila de datos de la tabla
        $keyMappings = [
            '7' => 'product_id',
            '2' => 'price',
            '3' => 'quantity',
            '4' => 'subtotal',
        ];

        $tableDataWithModifiedKeys = array_map(function ($row) use ($keyMappings, $purchaseId) {
            $modifiedRow = [];

            foreach ($row as $key => $value) {
                if (array_key_exists($key, $keyMappings) && array_key_exists($key, $row)) {
                    $modifiedRow[$keyMappings[$key]] = $value;
                }
            }

            $modifiedRow['purchase_id'] = $purchaseId; // Agregar purchaseId a cada fila modificada

            return $modifiedRow;
        }, $tableData);

        foreach ($tableDataWithModifiedKeys as $key => $value) {
            $product = Product::find($value['product_id']);
            $product->stock += $value['quantity'];
            $product->save();
        }

        //throw new \Exception('Contenido de formData: ' . json_encode($tableDataWithModifiedKeys));

        // Guardar los datos de la tabla en la tabla purchases_details
        PurchasesDetail::insert($tableDataWithModifiedKeys);

        return response()->json(['message' => 'Datos creados con éxito']);
    }

}
