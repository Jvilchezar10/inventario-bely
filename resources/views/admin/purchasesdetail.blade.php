@extends('adminlte::page')

@section('content_header')
    <h1 class="m-0 text-dark">Compras</h1>
@stop
{{-- @section('css')
    <link rel="stylesheet" href="{{ asset('css/Product.css') }}">
@stop --}}

@section('content')
    <div id="error-message" class="alert alert-danger" style="display: none;"></div>
    @if ($message = Session::get('success'))
        <div id="success-message" class="alert alert-success">
            {{ $message }}
        </div>
    @endif

    <x-adminlte-card title="Lista de compras" theme="pink" icon="fas fa-tshirt" class="elevation-3" maximizable>
        <x-datatable :columns=$columns :data=$data id="productsTable" />
    </x-adminlte-card>

@endsection

@section('js')
    <script>
        $(function() {

            var table = $('#productsTable').DataTable({
                columns: [{
                        data: 'id',
                        name: 'id',
                        visible: false,
                    },
                    {
                        data: 'id',
                        name: 'id',
                    },
                    {
                        data: 'creado en',
                        name: 'creado en',
                        visible: false,
                    },
                    {
                        data: 'actualizado en',
                        name: 'actualizado en',
                        visible: false,
                    },
                    {
                        data: 'id',
                        name: 'opciones',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return generateButtons(row);
                        },
                    }
                ],
                // Configuración adicional del DataTable
                timeout: 5000, // Tiempo de espera en milisegundos (5 segundos)
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, 'Todo']
                ],
                pageLength: 10,
                searching: true,
                ordering: true,
                order: [
                    [0, 'asc']
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.11.2/i18n/es_es.json'
                },
                dom: "<'row'<'col-auto'l><'col'B><'col-auto'f>>" +
                    "<'row'<'col-sm-12't>>" +
                    "<'row'<'col-sm-12 col-md-5'><'col-sm-12 col-md-7'p>>",

                buttons: [{
                        extend: 'copy',
                        text: '<i class="fas fa-sticky-note text-yellow"></i>', //Copiar
                        className: 'btn btn-sm btn-default',
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-csv text-blue"></i>', //CSV
                        className: 'btn btn-sm btn-default',
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel text-green"></i>', //Excel
                        className: 'btn btn-sm btn-default',
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf text-red"></i>', //PDF
                        className: 'btn btn-sm btn-default',
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i>',
                        className: 'btn btn-sm btn-default',
                    },
                    {
                        text: '<i class="fa fa-plus"></i> Registrar Producto',
                        className: 'btn btn-sm btn-primary bg-danger mx-1',
                        action: () => openRegisterModal(),
                    },
                    {
                        text: '<i class="fa fa-plus"></i> Cargar Productos',
                        className: 'btn btn-sm btn-primary bg-danger mx-1',
                        action: () => openRegisterExcelModal(),
                    },
                ],
                responsive: true,
                paging: true,
                stateDuration: -1,
                info: true,
                select: {
                    style: 'multi',
                    selector: 'td:first-child'
                },
            });
        });
    </script>
@endsection
