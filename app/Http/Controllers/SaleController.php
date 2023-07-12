<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Models\SalesDetail;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Session;

class SaleController extends Controller
{
    function __construct()
    {
        // Middleware para los permisos
        $this->middleware('permission:sale-list|sale-create|sale-edit|sale-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:sale-create', ['only' => ['store']]);
        $this->middleware('permission:sale-edit', ['only' => ['update']]);
        $this->middleware('permission:sale-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $saleId = 0;

        return view('admin.sale', compact('saleId'));
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
                'comprobante' => optional($sale->proofofpayment)->name,
                'n° de comprobante' => $sale->voucher_number,
                'empleado' => optional($sale->employee)->name . " " . optional($sale->employee)->last_name,
                'cod venta' => $sale->sales_code,
                'fecha de venta' => $sale->sales_date,
                'cliente' => optional($sale->client)->full_name,
                'total' => $sale->total,
                'creado en' => optional($sale->created_at)->toDateTimeString(),
                'actualizado en' => optional($sale->updated_at)->toDateTimeString(),
            ];
        });
    }
    public function getDataById(Request $request, $id)
    {
        try {
            if ($request->ajax()) {
                $sale = Sale::where('id', $id)->get();
                $data = $this->transformPurchasById($sale);
                return response()->json(['data' => $data, 'data_loaded' => true], Response::HTTP_OK);
            } else {
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    private function transformPurchasById($sale)
    {
        return $sale[0]->salesDetails->map(function ($salesdetail) {
            return [
                'id' => $salesdetail->id,
                'productos' => optional($salesdetail->product)->desc, //DETAIL
                'cantidad' => optional($salesdetail)->quantity, //DETAIL
                'precio' => optional($salesdetail->product)->sale_price, //DETAIL
                'sub total' => $salesdetail->subtotal, //DETAIL
                'creado en' => optional($salesdetail->created_at)->toDateTimeString(),
                'actualizado en' => optional($salesdetail->updated_at)->toDateTimeString(),
            ];
        });
    }

    public function store(Request $request)
    {
        $combinedData = $request->json()->all();

        $tableData = $combinedData['tableData'];
        $formData = $combinedData['formData'];
        $total = $combinedData['total'];

        //throw new \Exception('Contenido de formData: ' . json_encode($formData));

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

            $modifiedRow['sale_id'] = $saleId;

            return $modifiedRow;
        }, $tableData);


        foreach ($tableDataWithModifiedKeys as $key => $value) {
            $product = Product::find($value['product_id']);
            $product->stock -= $value['quantity'];
            $product->save();

            if ($product->stock < 1) {
                // Guardar el mensaje en la sesión
                session()->flash('mensaje', 'El stock del producto ' . $product->nombre . ' es menor a 1.');
            }
        }

        SalesDetail::insert($tableDataWithModifiedKeys);

        return response()->json(['message' => 'Datos creados con éxito']);
    }
}
