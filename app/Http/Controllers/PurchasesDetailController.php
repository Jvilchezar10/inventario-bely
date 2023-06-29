<?php

namespace App\Http\Controllers;

use App\Models\Purchas;
use App\Models\PurchasesDetail;
//use Illuminate\Contracts\Support\ValidatedData;
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
                $data = $this->transformPurchasesDetail($purchasesdetail);
                return response()->json(['data' => $data], Response::HTTP_OK);
            } else {
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    private function transformPurchasesDetail($purchasesdetail)
    {

        return $purchasesdetail->map(function ($detail) {

            return [
                'id' => $detail->id,
                'purchas_id' => $detail->purchas->id,
                'producto' => $detail->product->desc,
                'cantidad' => $detail->quantity,
                'precio' => $detail->price,
                'subtotal' => $detail->subtotal
            ];
        });
    }

    public function update(Request $request, $id)
    {
         
    }

    public function destroy($id)
    {

    }
}
