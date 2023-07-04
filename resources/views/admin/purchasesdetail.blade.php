@extends('adminlte::page')

@section('content_header')
    <h1 class="m-0 text-dark">Compras</h1>
@stop

@section('content')
    <div id="error-message" class="alert alert-danger" style="display: none;"></div>
    @if ($message = Session::get('success'))
        <div id="success-message" class="alert alert-success">
            {{ $message }}
        </div>
    @endif

    @php
        $columnsProducts = ['purchas_id', 'productos', 'cantidad', 'precio', 'subtotal'];
        $dataProducts = [];
    @endphp

    <x-adminlte-card title="Lista de compras" theme="pink" icon="fas fa-tshirt" class="elevation-3" maximizable>
        <x-datatable :columns=$columns :data=$data id="PurchasTable" />
    </x-adminlte-card>
    <x-adminlte-card title="Detalle de compra" theme="pink" icon="fas fa-tshirt" class="elevation-3" maximizable>
        <div class="card-body">
            <x-datatable :columns="$columnsProducts" :data="$dataProducts" id="PurchasDetailTable" />
        </div>
    </x-adminlte-card>

@endsection

@section('js')
    <script>
        var purchases_DataRoute = '{{ route('purchases.data') }}';
        var csrfToken = '{{ csrf_token() }}';

        $(function() {

            var table = $('#PurchasTable').DataTable({
                columns: [{
                        data: 'id',
                        name: 'id',
                        visible: false,
                    },
                    // {
                    //     data: 'purchas_id',
                    //     name: 'purchas_id',
                    //     visible: false,
                    // },
                    {
                        data: 'comprobante',
                        name: 'comprobante',
                    },
                    {
                        data: 'n° de comprobante',
                        name: 'n° de comprobante',
                    },
                    {
                        data: 'empleado',
                        name: 'empleado',
                    },
                    {
                        data: 'cod compra',
                        name: 'cod compra',
                    },
                    {
                        data: 'fecha de compra',
                        name: 'fecha de compra',
                    },
                    {
                        data: 'proveedor',
                        name: 'proveedor',
                    },
                    // {
                    //     data: 'productos',
                    //     name: 'productos',
                    // },
                    // {
                    //     data: 'cantidad',
                    //     name: 'cantidad',
                    // },
                    // {
                    //     data: 'precio',
                    //     name: 'precio',
                    // },
                    // {
                    //     data: 'subtotal',
                    //     name: 'subtotal',
                    // },
                    {
                        data: 'total',
                        name: 'total',
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
                    },
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
                    // {
                    //     text: '<i class="fa fa-plus"></i> Registrar Producto',
                    //     className: 'btn btn-sm btn-primary bg-danger mx-1',
                    //     action: () => openRegisterModal(),
                    // },
                    // {
                    //     text: '<i class="fa fa-plus"></i> Cargar Productos',
                    //     className: 'btn btn-sm btn-primary bg-danger mx-1',
                    //     action: () => openRegisterExcelModal(),
                    // },
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

            function initializeDataTable(data) {
                table.clear().rows.add(data).draw();
            }

            function refreshPurchsesDataTable() {
                $.ajax({
                    url: purchases_DataRoute,
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        if (response.data) {
                            // console.log('Datos encontrados: \n ' + response.data);
                            initializeDataTable(response.data);
                        } else {
                            console.log('No se encontraron datos de Componentes.');
                        }
                    },
                    error: function(xhr, textStatus, error) {
                        var errorContainer = $('#error-message');

                        if (xhr.status === 403) {
                            errorContainer.text('Acceso denegado').show();
                        } else {
                            errorContainer.text('Error desconocido').show();
                        }
                        // Desaparecer el mensaje después de 5 segundos
                        setTimeout(function() {
                            errorContainer.hide();
                        }, 3000); // 3000 milisegundos = 5 segundos

                    }
                });
            }

            function generateButtons(row) {
                var btnEdit =
                    '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" onclick="openEditModal(this)" data-product=\'' +
                    JSON.stringify(row) +
                    '\'><i class="fa fa-lg fa-fw fa-pen"></i></button> ';
                var btnDelete =
                    '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" onclick="openDeleteModal(' +
                    row.id +
                    ')"><i class="fa fa-lg fa-fw fa-trash"></i></button> ';

                return '<nobr>' + btnEdit + btnDelete + '</nobr>';
            }

            refreshPurchsesDataTable();

            setInterval(refreshPurchsesDataTable, 5000);
        });
    </script>
@endsection
