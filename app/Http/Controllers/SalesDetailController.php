<?php

namespace App\Http\Controllers;

class SalesDetailController extends Controller
{
    public function index()
    {
        $saledetailId = 0;
        $columns = [
            'id',
            'comprobante',
            'n° de comprobante',
            'empleado',
            'cod venta',
            'fecha de venta',
            'cliente',
            'total',
            'creado en',
            'actualizado en',
            'opciones'
        ];
        $data = [];
        return view('admin.salesdetail', compact('saledetailId', 'columns', 'data'));
    }
}
