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
    function __construct()
    {
        // Middleware para los permisos
        $this->middleware('permission:purchas-list|purchas-create|purchas-edit|purchas-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:purchas-create', ['only' => ['store']]);
        $this->middleware('permission:purchas-edit', ['only' => ['update']]);
        $this->middleware('permission:purchas-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $purchasId = 0;

        return view('admin.purchas', compact('purchasId'));
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
                'comprobante' => optional($purchas->proofofpayment)->name,
                'n° de comprobante' => $purchas->voucher_number,
                'empleado' => optional($purchas->employee)->name . " " . optional($purchas->employee)->last_name,
                'cod compra' => $purchas->purchase_code,
                'fecha de compra' => $purchas->purchase_date,
                'proveedor' => optional($purchas->provider)->provider,
                'origen' => $purchas->origin,
                'total' => $purchas->total,
                'creado en' => optional($purchas->created_at)->toDateTimeString(),
                'actualizado en' => optional($purchas->updated_at)->toDateTimeString(),
            ];
        });
    }

    public function getDataById(Request $request, $id)
    {
        try {
            if ($request->ajax()) {
                $purchase = Purchas::where('id', $id)->get();
                $data = $this->transformPurchasById($purchase);
                return response()->json(['data' => $data, 'data_loaded' => true], Response::HTTP_OK);
            } else {
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }


    private function transformPurchasById($purchase)
    {
        return $purchase[0]->purchasesDetails->map(function ($purchasesdetail) {
            return [
                'id' => $purchasesdetail->id,
                'productos' => optional($purchasesdetail->product)->desc, //DETAIL
                'cantidad' => optional($purchasesdetail)->quantity, //DETAIL
                'precio' => optional($purchasesdetail->product)->purchase_price, //DETAIL
                'sub total' => $purchasesdetail->subtotal, //DETAIL
                'creado en' => optional($purchasesdetail->created_at)->toDateTimeString(),
                'actualizado en' => optional($purchasesdetail->updated_at)->toDateTimeString(),
            ];
        });
    }

    public function store(Request $request)
    {
        $combinedData = $request->json()->all();

        // Acceder a los datos del formulario y del datatable individualmente
        $formData = $combinedData['formData'];
        $tableData = $combinedData['tableData'];
        $total = $combinedData['total'];


        //USAME PARA VER LOS ERRORES
        //throw new \Exception('Contenido de formData: ' . json_encode($formData[6]));

        $fpurchas = Carbon::createFromFormat('d/m/Y', $formData[5]['value'])->format('Y-m-d');

        $newPurchase = Purchas::create([
            'employee_id' => ($formData[1]['value']),
            'origin' => ($formData[2]['value']),
            'provider_id' => ($formData[3]['value']),
            'purchase_code' => ($formData[4]['value']),
            'purchase_date' => $fpurchas,
            'proof_of_payment_id' => ($formData[6]['value']),
            'voucher_number' => ($formData[7]['value']),
            'total' => $total,
        ]);

        //throw new \Exception('Contenido de formData: ' . json_encode([$formData[5]['value'],$fpurchas, $newPurchase->purchase_date]));

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
