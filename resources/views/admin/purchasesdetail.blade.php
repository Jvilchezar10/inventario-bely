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
        $columnsProducts = ['id', 'productos', 'cantidad', 'precio', 'subtotal', 'creado en', 'actualizado en', 'opciones'];
        $dataProducts = [];
    @endphp

    <x-adminlte-card id="detalleCompra" title="Detalle de compra" theme="pink" icon="fas fa-tshirt" class="elevation-3"
        maximizable collapsible>
        <div class="card-body">
            <x-datatable :columns="$columnsProducts" :data="$dataProducts" id="PurchasDetailTable" />
        </div>
    </x-adminlte-card>

    <x-adminlte-card title="Lista de compras" theme="pink" icon="fas fa-tshirt" class="elevation-3" maximizable>
        <x-datatable :columns=$columns :data=$data id="PurchasTable" />
    </x-adminlte-card>

    <x-delete-modal title='Eliminar Compra' size='md' modalId='deletePurchasModal' formId="destroyPurchasForm"
        quetion='¿Está seguro que desea eliminar la compra?' :field="['name' => 'd_id']" onClick="deletePurchas()" />

@endsection

@section('js')
    <script>
        var purchases_DataRoute = '{{ route('purchases.data') }}';
        var csrfToken = '{{ csrf_token() }}';
    </script>
    <script src="{{ asset('js/toast.js') }}"></script>
    <script>
        document.getElementById("destroyPurchasForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Detiene el envío del formulario predeterminado
        });

        function deletePurchas() {
            var id = $('#deletePurchasModal input[name="d_id"]').val();
            // Cuando el usuario confirme la eliminación, realizar una solicitud AJAX para borrar la compra
            $.ajax({
                url: '{{ route('purchases.destroy', ['id' => ':id']) }}'.replace(':id', id),
                type: 'DELETE',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                success: function(response) {
                    // Aquí puedes mostrar un mensaje de éxito o realizar alguna acción adicional después de borrar la compra
                    console.log('Compra borrada exitosamente');
                    // Por ejemplo, podrías recargar la tabla de compras para reflejar los cambios
                    showCustomToast({
                        title: 'Elimación exitosa',
                        body: 'Compra eliminada con éxito.',
                        class: 'bg-success',
                        icon: 'fas fa-check-circle',
                        close: false,
                        autohide: true,
                        delay: 5000
                    });
                    //
                    $('#deletePurchasModal').modal('hide');
                },
                error: function(xhr, textStatus, error) {
                    console.log('Error al eliminar la compra: ' + error);
                }
            });
        }

        function openDeleteModal(id) {
            $('#deletePurchasModal input[name="d_id"]').val(id);
            // abrir modal
            $('#deletePurchasModal').modal('show');
        }

        $(function() {
            var table = $('#PurchasTable').DataTable({

                columns: [{
                        data: 'id',
                        name: 'id',
                        visible: false,
                    },
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
                    {
                        data: 'origen',
                        name: 'origen',
                    },
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
                ],
                responsive: true,
                paging: true,
                stateDuration: -1,
                info: true,
                select: {
                    style: 'multi',
                    selector: 'td:first-child'
                },
                rowCallback: function(row, data) {
                    $(row).on('click', function() {
                        console.log(data.id);
                        loadPurchaseDetails(data.id);
                    });
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
                        'X-CSRF-TOKEN': csrfToken,
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
            // Obtener el elemento por su id
            var detalleCompra = document.getElementById('detalleCompra');
            // Para agregar el atributo hidden
            detalleCompra.setAttribute('hidden', '');

            var tableDetails = $('#PurchasDetailTable').DataTable({
                columns: [{
                        data: 'id',
                        name: 'id',
                        visible: false,
                    },
                    {
                        data: 'productos',
                        name: 'productos',
                    },
                    {
                        data: 'cantidad',
                        name: 'cantidad',
                    },
                    {
                        data: 'precio',
                        name: 'precio',
                    },
                    {
                        data: 'sub total',
                        name: 'sub total',
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
                            return generateButtonsDetails(row);
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

            function initializeDataTableDetails(data) {
                tableDetails.clear().rows.add(data).draw();
                // Para quitar el atributo hidden
                detalleCompra.removeAttribute('hidden');
            }

            function loadPurchaseDetails(purchaseId) {
                // Realizar solicitud AJAX para obtener los detalles de la compra
                $.ajax({
                    // Ruta para obtener los detalles de una compra específica
                    url: '{{ route('purchases_id.data', ['id' => ':id']) }}'.replace(':id', purchaseId),
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    success: function(response) {
                        if (response.data) {
                            initializeDataTableDetails(response.data);
                        } else {
                            console.log('No se encontraron detalles de compra.');
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
                        // Para agregar el atributo hidden
                        detalleCompra.setAttribute('hidden', '');
                    }
                });
            }

            function generateButtonsDetails(row) {
                // var btnEdit =
                //     '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" onclick="openEditModal(this)" data-product=\'' +
                //     JSON.stringify(row) +
                //     '\'><i class="fa fa-lg fa-fw fa-pen"></i></button> ';
                var btnDelete =
                    '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" onclick="openDeleteModal(' +
                    row.id +
                    ')"><i class="fa fa-lg fa-fw fa-trash"></i></button> ';

                return '<nobr>' /*+ btnEdit */ + btnDelete + '</nobr>';
            }


            refreshPurchsesDataTable();

            setInterval(refreshPurchsesDataTable, 5000);
        });
    </script>
@endsection
