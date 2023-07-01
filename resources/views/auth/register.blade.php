@extends('adminlte::page')

@section('content_header')
    <h1 class="m-0 text-dark">Usuarios</h1>
@stop

@php
    $columns = ['id', 'Nombre', 'Correo', 'Empleado', 'Tipo de usuario', 'Creado en', 'Actualizado en', 'Opciones'];
    $data = [];
    $usuarioId = 0;
@endphp


@section('content')
    <x-adminlte-card title="Lista de Usuarios" theme="pink" icon="fas fa-tags" class="elevation-3" maximizable>
        <x-datatable :columns=$columns :data=$data id="usersTable" />
    </x-adminlte-card>

    {{-- Modales --}}
    <x-register-modal formId="registerUserForm" :fields="[
        [
            'name' => 'name',
            'label' => 'Nombre',
            'placeholder' => 'Ingrese nombre',
            'type' => 'input',
            'required' => 'true',
        ],
        [
            'name' => 'email',
            'label' => 'Correo electrónico',
            'placeholder' => 'Ingrese correo',
            'type' => 'input',
            'type_input' => 'email',
            'required' => 'true',
        ],
        [
            'name' => 'i_selectEmpleado',
            'label' => 'Empleado',
            'placeholder' => 'Seleccionar el empleado',
            'type' => 'select2_with_search',
        ],
        [
            'name' => 'i_selectRoles',
            'label' => 'Tipo de usuario',
            'placeholder' => 'Seleccionar el tipo de usuario',
            'type' => 'select2_with_search',
        ],
        [
            'name' => 'password',
            'label' => 'Contraseña',
            'placeholder' => 'Ingrese contraseña',
            'type' => 'input',
            'type_input' => 'password',
            'required' => 'true',
        ],
        [
            'name' => 'password_confirmation',
            'label' => 'Confirmar contraseña',
            'placeholder' => 'Ingresar contraseñá',
            'type' => 'input',
            'type_input' => 'password',
            'required' => 'true',
        ],
    ]" title="Registrar Usuario" size="md"
        modalId="registerUserModal" onClick="registerUser()" />


    <x-editmodal :route="route('user-profile-information.update', ['id' => $usuarioId])" :fields="[
        [
            'name' => 'name',
            'label' => 'Nombre',
            'placeholder' => 'Ingrese nombre',
            'type' => 'input',
            'required' => 'true',
        ],
        [
            'name' => 'email',
            'label' => 'Correo electrónico',
            'placeholder' => 'Ingrese correo',
            'type' => 'input',
            'type_input' => 'email',
            'required' => 'true',
        ],
        [
            'name' => 'e_selectRoles',
            'label' => 'Tipo de usuario',
            'placeholder' => 'Seleccionar el tipo de usuario',
            'type' => 'select2_with_search',
        ],
        [
            'name' => 'e_selectEmpleado',
            'label' => 'Categoria',
            'placeholder' => 'Seleccionar el empleado',
            'type' => 'select2_with_search',
        ],
        ['name' => 'e_id', 'type' => 'hidden'],
    ]" title='Editar Usuario' size='md' modalId='editUserModal' />


    {{-- <x-deletemodal title='Eliminar Usuario' size='md' modalId='deleteUserModal' :route="route('usuarios.destroy', ['id' => $usuarioId])"
    quetion='¿Está seguro que desea eliminar el usuario?' :field="['name' => 'd_id']" /> --}}

@endsection

@section('js')
    <script>
        var usersDataRoute = '{{ route('register.data') }}';
        var employeesDataRoute = '{{ route('employees.search') }}';
        var rolesDataRoute = '{{ route('roles.search') }}';
        var csrfToken = '{{ csrf_token() }}';
    </script>
    <script>
        document.getElementById("registerUserForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Detiene el envío del formulario predeterminado
            registerUser(); // Llama a la función para procesar los datos y enviar la petición AJAX
        });

        function registerUser() {
            // Obtener los datos del formulario
            var formData = $('#registerUserModal form').serialize();
            // Realizar la petición AJAX para el registro de la categoría
            $.ajax({
                url: '{{ route('register') }}',
                type: 'POST',
                dataType: 'json',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    // Mostrar mensaje de éxito o realizar acciones adicionales si es necesario
                    console.log('Usuario registrada con éxito.');
                    // Cerrar el modal después de la operación exitosa
                    $('#registerUserModal').modal('hide');
                },
                error: function(xhr, textStatus, error) {
                    // Mostrar mensaje de error o realizar acciones adicionales si es necesario
                    console.log('Error al registrar el usuario: ' + error);
                }
            });
        }

        function openRegisterUser() {
            // Lógica para registrar una Usero
            $('#registerUserModal').modal('show');
        }

        function openEditUser(button) {
            var User = JSON.parse(button.getAttribute('data-user')); // Analizar la cadena JSON en un objeto

            // Asignar los valores a los campos del modal
            // $('#editUserModal input[name="e_id"]').val(User.id);
            // $('#editUserModal input[name="e_User"]').val(User.name);
            // $('#editUserModal input[name="e_state"][value="vigente"]').prop('checked', User.state === 'vigente');
            // $('#editUserModal input[name="e_state"][value="descontinuado"]').prop('checked', User.state ===
            // 'descontinuado');

            $('#editUserModal').modal('show'); // Invocar al modal de edición
        }

        function openDeleteUser(id) {
            // Lógica para mostrar el mensaje de confirmación de eliminación
            // y abrir el modal de eliminacións
            $('#deleteUserModal').modal('show');
        }

        $(function() {
            var table = $('#usersTable').DataTable({
                columns: [{
                        data: 'id',
                        name: 'id',
                        visible: false,
                    },
                    {
                        data: 'Nombre',
                        name: 'Nombre'
                    },
                    {
                        data: 'Correo',
                        name: 'Correo'
                    },
                    {
                        data: 'Empleado',
                        name: 'Empleado',
                    },
                    {
                        data: 'Tipo de usuario',
                        name: 'Tipo de usuario'
                    },
                    {
                        data: 'Creado en',
                        name: 'Creado en',
                        visible: false,
                    },
                    {
                        data: 'Actualizado en',
                        name: 'Actualizado en',
                        visible: false,
                    },
                    {
                        data: 'id',
                        name: ' ',
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
                        text: '<i class="fa fa-plus"></i> Registrar Usero',
                        className: 'btn btn-sm btn-primary bg-danger mx-1',
                        action: () => openRegisterUser(),
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

            function refreshUserDataTable() {
                $.ajax({
                    url: usersDataRoute,
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
                            console.log('No se encontraron datos de Usuarios.');
                        }
                    },
                    error: function(xhr, textStatus, error) {
                        console.log('Error al obtener los datos de Usuarios: ' + error);
                    }
                });
            }

            function generateButtons(row) {
                var btnEdit =
                    '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" onclick="openRditUser(this)" data-user=\'' +
                    JSON.stringify(row) +
                    '\'><i class="fa fa-lg fa-fw fa-pen"></i></button> ';
                var btnDelete =
                    '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" onclick="openDeleteUser(' +
                    row.id +
                    ')"><i class="fa fa-lg fa-fw fa-trash"></i></button> ';

                return '<nobr>' + btnEdit + btnDelete + '</nobr>';
            }

            refreshUserDataTable();

            setInterval(refreshUserDataTable, 10000);
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
            initializeSelect2('#i_selectEmpleado', employeesDataRoute, 'emp');
            initializeSelect2('#e_selectEmpleado', employeesDataRoute, 'emp');
            initializeSelect2('#i_selectRoles', rolesDataRoute, 'rol');
            initializeSelect2('#e_selectRoles', rolesDataRoute, 'rol');
        });
    </script>
@endsection
