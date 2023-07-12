<?php

namespace App\Http\Controllers;

class SalesDetailController extends Controller
{
    function __construct()
    {
        // Middleware para los permisos
        $this->middleware('permission:salesDetail-list|salesDetail-create|salesDetail-edit|salesDetail-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:salesDetail-create', ['only' => ['store']]);
        $this->middleware('permission:salesDetail-edit', ['only' => ['update']]);
        $this->middleware('permission:salesDetail-delete', ['only' => ['destroy']]);
    }

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
