@extends('adminlte::page')

@section('content_header')
    <h1 class="m-0 text-dark">Clientes</h1>
@stop

@section('content')
    <div id="error-message" class="alert alert-danger" style="display: none;"></div>
    @if ($message = Session::get('success'))
        <div id="success-message" class="alert alert-success">
            {{ $message }}
        </div>
    @endif

    <x-adminlte-card title="Lista de Clientes" theme="pink" icon="fas fa-tags" class="elevation-3" maximizable>
        <x-datatable :columns=$columns :data=$data id="clientsTable" />
    </x-adminlte-card>

    {{-- Modales --}}
    <x-register-modal formId="registerClientForm" :fields="[
        [
            'name' => 'i_name',
            'label' => 'Nombre completo',
            'placeholder' => 'ingrese el nombre del cliente',
            'type' => 'input',
        ],
        ['name' => 'i_DNI', 'label' => 'DNI', 'placeholder' => '@75363204', 'type' => 'input'],
        [
            'name' => 'i_phone',
            'label' => 'Número de celular',
            'placeholder' => '@951758468',
            'type' => 'input',
        ],
    ]" title='Añadir cliente' size='md'
        modalId='registerClientModal' onClick="registerClient()" />

    <x-edit-modal formId="updateClientForm" :fields="[
        ['name' => 'e_id', 'type' => 'hidden'],
        [
            'name' => 'e_name',
            'label' => 'Nombre completo',
            'placeholder' => 'ingrese el nombre del cliente',
            'type' => 'input',
        ],
        ['name' => 'e_DNI', 'label' => 'DNI', 'placeholder' => '@75363204', 'type' => 'input'],
        [
            'name' => 'e_phone',
            'label' => 'Número de celular',
            'placeholder' => '@951758468',
            'type' => 'input',
        ],
    ]" title='Editar cliente' size='md'
        modalId='editClientModal' onClick="updateClient()" />

    <x-delete-modal title='Eliminar cliente' size='md' modalId='deleteClientModal' formId="destroyClientForm"
        quetion='¿Está seguro que desea eliminar el cliente?' :field="['name' => 'd_id']" onClick="deleteClient()" />

        <x-register-excel-modal title='Importar clientes' modalId='registerClientExcelModal' :field="[
        'name' => 'excel_file',
        'label' => 'Seleccionar archivo Excel',
        'placeholder' => 'Seleccione un archivo',
    ]"
        :route="route('clients.import')" />

@endsection

@section('js')
    <script>
        var clientId = 0;
        var clientsDataRoute = '{{ route('clients.data') }}';
        var csrfToken = '{{ csrf_token() }}';
    </script>
    <script src="{{ asset('js/toast.js') }}"></script>
    <script>
        document.getElementById("registerClientForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Detiene el envío del formulario predeterminado
        });
        document.getElementById("updateClientForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Detiene el envío del formulario predeterminado
        });
        document.getElementById("destroyClientForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Detiene el envío del formulario predeterminado
        });

        function registerClient() {
            // Obtener los datos del formulario
            var formData = $('#registerClientModal form').serialize();
            // Realizar la petición AJAX para el registro de la categoría
            $.ajax({
                url: '{{ route('clients.store') }}',
                type: 'POST',
                dataType: 'json',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    // Mostrar mensaje de éxito o realizar acciones adicionales si es necesario
                    console.log('Categoría registrada con éxito.');
                    //
                    showCustomToast({
                        title: 'Registro exitoso',
                        body: 'Categoría registrada con éxito.',
                        class: 'bg-success',
                        icon: 'fas fa-check-circle',
                        close: false,
                        autohide: true,
                        delay: 5000
                    });
                    //
                    $('#registerClientModal').modal('hide');
                },
                error: function(xhr, textStatus, errorThrown) {
                    // Si se produce un error en la solicitud
                    var errorContainer = $('#error-message');

                    if (xhr.status === 403) {
                        errorContainer.text('Acceso denegado').show();
                    } else {
                        showCustomToast({
                            title: 'Eliminación denegada',
                            body: 'No puedes registar una categoría vacia.',
                            class: 'bg-danger',
                            icon: 'fas fa-exclamation-triangle',
                            close: false,
                            autohide: true,
                            delay: 5000
                        });
                    }

                    // Desaparecer el mensaje después de 5 segundos
                    setTimeout(function() {
                        errorContainer.hide();
                    }, 3000); // 3000 milisegundos = 5 segundos
                }

            });
        }

        function updateClient() {
            // Obtener los datos del formulario
            var formData = $('#editClientModal form').serialize();
            var id = $('#editClientModal input[name="e_id"]').val();

            event.preventDefault();
            // Realizar la petición AJAX para la actualización de la categoría
            $.ajax({
                url: '{{ route('clients.update', ['id' => ':id']) }}'.replace(':id', id),
                type: 'POST',
                dataType: 'json',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    // Mostrar mensaje de éxito o realizar acciones adicionales si es necesario
                    console.log('Categoría actualizada con éxito.');
                    // Cerrar el modal después de la operación exitosa
                    showCustomToast({
                        title: 'Actualización exitoso',
                        body: 'Categoría actualizada con éxito.',
                        class: 'bg-success',
                        icon: 'fas fa-check-circle',
                        close: false,
                        autohide: true,
                        delay: 5000
                    });
                    //
                    $('#editClientModal').modal('hide');
                },
                error: function(xhr, textStatus, error) {
                    // Mostrar mensaje de error o realizar acciones adicionales si es necesario\
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

        function deleteClient() {
            var id = $('#deleteClientModal input[name="d_id"]').val();

            // Realizar la petición AJAX para la eliminación de la categoría
            $.ajax({
                url: '{{ route('clients.destroy', ['id' => ':id']) }}'.replace(':id', id),
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
                    console.log('Categoría eliminada con éxito.');
                    //
                    showCustomToast({
                        title: 'Elimación exitosa',
                        body: 'Categoría eliminada con éxito.',
                        class: 'bg-success',
                        icon: 'fas fa-check-circle',
                        close: false,
                        autohide: true,
                        delay: 5000
                    });
                    //
                    $('#deleteClientModal').modal('hide');
                },
                error: function(xhr, textStatus, error) {
                    var errorContainer = $('#error-message');

                    if (xhr.status === 403) {
                        errorContainer.text('Acceso denegado').show();
                    } else {
                        //errorContainer.text('Error desconocido').show();

                        showCustomToast({
                            title: 'Elimación denegada',
                            body: 'No puedes eliminar una categoría que esta siendo usada.',
                            class: 'bg-danger',
                            icon: 'fas fa-exclamation-triangle',
                            close: false,
                            autohide: true,
                            delay: 5000
                        });
                    }
                    // Desaparecer el mensaje después de 5 segundos
                    setTimeout(function() {
                        errorContainer.hide();
                    }, 3000); // 3000 milisegundos = 5 segundos
                }
            });
        }

        function openRegisterModal() {
            $('#registerClientModal').modal('show');
        }

        function openRegisterExcelModal() {
            $('#registerClientExcelModal').modal('show');
        }

        function openEditModal(button) {
            var client = JSON.parse(button.getAttribute('data-product')); // Analizar la cadena JSON en un objeto

            // Asignar los valores a los campos del modal
            $('#editClientModal input[name="e_id"]').val(client.id);
            $('#editClientModal input[name="e_name"]').val(client['nombre completo']);
            $('#editClientModal input[name="e_DNI"]').val(client['DNI']);
            $('#editClientModal input[name="e_phone"]').val(client['número de celular']);

            $('#editClientModal').modal('show'); // Invocar al modal de edición
        }

        function openDeleteModal(id) {
            $('#deleteClientModal input[name="d_id"]').val(id);
            // abrir modal
            $('#deleteClientModal').modal('show');
        }

        $(function() {

            var table = $('#clientsTable').DataTable({
                columns: [{
                        data: 'id',
                        name: 'id',
                    },
                    {
                        data: 'nombre completo',
                        name: 'nombre completo',
                    },
                    {
                        data: 'DNI',
                        name: 'DNI',
                    },
                    {
                        data: 'número de celular',
                        name: 'número de celular',
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
                timeout: 3000, // Tiempo de espera en milisegundos (5 segundos)
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
                        text: '<i class="fa fa-plus"></i> Registrar cliente',
                        className: 'btn btn-sm btn-primary bg-danger mx-1',
                        action: () => openRegisterModal(),
                    },
                    {
                        text: '<i class="fa fa-plus"></i> Cargar clientes',
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

            function refreshclientDataTable() {
                $.ajax({
                    url: clientsDataRoute,
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
                            console.log('No se encontraron datos de Categorías.');
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

            refreshclientDataTable();

            setInterval(refreshclientDataTable, 5000);
        });
    </script>
@endsection
