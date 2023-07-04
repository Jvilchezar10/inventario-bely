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
            // 'purchas_id', //DETAIL
            'comprobante',
            'n° de comprobante',
            'empleado',
            'cod compra',
            'fecha de compra',
            'proveedor',
            'origen',
            // 'productos', //DETAIL
            // 'cantidad', //DETAIL
            // 'precio', //DETAIL
            // 'subtotal', //DETAIL
            'total',
            // 'creado en', //DETAIL
            // 'actualizado en',  //DETAIL
            'actions' //DETAIL
        ];
        $data = [];
        // $purchases = Purchas::all();
        $purchasesdetail = PurchasesDetail::with('purchas')->get();
        foreach ($purchasesdetail as $detail) {
        }

        return view('admin.purchasesdetail', compact('purchasesdetailId', 'columns', 'data'));
    }
}
