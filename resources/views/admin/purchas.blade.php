@extends('adminlte::page')

@section('content_header')
    <h1 class="m-0 text-dark">Registro de Compras</h1>
@stop

@section('content')
    <div id="error-message" class="alert alert-danger" style="display: none;"></div>
    @if ($message = Session::get('success'))
        <div id="success-message" class="alert alert-success">
            {{ $message }}
        </div>
    @endif

    @php
        $columnsProducts = ['id', 'producto', 'precio', 'cantidad', 'subtotal', 'total'];
        $dataProducts = [];
    @endphp

    <div class="row">
        <div class="col-md-7">
            <x-adminlte-card title="Productos seleccionados" theme="pink" icon="fas fa-tshirt" class="elevation-3"
                maximizable>
                <x-adminlte-select2 id="search_product" name="search_product" label="Buscar Producto"
                    label-class="'text-lightblue'" igroup-size="sm" data-placeholder="ingrese el producto">
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-gradient-pink">
                            <i class="fas fa-square-list"></i>
                        </div>
                    </x-slot>
                </x-adminlte-select2>
                <div class="card mt-4">
                    <div class="card-body">
                        <x-datatable :columns="$columnsProducts" :data="$dataProducts" id="productsTable" />
                    </div>
                </div>
            </x-adminlte-card>
        </div>
        <div class="col-md-5">
            <x-card-detail title="Detalle de compra" formId="form_saledateil" :fields="[
                [
                    'name' => 'i_selectEmployee',
                    'label' => 'Empleado',
                    'placeholder' => '@juan edu',
                    'type' => 'select2_with_search',
                    'inputClass' => 'col-md-6',
                ],
                [
                    'name' => 'i_purchases_date',
                    'label' => 'Fecha de compra',
                    'placeholder' => '2002/06/10',
                    'title' => 'Fecha compra',
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
                    'name' => 'i_selectProvider',
                    'label' => 'Proveedor',
                    'placeholder' => '@juan_edu.sac',
                    'type' => 'select2_with_search',
                    'inputClass' => 'col-md-6',
                ],
            ]" />
        </div>
    </div>

@endsection

@section('js')
    <script>
        var purchaseId = 0;
        var purchasesDataRoute = '{{ route('purchases.data') }}';
        var productsDataRoute = '{{ route('products.search') }}';
        var employeesDataRoute = '{{ route('employees.search') }}';
        var proofofpaymentsDataRoute = '{{ route('proofofpayments.search') }}';
        var providersDataRoute = '{{ route('providers.search') }}';
        var csrfToken = '{{ csrf_token() }}';
    </script>
    <script src="{{ asset('js/toast.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Inicializar tabla de productos
            var productsTable = $('#productsTable').DataTable({
                columns: [
                    null, // ID
                    null, // Producto
                    null, // Precio
                    { // Columna editable
                        className: 'editable-column',
                        defaultContent: '',
                    },
                    null,
                    null,
                ],
            });

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
                                results: data
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
                }).on('select2:select', function(e) {
                    var selectedProduct = e.params.data;
                    var division = selectedProduct.text.split('-');
                    var table = $('#productsTable').DataTable();
                    table.row.add([
                        selectedProduct.id,
                        division[0],
                        division[1],
                        '',
                        '',
                        '',
                    ]).draw(false);
                });
            }

            $(function() {
                initializeSelect2('#search_product', productsDataRoute, 'pro');
            });

            // Habilitar edición de columnas
            $('#productsTable tbody').on('click', '.editable-column', function() {
                var cell = productsTable.cell(this);
                var data = cell.data();
                var input = $('<input type="text" class="form-control">').val(data);
                cell.data(input.prop('outerHTML'));
                input.appendTo(cell);
                input.focus();
            });

            // Guardar cambios al salir del campo de edición
            $('#productsTable tbody').on('blur', '.form-control', function() {
                var input = $(this);
                var value = input.val();
                var cell = input.closest('td');
                cell.empty().text(value);
            });
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
            initializeSelect2('#i_selectProvider', providersDataRoute, 'prov');
        });
    </script>
@endsection
