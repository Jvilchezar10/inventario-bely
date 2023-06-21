<?php

namespace App\Http\Controllers;

use App\Models\PurchasesDetail;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PurchasesDetailController extends Controller
{
    public function index()
    {
        $purchasesdetailId = 0;
        $columns = [
            'id',
            'purchas_id',
            'producto',
            'cantidad',
            'precio',
            'subtotal',
            'actions'
        ];
        $data = [];
        return view('admin.purchasesdetail', compact('purchasesdetailId', 'columns', 'data'));
    }

    public function getData(Request $request)
    {
        try {
            if ($request->ajax()) {
                $purchasesdetail = PurchasesDetail::all();
                $data = $this->transformPurchas($purchasesdetail);
                return response()->json(['data' => $data], Response::HTTP_OK);
            } else {
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
