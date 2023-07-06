@extends('adminlte::page')

@section('content_header')
    <h1 class="m-0 text-dark">Proveedores</h1>
@stop

@section('content')
    <div id="error-message" class="alert alert-danger" style="display: none;"></div>
    @if ($message = Session::get('success'))
        <div id="success-message" class="alert alert-success">
            {{ $message }}
        </div>
    @endif
    <x-adminlte-card title="Lista de Proveedores" theme="pink" icon="fas fa-tags" class="elevation-3" maximizable>
        <x-datatable :columns=$columns :data=$data id="providersTable" />
    </x-adminlte-card>

    {{-- Modales --}}
    <x-register-modal formId="registerProviderForm" :fields="[
        ['name' => 'i_provider', 'label' => 'Proveedores', 'placeholder' => '@Jenny.sac', 'type' => 'input'],
        ['name' => 'i_DNI', 'label' => 'DNI', 'placeholder' => '@75363204', 'type' => 'input'],
        ['name' => 'i_RUC', 'label' => 'RUC', 'placeholder' => '@10629548171', 'type' => 'input'],
        [
            'name' => 'i_phone',
            'label' => 'Número de celular',
            'placeholder' => 'Ingrese el número de celular',
            'type' => 'input',
        ],
        ['name' => 'i_contact', 'label' => 'Contacto', 'placeholder' => '@joquin edu', 'type' => 'input'],
        [
            'name' => 'i_contact_phone',
            'label' => 'Número de contacto',
            'placeholder' => 'Ingrese el número de contacto',
            'type' => 'input',
        ],
    ]" title='Añadir Proveedores' size='md'
        modalId='registerProviderModal' onClick="registerProvider()" />

    <x-edit-modal formId="updateProviderForm" :fields="[
        ['name' => 'e_id', 'type' => 'hidden'],
        ['name' => 'e_provider', 'label' => 'Proveedores', 'placeholder' => '@Jenny.sac', 'type' => 'input'],
        ['name' => 'e_DNI', 'label' => 'DNI', 'placeholder' => '@75363204', 'type' => 'input'],
        ['name' => 'e_RUC', 'label' => 'RUC', 'placeholder' => '@10629548171', 'type' => 'input'],
        [
            'name' => 'e_phone',
            'label' => 'Número de celular',
            'placeholder' => 'Ingrese el número de celular',
            'type' => 'input',
        ],
        ['name' => 'e_contact', 'label' => 'Contacto', 'placeholder' => '@joquin edu', 'type' => 'input'],
        [
            'name' => 'e_contact_phone',
            'label' => 'Número de contacto',
            'placeholder' => 'Ingrese el número de contacto',
            'type' => 'input',
        ],
    ]" title='Editar Proveedores' size='md'
        modalId='editProviderModal' onClick="updateProvider()" />

    <x-delete-modal title='Eliminar Proveedor' size='md' modalId='deleteProviderModal' formId="destroyProviderForm"
        quetion='¿Está seguro que desea eliminar el proveedor?' :field="['name' => 'd_id']" onClick="deleteProvider()" />

    <x-register-excel-modal title='Importar proveedores' modalId='registerProviderExcelModal' :field="[
        'name' => 'excel_file',
        'label' => 'Seleccionar archivo Excel',
        'placeholder' => 'Seleccione un archivo',
    ]"
        :route="route('providers.import')" />

@endsection

@section('js')
    <script>
        var providerId = 0;
        var providersDataRoute = '{{ route('providers.data') }}';
        var csrfToken = '{{ csrf_token() }}';
    </script>
    <script src="{{ asset('js/toast.js') }}"></script>
    <script>
        document.getElementById("registerProviderForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Detiene el envío del formulario predeterminado
        });

        document.getElementById("updateProviderForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Detiene el envío del formulario predeterminado
        });
        document.getElementById("destroyProviderForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Detiene el envío del formulario predeterminado
        });

        function registerProvider() {
            // Obtener los datos del formulario
            var formData = $('#registerProviderModal form').serialize();
            // Evitar que se envíe el formulario y se actualice la página
            $.ajax({
                url: '{{ route('providers.store') }}',
                type: 'POST',
                dataType: 'json',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    // Mostrar mensaje de éxito o realizar acciones adicionales si es necesario
                    console.log('Proveedor registrado con éxito.');
                    // Cerrar el modal después de la operación exitosa
                    showCustomToast({
                        title: 'Registro exitoso',
                        body: 'Proveedor registrado con éxito.',
                        class: 'bg-success',
                        icon: 'fas fa-check-circle',
                        close: false,
                        autohide: true,
                        delay: 5000
                    });
                    //
                    $('#registerProviderModal').modal('hide');
                },
                error: function(xhr, textStatus, errorThrown) {
                    // Si se produce un error en la solicitud
                    var response = xhr.responseJSON; // Obtener la respuesta JSON del servidor

                    if (response && response.message) {
                        console.log(response.message); // Mensaje de error del controlador
                    } else {
                        // Mostrar mensaje de error o realizar acciones adicionales si es necesario
                        console.log('Error al registrar a el Proveedor: ' + errorThrown);

                    }
                }
            });
        }

        function updateProvider() {
            // Obtener los datos del formulario
            var formData = $('#editProviderModal form').serialize();
            var id = $('#editProviderModal input[name="e_id"]').val();
            console.log(formData);
            event.preventDefault();
            // Realizar la petición AJAX para la actualización de la Proveedor
            $.ajax({
                url: '{{ route('providers.update', ['id' => ':id']) }}'.replace(':id', id),
                type: 'POST',
                dataType: 'json',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    // Mostrar mensaje de éxito o realizar acciones adicionales si es necesario
                    console.log('Proveedor actualizado con éxito.');
                    // Cerrar el modal después de la operación exitosa
                    showCustomToast({
                        title: 'Actualización exitoso',
                        body: 'Proovedor actualizado con éxito.',
                        class: 'bg-success',
                        icon: 'fas fa-check-circle',
                        close: false,
                        autohide: true,
                        delay: 5000
                    });
                    //
                    $('#editProviderModal').modal('hide');
                },
                error: function(xhr, textStatus, error) {
                    // Mostrar mensaje de error o realizar acciones adicionales si es necesario
                    console.log('Error al actualizar el proveedor: ' + error);
                }
            });
        }

        function deleteProvider() {
            var id = $('#deleteProviderModal input[name="d_id"]').val();

            // Realizar la petición AJAX para la eliminación de la Proveedor
            $.ajax({
                url: '{{ route('providers.destroy', ['id' => ':id']) }}'.replace(':id', id),
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
                    console.log('Proveedor eliminado con éxito.');
                    //
                    showCustomToast({
                        title: 'Elimación exitosa',
                        body: 'Proveedor eliminado con éxito.',
                        class: 'bg-success',
                        icon: 'fas fa-check-circle',
                        close: false,
                        autohide: true,
                        delay: 5000
                    });
                    //
                    $('#deleteProviderModal').modal('hide');
                },
                error: function(xhr, textStatus, error) {
                    // Mostrar mensaje de error o realizar acciones adicionales si es necesario
                    console.log('Error al eliminar el proveedor: ' + error);
                }
            });
        }

        function openRegisterModal() {
            $('#registerProviderModal').modal('show');
        }

        function openRegisterExcelModal() {
            $('#registerProviderExcelModal').modal('show');
        }

        function openEditModal(button) {
            var provider = JSON.parse(button.getAttribute('data-product')); // Analizar la cadena JSON en un objeto

            // Asignar los valores a los campos del modal
            $('#editProviderModal input[name="e_id"]').val(provider.id);
            $('#editProviderModal input[name="e_provider"]').val(provider.proveedor);
            $('#editProviderModal input[name="e_DNI"]').val(provider["DNI"]);
            $('#editProviderModal input[name="e_RUC"]').val(provider["RUC"]);
            $('#editProviderModal input[name="e_phone"]').val(provider["número de celular"]);
            $('#editProviderModal input[name="e_contact"]').val(provider.contacto);
            $('#editProviderModal input[name="e_contact_phone"]').val(provider["número de contacto"]);
            $('#editProviderModal').modal('show'); // Invocar al modal de edición
        }

        function openDeleteModal(id) {
            $('#deleteProviderModal input[name="d_id"]').val(id);
            // abrir modal
            $('#deleteProviderModal').modal('show');
        }

        $(function() {

            var table = $('#providersTable').DataTable({
                columns: [{
                        data: 'id',
                        name: 'id',
                        visible: false,
                    },
                    {
                        data: 'proveedor',
                        name: 'proveedor',
                    },
                    {
                        data: 'DNI',
                        name: 'DNI',
                    },
                    {
                        data: 'RUC',
                        name: 'RUC',
                    },
                    {
                        data: 'número de celular',
                        name: 'número de celular',
                    },
                    {
                        data: 'contacto',
                        name: 'contacto',
                    },
                    {
                        data: 'número de contacto',
                        name: 'número de contacto',
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
                        text: '<i class="fa fa-plus"></i> Registrar Proveedor',
                        className: 'btn btn-sm btn-primary bg-danger mx-1',
                        action: () => openRegisterModal(),
                    },
                    {
                        text: '<i class="fa fa-plus"></i> Cargar proveedores',
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

            function refreshProviderDataTable() {
                $.ajax({
                    url: providersDataRoute,
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
                            console.log('No se encontraron datos de Proveedors.');
                        }
                    },
                    error: function(xhr, textStatus, error) {
                        console.log('Error al obtener los datos de Proveedors: ' + error);
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

            refreshProviderDataTable();

            setInterval(refreshProviderDataTable, 5000);
        });
    </script>
@endsection
