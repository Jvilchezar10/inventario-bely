@extends('adminlte::page')

@section('content_header')
    <h1 class="m-0 text-dark">Registro de ventas</h1>
@stop

@section('content')
    <div id="error-message" class="alert alert-danger" style="display: none;"></div>
    @if ($message = Session::get('success'))
        <div id="success-message" class="alert alert-success">
            {{ $message }}
        </div>
    @endif
    @php
        $columnsProducts = ['id', 'producto', 'precio', 'cantidad', 'subtotal'];
        $dataProducts = [];
    @endphp

    <div class="row">
        <div class="col-md-8">
            <x-adminlte-card title="Detalle de ventas" theme="pink" icon="fas fa-tshirt" class="elevation-3" maximizable>
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
                <div class="text-right mt-3">
                    <strong>Total: $<span id="totalAmount">0</span></strong>
                </div>
            </x-adminlte-card>
        </div>
        <div class="col-md-4">
            <x-card-detail title="Datos" formId="form_saledateil" :fields="[
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
                [
                    'label' => 'Anular',
                    'onClick' => 'fallo()',
                    'class' => 'danger',
                    'type' => 'button',
                    'inputClass' => 'col-md-6',
                ],
                [
                    'label' => 'Guardar',
                    'onClick' => 'exito()',
                    'class' => 'success',
                    'type' => 'button',
                    'inputClass' => 'col-md-6',
                ],
            ]" />
        </div>
    </div>
@endsection


@section('js')
    <script>
        var salesDataRoute = '{{ route('sales.data') }}';
        var productsDataRoute = '{{ route('products.search') }}';
        var employeesDataRoute = '{{ route('employees.search') }}';
        var proofofpaymentsDataRoute = '{{ route('proofofpayments.search') }}';
        var csrfToken = '{{ csrf_token() }}';
    </script>
    <script src="{{ asset('js/toast.js') }}"></script>
    <script>
        //funciones de prueba
        function exito() {
            showCustomToast({
                title: 'Registro exitoso',
                body: 'Producto registrado con éxito.',
                class: 'bg-success',
                icon: 'fas fa-check-circle',
                close: false,
                autohide: true,
                delay: 5000
            });
        }

        //funciones de prueba
        function fallo() {
            showCustomToast({
                title: 'Registro fallo',
                body: ':(',
                class: 'bg-danger',
                icon: 'fas fa-exclamation-triangle',
                close: false,
                autohide: true,
                delay: 5000
            });
        }

        $(document).ready(function() {
            // Inicializar tabla de productos
            var productsTable = $('#productsTable').DataTable({
                columns: [
                    null, // ID
                    null, // Producto
                    null, // Precio
                    {
                        className: 'editable-column',
                        defaultContent: '',
                        render: function(data, type, row, meta) {
                            if (type === 'display') {
                                if (data === '') {
                                    return '<input type="text" class="form-control" inputmode="numeric">';
                                } else {
                                    return data;
                                }
                            }
                            return data;
                        },
                        createdCell: function(cell, cellData, rowData, rowIndex, colIndex) {
                            $(cell).on('click', function() {
                                if ($(this).hasClass('editing')) {
                                    return;
                                }
                                $(this).addClass('editing');
                                var input = $(
                                    '<input type="number" class="form-control" inputmode="numeric">'
                                ).val(cellData);
                                $(this).html(input);
                                input.focus();
                            });

                            $(cell).on('blur', 'input', function() {
                                var value = $(this).val();
                                productsTable.cell(cell).data(value).draw();

                                if ($(cell).parent().length) {
                                    $(cell).removeClass('editing');
                                }
                            });

                            $(cell).on('input', 'input', function() {
                                var value = parseFloat($(this).val());

                                // Validar que el valor sea un número válido
                                if (!isNaN(value)) {
                                    var rowIndex = productsTable.cell(cell).index().row;
                                    var precio = parseFloat(productsTable.cell(rowIndex, 2)
                                        .data());

                                    if (!isNaN(precio)) {
                                        var subtotal = (precio * value).toFixed(2);
                                        var targetCell = productsTable.cell(rowIndex, 4);

                                        if (!isNaN(subtotal)) {
                                            targetCell.data(subtotal);
                                            updateTotal();
                                        }
                                    }
                                }
                            });

                            // Escuchar el evento 'keypress' para permitir solo números enteros
                            $(cell).on('keypress', 'input', function(event) {
                                var keyCode = event.which ? event.which : event.keyCode;
                                var isValid = keyCode >= 48 && keyCode <=
                                    57; // Rango de códigos ASCII para números enteros

                                return isValid;
                            });
                        },
                    },
                    null, // subtotal
                ],
            });
            // Función para actualizar el total
            function updateTotal() {
                var total = 0;
                var subtotals = productsTable.column(4).data().toArray();

                for (var i = 0; i < subtotals.length; i++) {
                    var subtotal = parseFloat(subtotals[i]);
                    if (!isNaN(subtotal)) {
                        total += subtotal;
                    }
                }

                $('#totalAmount').text(total.toFixed(2));
            }

            // Llamar a la función updateTotal al cargar la página para mostrar el total inicial
            updateTotal();

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
        });
    </script>
@endsection
