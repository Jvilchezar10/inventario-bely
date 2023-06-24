<?php

namespace App\Http\Controllers;

use App\Models\SalesDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SalesDetailController extends Controller
{
    public function index()
    {
        $saledetailId = 0;
        $columns = [
            'id',
            'sales_id',
            'quantity',
            'price',
            'subtotal',
            'subtotal',
            'actions'
        ];
        $data = [];
        return view('admin.saledetail', compact('saledetailId', 'columns', 'data'));
    }

    public function getData(Request $request)
    {
        try {
            if ($request->ajax()) {
                $salesdetail = SalesDetail::all();
                $data = $this->transformSalesDetail($salesdetail);
                return response()->json(['data' => $data], Response::HTTP_OK);
            } else {
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    private function transformSalesDetail($sales)
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
