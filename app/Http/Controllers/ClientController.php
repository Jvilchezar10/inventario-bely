<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        $clientId = 0;
        $columns = [
            'id',
            'nombre completo',
            'DNI',
            'número de celular',
            'created_at',
            'updated_at',
            'actions'
        ];
        $data = [];
        return view('admin.client', compact('categoryId', 'columns', 'data'));
    }

}
