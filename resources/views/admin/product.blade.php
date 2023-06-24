@extends('adminlte::page')

@section('content_header')
    <h1 class="m-0 text-dark">Productos</h1>
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

    <x-adminlte-card title="Lista de Productos" theme="pink" icon="fas fa-tshirt" class="elevation-3" maximizable>
        <x-datatable :columns=$columns :data=$data id="productsTable" />
    </x-adminlte-card>

    {{-- Modales --}}
    <x-register-modal formId="registerProductForm" :fields="[
        [
            'name' => 'i_cod_producto',
            'label' => 'Cod Producto',
            'placeholder' => '@PRO0001',
            'type' => 'input',
        ],
        [
            'name' => 'i_selectProveedor',
            'label' => 'Proveedor',
            'placeholder' => 'Seleccionar el proveedor',
            'type' => 'select2_with_search',
        ],
        [
            'name' => 'selectCategoria',
            'label' => 'Categoria',
            'placeholder' => 'Seleccionar la categoria',
            'type' => 'select2_with_search',
        ],
        [
            'name' => 'i_descripcion',
            'label' => 'Descripción',
            'placeholder' => 'Ingrese la descripción del producto',
            'type' => 'long_text',
        ],
        [
            'name' => 'i_talla',
            'label' => 'Talla',
            'placeholder' => 'calzado @35-44, vestido @XS-XL, etc ',
            'type' => 'input',
        ],
        [
            'name' => 'i_stock_min',
            'label' => 'Stock min',
            'placeholder' => 'Ingrese el stock minima',
            'type' => 'number',
        ],
        [
            'name' => 'i_stock',
            'label' => 'Stock',
            'placeholder' => 'Ingrese el stock',
            'type' => 'number',
        ],
        [
            'name' => 'i_precio_compra',
            'label' => 'Precio compra',
            'placeholder' => 'Ingrese el precio de compra',
            'type' => 'number',
        ],
        [
            'name' => 'i_precio_venta',
            'label' => 'Precio venta',
            'placeholder' => 'Ingrese el precio de venta',
            'type' => 'number',
        ],
    ]" title="Registrar Producto" size="md"
        modalId="registerProductModal" onClick="registerProduct()" />

    <x-edit-modal formId="updateProductForm" :fields="[
        ['name' => 'e_id', 'type' => 'hidden'],
        [
            'name' => 'e_cod_producto',
            'label' => 'Cod Producto',
            'placeholder' => 'Ingrese el codigo del producto',
            'type' => 'input',
        ],
        [
            'name' => 'e_selectProveedor',
            'label' => 'Proveedor',
            'placeholder' => 'Seleccionar el proveedor',
            'type' => 'select2_with_search',
        ],
        [
            'name' => 'e_selectCategoria',
            'label' => 'Categoria',
            'placeholder' => 'Seleccionar la categoria',
            'type' => 'select2_with_search',
        ],
        [
            'name' => 'e_descripcion',
            'label' => 'Descripción',
            'placeholder' => 'Ingrese la descripción del producto',
            'type' => 'long_text',
        ],
        [
            'name' => 'e_talla',
            'label' => 'Talla',
            'placeholder' => 'calzado @35-44, vestido @XS-XL, Anillos @4-12 ',
            'type' => 'input',
        ],
        [
            'name' => 'e_stock_min',
            'label' => 'Stock min',
            'placeholder' => 'Ingrese el stock minima',
            'type' => 'number',
        ],
        [
            'name' => 'e_stock',
            'label' => 'Stock',
            'placeholder' => 'Ingrese el stock',
            'type' => 'number',
        ],
        [
            'name' => 'e_precio_compra',
            'label' => 'Precio compra',
            'placeholder' => 'Ingrese el precio de compra',
            'type' => 'number',
        ],
        [
            'name' => 'e_precio_venta',
            'label' => 'Precio venta',
            'placeholder' => 'Ingrese el precio de venta',
            'type' => 'number',
        ],
    ]" title='Editar Producto' size='md'
        modalId="editProductModal" onClick="updateProduct()" />

    <x-delete-modal title='Eliminar Producto' size='md' modalId='deleteProductModal' formId="destroyProductForm"
        quetion='¿Está seguro que desea eliminar el producto?' :field="['name' => 'd_id']" onClick="deleteProduct()" />

    <x-register-excel-modal title='Importar productos' modalId='registerProductExcelModal' :field="[
        'name' => 'excel_file',
        'label' => 'Seleccionar archivo Excel',
        'placeholder' => 'Seleccione un archivo',
    ]"
        :route="route('products.import')" />

@endsection

@section('js')
    <script>
        var employeeId = 0;
        var providersDataRoute = '{{ route('providers.search') }}';
        var productsDataRoute = '{{ route('products.data') }}';
        var categoriesDataRoute = '{{ route('categories.search') }}';
        var csrfToken = '{{ csrf_token() }}';
    </script>
    <script src="{{ asset('js/toast.js') }}"></script>
    <script>

        document.getElementById("registerProductForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Detiene el envío del formulario predeterminado
        });
        document.getElementById("updateProductForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Detiene el envío del formulario predeterminado
        });

        document.getElementById("destroyProductForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Detiene el envío del formulario predeterminado
        });

        function registerProduct() {
            // Obtener los datos del formulario
            var formData = $('#registerProductModal form').serialize();
            // Realizar la petición AJAX para el registro de el empleado

            $.ajax({
                url: '{{ route('products.store') }}',
                type: 'POST',
                dataType: 'json',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    // Mostrar mensaje de éxito o realizar acciones adicionales si es necesario
                    console.log('Producto registrado con éxito.');
                    // Cerrar el modal después de la operación exitosa
                    showCustomToast({
                        title: 'Registro exitoso',
                        body: 'Producto registrado con éxito.',
                        class: 'bg-success',
                        icon: 'fas fa-check-circle',
                        close: false,
                        autohide: true,
                        delay: 5000
                    });
                    //
                    $('#registerProductModal').modal('hide');
                },
                error: function(xhr, textStatus, error) {
                    // Mostrar mensaje de error o realizar acciones adicionales si es necesario
                    console.log('Error al registrar el producto: ' + error);
                }
            });
        }

        function updateProduct() {
            // Obtener los datos del formulario
            var formData = $('#editProductModal form').serialize();

            var id = $('#editProductModal input[name="e_id"]').val();

            // Realizar la petición AJAX para la actualización de la Productos
            $.ajax({
                //     url: '{{ route('categories.update', ['id' => ':id']) }}'.replace(':id', id),
                url: '{{ route('products.update', ['id' => ':id']) }}'.replace(':id', id),
                type: 'POST',
                dataType: 'json',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    // Mostrar mensaje de éxito o realizar acciones adicionales si es necesario
                    console.log('Productos actualizada con éxito.');
                    // Cerrar el modal después de la operación exitosa
                    showCustomToast({
                        title: 'Actualización exitoso',
                        body: 'Producto actualizado con éxito.',
                        class: 'bg-success',
                        icon: 'fas fa-check-circle',
                        close: false,
                        autohide: true,
                        delay: 5000
                    });
                    //
                    $('#editProductModal').modal('hide');
                },
                error: function(xhr, textStatus, error) {
                    // Mostrar mensaje de error o realizar acciones adicionales si es necesario
                    console.log('Error al actualizar el Productos: ' + error);
                }
            });
        }


        function deleteProduct() {
            var id = $('#deleteProductModal input[name="d_id"]').val();
            console.log(id);

            // Realizar la petición AJAX para la eliminación de la categoría
            $.ajax({
                url: '{{ route('products.destroy', ['id' => ':id']) }}'.replace(':id', id),
                type: 'POST',
                dataType: 'json',
                data: {
                    '_method': 'DELETE',
                    '_token': csrfToken
                },
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    // Mostrar mensaje de éxito o realizar acciones adicionales si es necesario
                    console.log('Producto eliminada con éxito.');
                    //
                    showCustomToast({
                        title: 'Elimación exitosa',
                        body: 'Producto eliminado con éxito.',
                        class: 'bg-success',
                        icon: 'fas fa-check-circle',
                        close: false,
                        autohide: true,
                        delay: 5000
                    });
                    //
                    $('#deleteProductModal').modal('hide');
                },
                error: function(xhr, textStatus, error) {
                    // Mostrar mensaje de error o realizar acciones adicionales si es necesario
                    console.log('Error al eliminar el producto: ' + error);
                }
            });
        }

        function openRegisterModal() {
            $('#registerProductModal').modal('show');
        }

        function openRegisterExcelModal() {
            $('#registerProductExcelModal').modal('show');
        }

        function openEditModal(button) {
            var product = JSON.parse(button.getAttribute('data-product')); // Analizar la cadena JSON en un objeto
            // Asignar los valores a los campos del modal
            var selectValue = product['categoria_id'];
            var selectText = product['categoria'];
            var optionCategoria = new Option(selectText, selectValue, true, true); // Crear una opción

            var selectValue = product['proveedor_id'];
            var selectText = product['proveedor'];
            var optionProveedor = new Option(selectText, selectValue, true, true); // Crear una opción

            $('#editProductModal input[name="e_id"]').val(product.id);
            $('#editProductModal input[name="e_cod_producto"]').val(product['cod producto']);
            $('#editProductModal select[name="e_selectProveedor"]').empty().append(optionProveedor);
            $('#editProductModal select[name="e_selectCategoria"]').empty().append(optionCategoria);
            $('#editProductModal textarea[name="e_descripcion"]').text(product['descripción']);
            $('#editProductModal input[name="e_talla"]').val(product.talla);
            $('#editProductModal input[name="e_stock_min"]').val(product['stock min']);
            $('#editProductModal input[name="e_stock"]').val(product['stock']);
            $('#editProductModal input[name="e_precio_compra"]').val(product['precio compra']);
            $('#editProductModal input[name="e_precio_venta"]').val(product['precio venta']);


            $('#editProductModal').modal('show'); // Invocar al modal de edición
            // Esperar a que los datos del select se carguen
        }

        function openDeleteModal(id) {
            $('#deleteProductModal input[name="d_id"]').val(id);
            // Lógica para mostrar el mensaje de confirmación de eliminación
            // y abrir el modal de eliminacións
            $('#deleteProductModal').modal('show');
        }

        $(function() {

            var table = $('#productsTable').DataTable({
                columns: [{
                        data: 'id',
                        name: 'id',
                        visible: false,
                    },
                    {
                        data: 'cod producto',
                        name: 'cod producto'
                    },
                    {
                        data: 'proveedor',
                        name: 'proveedor'
                    },
                    {
                        data: 'proveedor_id',
                        name: 'proveedor_id',
                        visible: false,
                    },
                    {
                        data: 'categoria',
                        name: 'categoria'
                    },
                    {
                        data: 'categoria_id',
                        name: 'categoria_id',
                        visible: false,
                    },
                    {
                        data: 'descripción',
                        name: 'descripción'
                    },
                    {
                        data: 'talla',
                        name: 'talla'
                    },
                    {
                        data: 'stock min',
                        name: 'stock min'
                    },
                    {
                        data: 'stock',
                        name: 'stock'
                    },
                    {
                        data: 'precio compra',
                        name: 'precio compra'
                    },
                    {
                        data: 'precio venta',
                        name: 'precio venta'
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

            function initializeDataTable(data) {
                table.clear().rows.add(data).draw();
            }

            function refreshProductDataTable() {
                $.ajax({
                    url: productsDataRoute,
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
                            console.log('No se encontraron datos de Productos.');
                        }
                    },
                    error: function(xhr, textStatus, error) {
                        console.log('Error al obtener los datos de Productos: ' + error);
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

            refreshProductDataTable();

            setInterval(refreshProductDataTable, 5000);
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
            initializeSelect2('#i_selectProveedor', providersDataRoute, 'prov');
            initializeSelect2('#e_selectProveedor', providersDataRoute, 'prov');
            initializeSelect2('#selectCategoria', categoriesDataRoute, 'q');
            initializeSelect2('#e_selectCategoria', categoriesDataRoute, 'q');
        });
    </script>
@endsection
