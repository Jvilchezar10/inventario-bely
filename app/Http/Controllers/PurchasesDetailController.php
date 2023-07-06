<?php

namespace App\Http\Controllers;

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
            'creado en',
            'actualizado en',
            'opciones'
        ];
        $data = [];

        return view('admin.purchasesdetail', compact('purchasesdetailId', 'columns', 'data'));
    }
}
