<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalesDetailController extends Controller
{
    public function index()
    {
        $saledetailId = 0;
        $columns = [
            'id',
            'quantity',
            'price',
            'subtotal',
            'total',
            'sales_id',
            'product_id',
            'created_at',
            'updated_at',
            'created_at',
            'updated_at',
            'actions'
        ];
        $data = [];
        return view('admin.saledetail', compact('saledetailId', 'columns', 'data'));
    }

    public function getData(Request $request)
    {
        try {
            if ($request->ajax()) {
                $proofofpayment = Sale::all();
                $data = $this->transformPOPayment($proofofpayments);
                return response()->json(['data' => $data], Response::HTTP_OK);
            } else {
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    private function transformPOPayment($sales)
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
}
