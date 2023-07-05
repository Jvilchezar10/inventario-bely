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
            'comprobante',
            'n° de comprobante',
            'empleado',
            'cod compra',
            'fecha de compra',
            'proveedor',
            'origen',
            'total',
            'creado en', //DETAIL
            'actualizado en',  //DETAIL
            'actions' //DETAIL
        ];
        $data = [];
        // $purchases = Purchas::all();
        $purchasesdetail = PurchasesDetail::with('purchas')->get();
        foreach ($purchasesdetail as $detail) {
        }

        return view('admin.purchasesdetail', compact('purchasesdetailId', 'columns', 'data'));
    }

    public function getData(Request $request)
    {
        try {
            if ($request->ajax()) {
                $purchasesdetails = PurchasesDetail::all();
                $data = $this->transformPurchas($purchasesdetails);
                return response()->json(['data' => $data], Response::HTTP_OK);
            } else {
                throw new \Exception('Invalid request.');
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }


    private function transformPurchas($purchasesdetails)
    {
        return $purchasesdetails->map(function ($purchasesdetail) {
            return [
                'id' => optional($purchasesdetail)->id,
                'productos' => optional($purchasesdetail->product)->desc, //DETAIL
                'cantidad' => optional($purchasesdetail)->quantity, //DETAIL
                'precio' => optional($purchasesdetail->product)->purchasesdetaile_price, //DETAIL
                'subtotal' => optional($purchasesdetail)->subtotal, //DETAIL
                'creado en' => optional($purchasesdetail->created_at)->toDateTimeString(),
                'actualizado en' => optional($purchasesdetail->updated_at)->toDateTimeString(),
            ];
        });
    }
}
