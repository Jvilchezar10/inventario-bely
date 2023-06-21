@extends('adminlte::page')

@section('content_header')
    <h1 class="m-0 text-dark">Registro de ventas</h1>
@stop

@section('content')

    @php
        $columnsClient = ['id', 'producto', ''];
        $dataProducts = [];
    @endphp

    <div class="row">
        <div class="col-md-8">
            <x-adminlte-card title="Productos seleccionados" theme="pink" icon="fas fa-tshirt" class="elevation-3"
                maximizable>
                <x-datatable :columns=$columns :data=$data id="salesTable" />
            </x-adminlte-card>
        </div>
        <div class="col-md-4">
            <x-card-detail title="Detalle de venta" formId="form_saledateil" :fields="[
                [
                    'name' => 'i_selectEmployee',
                    'label' => 'Empleado',
                    'placeholder' => '@juan edu',
                    'type' => 'select2_with_search',
                    'inputClass' => 'col-md-6',
                ],
                [
                    'name' => 'i_sales_date',
                    'label' => 'Fecha de venta',
                    'placeholder' => '@2002/06/10',
                    'title' => 'Fecha venta',
                    'config' => 'date_hour',
                    'type' => 'datetime',
                    'inputClass' => 'col-md-6',
                ],
                [
                    'name' => 'i_selectProof',
                    'label' => 'Comprobante',
                    'placeholder' => '@Boleta',
                    'type' => 'select2_with_search',
                    'inputClass' => 'col-md-6',
                ],
                [
                    'name' => 'i_voucher_number',
                    'label' => 'N° comprobante',
                    'placeholder' => '@126354789632',
                    'type' => 'input',
                    'inputClass' => 'col-md-6',
                ],
                [
                    'name' => 'i_fullname',
                    'label' => 'Nombre del cliente',
                    'placeholder' => 'Ingrese el nombre completo del cliente',
                    'type' => 'input',
                    'inputClass' => 'col-md-6',
                ],
                [
                    'name' => 'i_dni',
                    'label' => 'DNI',
                    'placeholder' => '@74859612',
                    'type' => 'input',
                    'inputClass' => 'col-md-6',
                ],
                [
                    'name' => 'i_phone',
                    'label' => 'Número de celular',
                    'placeholder' => '@951753456',
                    'type' => 'input',
                    'inputClass' => 'col-md-6',
                ],
            ]" />
        </div>
    </div>
@endsection


@section('js')
    <script>
        var salesDataRoute = '{{ route('sales.data') }}';
        var employeesDataRoute = '{{ route('employees.search') }}';
        var proofofpaymentsDataRoute = '{{ route('proofofpayments.search') }}';
        var csrfToken = '{{ csrf_token() }}'; <
        script src = "{{ asset('js/toast.js') }}" >
    </script>
    <script>
        $(function() {

            var table = $('#salesTable').DataTable({
                columns: [{
                        data: 'id',
                        name: 'id',
                        visible: false,
                    },
                    {
                        data: 'sales_date',
                        name: 'sales_date'
                    },
                    {
                        data: 'client_id',
                        name: 'client_id'
                    },
                    {
                        data: 'proof_payment_id',
                        name: 'proof_payment_id'
                    },
                    {
                        data: 'employee_id',
                        name: 'employee_id'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        visible: false,
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at',
                        visible: false,
                    },
                    {
                        data: 'id',
                        name: 'actions',
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
                        text: '<i class="fa fa-plus"></i> Registrar Venta',
                        className: 'btn btn-sm btn-primary bg-danger mx-1',
                        action: () => registerSale(),
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

            function initializeDataTable(data) {
                table.clear().rows.add(data).draw();
            }

            function refreshSalesDataTable() {
                $.ajax({
                    url: salesDataRoute,
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
                            console.log('No se encontraron datos de Ventas.');
                        }
                    },
                    error: function(xhr, textStatus, error) {
                        console.log('Error al obtener los datos de Ventas: ' + error);
                    }
                });
            }

            function generateButtons(row) {
                var btnEdit =
                    '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" onclick="editSale(this)" data-sale=\'' +
                    JSON.stringify(row) +
                    '\'><i class="fa fa-lg fa-fw fa-pen"></i></button> ';
                var btnDelete =
                    '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" onclick="deleteSale(' +
                    row.id +
                    ')"><i class="fa fa-lg fa-fw fa-trash"></i></button> ';

                return '<nobr>' + btnEdit + btnDelete + '</nobr>';
            }

            refreshSalesDataTable();

            setInterval(refreshSalesDataTable, 10000);
        });
    </script>
    <script>
        function initializeSelect2(selector, dataRoute, paramName) {
            $(selector).select2({
                placeholder: 'Buscar opción',
                minimumInputLength: 2,
                ajax: {
                    url: dataRoute,
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    delay: 250,
                    data: function(params) {
                        var requestData = {};
                        requestData[paramName] = params.term;
                        return requestData;
                    },
                    processResults: function(data) {
                        return {
                            results: data,
                        };
                    },
                    cache: true
                },
                templateResult: function(option) {
                    if (option.loading) {
                        return $('<div class="loading-results">Buscando...</div>');
                    }
                    return $('<div>' + option.text + '</div>');
                },
                templateSelection: function(option) {
                    return option.text;
                }
            });
        }

        $(function() {
            initializeSelect2('#i_selectEmployee', employeesDataRoute, 'emp');
            initializeSelect2('#i_selectProof', proofofpaymentsDataRoute, 'q');
        });
    </script>
@endsection
