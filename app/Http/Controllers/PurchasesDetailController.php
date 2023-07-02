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
            'purchas_id', //DETAIL
            'comprobante',
            'n° de comprobante',
            'empleado',
            'cod compra',
            'fecha de compra',
            'proveedor',
            'origen',
            'productos', //DETAIL
            'cantidad', //DETAIL
            'precio', //DETAIL
            'subtotal', //DETAIL
            'total',
            'creado en',
            'actualizado en',
            'actions' //DETAIL
        ];
        $data = [];
        $purchases = Purchas::all();
        $purchasesdetail = PurchasesDetail::all();
        //dd($purchases[3]->purchasesDetails[0]);
        //dd($purchases[3]->purchasesDetails[0]);
        //dd($purchases[3]);
        //dd($purchasesdetail->purchase_id);
        foreach ($purchasesdetail as $detail) {
            dd($detail->purchase_id);
        }

        return view('admin.purchasesdetail', compact('purchasesdetailId', 'columns', 'data'));
    }

    // public function getData(Request $request)
    // {
    //     try {
    //         if ($request->ajax()) {
    //             $purchasesdetail = PurchasesDetail::all();
    //             $data = $this->transformPurchasesDetail($purchasesdetail);
    //             return response()->json(['data' => $data], Response::HTTP_OK);
    //         } else {
    //             throw new \Exception('Invalid request.');
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json(['message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
    //     }
    // }
}
