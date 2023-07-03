@extends('adminlte::page')

@section('content_header')
    <h1 class="m-0 text-dark">Usuarios</h1>
@stop

@php
    $columns = ['id', 'Nombre', 'Correo', 'empleado_id', 'Empleado', 'Tipo de usuario_id', 'Tipo de usuario', 'Creado en', 'Actualizado en', 'Opciones'];
    $data = [];
    $usuarioId = 0;
@endphp

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <div>
                    <strong>¡Ups!</strong> Hubo algunos problemas con tu entrada.
                </div>
            </div>
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <div>
                    {{ $message }}
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <x-adminlte-card title="Lista de Usuarios" theme="pink" icon="fas fa-tags" class="elevation-3" maximizable>
        <x-datatable :columns=$columns :data=$data id="usersTable" />
    </x-adminlte-card>

    {{-- Modales --}}
    <x-registermodal :route="route('users.store')" :fields="[
        [
            'name' => 'i_name',
            'label' => 'Nombre',
            'placeholder' => 'Ingrese nombre',
            'type' => 'input',
            'required' => 'true',
        ],
        [
            'name' => 'i_email',
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
            'name' => 'i_password',
            'label' => 'Contraseña',
            'placeholder' => 'Ingrese contraseña',
            'type' => 'input',
            'type_input' => 'password',
            'required' => 'true',
        ],
        [
            'name' => 'confirm-password',
            'label' => 'Confirmar contraseña',
            'placeholder' => 'Ingrese contraseña',
            'type' => 'input',
            'type_input' => 'password',
            'required' => 'true',
        ],
        [
            'name' => 'i_selectRoles',
            'label' => 'Tipo de usuario',
            'placeholder' => 'Seleccionar el tipo de usuario',
            'type' => 'select2_with_search',
        ],
    ]" title="Registrar Usuario" size="md"
        modalId="registerUserModal" />


    <x-editmodal :route="route('users.update', ['id' => $usuarioId])" :fields="[
        [
            'name' => 'e_name',
            'label' => 'Nombre',
            'placeholder' => 'Ingrese nombre',
            'type' => 'input',
            'required' => 'true',
        ],
        [
            'name' => 'e_email',
            'label' => 'Correo electrónico',
            'placeholder' => 'Ingrese correo',
            'type' => 'input',
            'type_input' => 'email',
            'required' => 'true',
        ],
        [
            'name' => 'e_selectEmpleado',
            'label' => 'Empleado',
            'placeholder' => 'Seleccionar el empleado',
            'type' => 'select2_with_search',
        ],
        [
            'name' => 'e_password',
            'label' => 'Contraseña',
            'placeholder' => 'Ingrese contraseña',
            'type' => 'input',
            'type_input' => 'password',
            'required' => 'true',
        ],
        [
            'name' => 'e_confirm-password',
            'label' => 'Confirmar contraseña',
            'placeholder' => 'Ingrese contraseña',
            'type' => 'input',
            'type_input' => 'password',
            'required' => 'true',
        ],
        [
            'name' => 'e_selectRoles',
            'label' => 'Tipo de usuario',
            'placeholder' => 'Seleccionar el tipo de usuario',
            'type' => 'select2_with_search',
        ],
        ['name' => 'e_id', 'type' => 'hidden'],
    ]" title='Editar Usuario' size='md' modalId='editUserModal'
        route_id="editForm" />


    <x-deletemodal title='Eliminar Usuario' size='md' modalId='deleteUserModal' :route="route('users.destroy', ['id' => $usuarioId])"
        quetion='¿Está seguro que desea eliminar el usuario?' :field="['name' => 'd_id']" route_id="deleteForm" />

@endsection

@section('js')
    <script>
        var usersDataRoute = '{{ route('users.data') }}';
        var employeesDataRoute = '{{ route('employees.search') }}';
        var rolesDataRoute = '{{ route('roles.search') }}';
        var csrfToken = '{{ csrf_token() }}';
    </script>
    <script>
        var openModals = [{
            'name': 'registerUser',
            'onClick': () => {
                $('#registerUserModal').modal('show');
            },
        }, {
            'name': 'editUser',
            'onClick': (button) => {
                var user = JSON.parse(button.getAttribute('data-user'));
                // Analizar la cadena JSON en un objeto
                //console.log(User);

                var selectValue = user['empleado_id'];
                var selectText = user['Empleado'];
                var optionEmployee = new Option(selectText, selectValue, true, true); // Crear una opción

                // Obtener el ID y nombre del rol
                var roleId = user['Tipo de usuario_id'];
                var roleName = user['Tipo de usuario'];
                var optionRole = new Option(roleName, roleId, true,
                true); // Crear una opción para el rol seleccionado

                $('#editUserModal input[name="e_id"]').val(user.id);
                $('#editUserModal input[name="e_name"]').val(user['Nombre']);
                $('#editUserModal input[name="e_email"]').val(user['Correo']);
                $('#editUserModal select[name="e_selectEmpleado"]').empty().append(optionEmployee);
                $('#editUserModal input[name="e_password"]').val('');
                $('#editUserModal input[name="confirm-password"]').val('');
                $('#editUserModal select[name="e_selectRoles"]').empty().append(optionRole);

                var route = '{{ route('users.update', ['id' => ':id']) }}'
                    .replace(':id', user.id);
                $('#editForm').attr('action', route);

                $('#editUserModal').modal('show');

            },
        }, {
            'name': 'deleteUser',
            'onClick': (id) => {

                var formId = 'deleteForm';
                var route = '{{ route('users.destroy', ['id' => ':id']) }}'
                    .replace(':id', id);
                $('#' + formId).attr('action', route);

                $('#deleteUserModal').modal('show');
            },
        }, ];

        function searchOption(modalName) {
            return openModals.find(function(modal) {
                return modal.name === modalName;
            });
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
                        data: 'empleado_id',
                        name: 'empleado_id',
                        visible: false,
                    },
                    {
                        data: 'Empleado',
                        name: 'Empleado',
                    },
                    {
                        data: 'Tipo de usuario_id',
                        name: 'Tipo de usuario_id',
                        visible: false,
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
                        name: 'Opciones',
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
                        text: '<i class="fa fa-plus"></i> Registrar Usuario',
                        className: 'btn btn-sm btn-primary bg-danger mx-1',
                        action: () => searchOption('registerUser').onClick(),
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
                    '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" onclick="searchOption(\'' +
                    'editUser' + '\').onClick(this)" data-user=\'' + JSON.stringify(row) +
                    '\'><i class="fa fa-lg fa-fw fa-pen"></i></button> ';
                var btnDelete =
                    '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" onclick="searchOption(\'' +
                    'deleteUser' + '\').onClick(' +
                    row.id + ')"><i class="fa fa-lg fa-fw fa-trash"></i></button> ';

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
