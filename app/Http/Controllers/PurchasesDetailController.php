<?php

namespace App\Http\Controllers;

class PurchasesDetailController extends Controller
{
    function __construct()
    {
        // Middleware para los permisos
        $this->middleware('permission:purchasesDetail-list|purchasesDetail-create|purchasesDetail-edit|purchasesDetail-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:purchasesDetail-create', ['only' => ['store']]);
        $this->middleware('permission:purchasesDetail-edit', ['only' => ['update']]);
        $this->middleware('permission:purchasesDetail-delete', ['only' => ['destroy']]);
    }

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
