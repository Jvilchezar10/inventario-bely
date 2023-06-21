@extends('adminlte::page')

@section('content_header')
    <h1 class="m-0 text-dark">Empleados</h1>
@stop
{{-- @section('css')
    <link rel="stylesheet" href="{{ asset('css/category.css') }}">
@stop --}}

@section('content')
    <div id="error-message" class="alert alert-danger" style="display: none;"></div>
    @if ($message = Session::get('success'))
        <div id="success-message" class="alert alert-success">
            {{ $message }}
        </div>
    @endif

    <x-adminlte-card title="Lista de Empleados" theme="pink" icon="fas fa-users" class="elevation-3" maximizable>
        <x-datatable :columns=$columns :data=$data id="employeesTable" />
    </x-adminlte-card>

    <!-- Modales -->
    <x-register-modal formId="registerEmployeeForm" :fields="[
        [
            'name' => 'i_cod_emp',
            'label' => 'Cod Empleado',
            'placeholder' => '@EMP0001',
            'type' => 'input',
        ],
        ['name' => 'i_name', 'label' => 'Nombre', 'placeholder' => 'Ingrese el nombre', 'type' => 'input'],
        ['name' => 'i_last_name', 'label' => 'Apellido', 'placeholder' => 'Ingrese el apellido', 'type' => 'input'],
        [
            'name' => 'i_phone',
            'label' => 'Número de celular',
            'placeholder' => 'Ingrese el número de celular',
            'type' => 'input',
        ],
        [
            'name' => 'i_correo',
            'label' => 'Correo',
            'placeholder' => 'Ingrese el correo',
            'type' => 'input',
        ],
        [
            'name' => 'i_state',
            'label' => 'Estado',
            'type' => 'radio',
            'options' => [['value' => 'vigente', 'label' => 'Vigente'], ['value' => 'retirado', 'label' => 'Retirado']],
        ],
    ]" title="Registrar Empleado" size="md"
        modalId="registerEmployeeModal" onClick="registerEmployee()" />

    <x-edit-modal formId="updateEmployeeForm" :fields="[
        ['name' => 'e_id', 'type' => 'hidden'],
        [
            'name' => 'e_cod_emp',
            'label' => 'Cod Empleado',
            'placeholder' => 'Ingrese el codigo empleado',
            'type' => 'input',
        ],
        ['name' => 'e_name', 'label' => 'Nombre', 'placeholder' => 'Ingrese el nombre', 'type' => 'input'],
        ['name' => 'e_last_name', 'label' => 'Apellido', 'placeholder' => 'Ingrese el apellido', 'type' => 'input'],
        [
            'name' => 'e_phone',
            'label' => 'Número de celular',
            'placeholder' => 'Ingrese el número de celular',
            'type' => 'input',
        ],
        [
            'name' => 'e_correo',
            'label' => 'Correo',
            'placeholder' => 'Ingrese el correo',
            'type' => 'input',
        ],
        [
            'name' => 'e_state',
            'label' => 'Estado',
            'type' => 'radio',
            'options' => [['value' => 'vigente', 'label' => 'Vigente'], ['value' => 'retirado', 'label' => 'Retirado']],
        ],
    ]" title='Editar Empleado' size='md'
        modalId='editEmployeeModal' onClick="updateEmployee()" />

    <x-delete-modal title='Eliminar Empleado' size='md' modalId='deleteEmployeeModal' formId="destroyEmployeeForm"
        quetion='¿Está seguro que desea eliminar el producto?' :field="['name' => 'd_id']" onClick="deleteEmployee()" />

@endsection

@section('js')
    <script>
        var employeeId = 0;
        var employeesDataRoute = '{{ route('employees.data') }}';
        var csrfToken = '{{ csrf_token() }}';
    </script>
    <script src="{{ asset('js/toast.js') }}"></script>
    <script>
        document.getElementById("registerEmployeeForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Detiene el envío del formulario predeterminado
        });

        document.getElementById("updateEmployeeForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Detiene el envío del formulario predeterminado
        });

        document.getElementById("destroyEmployeeForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Detiene el envío del formulario predeterminado
        });

        function registerEmployee() {
            // Obtener los datos del formulario
            var formData = $('#registerEmployeeModal form').serialize();
            // Evitar que se envíe el formulario y se actualice la página
            event.preventDefault();
            // Realizar la petición AJAX para el registro de el empleado
            $.ajax({
                url: '{{ route('employees.store') }}',
                type: 'POST',
                dataType: 'json',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    // Mostrar mensaje de éxito o realizar acciones adicionales si es necesario
                    console.log('Empleado registrado con éxito.');
                    //
                    showCustomToast({
                        title: 'Registro exitoso',
                        body: 'Empleado registrado con éxito.',
                        class: 'bg-success',
                        icon: 'fas fa-check-circle',
                        close: false,
                        autohide: true,
                        delay: 5000
                    });
                    //
                    $('#registerEmployeeModal').modal('hide');
                },
                error: function(xhr, textStatus, error) {
                    // Mostrar mensaje de error o realizar acciones adicionales si es necesario
                    console.log('Error al registrar el empleado: ' + error);
                }
            });
        }

        function updateEmployee() {
            // Obtener los datos del formulario
            var formData = $('#editEmployeeModal form').serialize();
            var id = $('#editEmployeeModal input[name="e_id"]').val();

            // Realizar la petición AJAX para la actualización de el empleado
            $.ajax({
                url: '{{ route('employees.update', ['id' => ':id']) }}'.replace(':id', id),
                type: 'POST',
                dataType: 'json',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    // Mostrar mensaje de éxito o realizar acciones adicionales si es necesario
                    console.log('Empleado actualizado con éxito.');
                    // Cerrar el modal después de la operación exitosa
                    showCustomToast({
                        title: 'Actualización exitoso',
                        body: 'Empleado actualizado con éxito.',
                        class: 'bg-success',
                        icon: 'fas fa-check-circle',
                        close: false,
                        autohide: true,
                        delay: 5000
                    });
                    //
                    $('#editEmployeeModal').modal('hide');
                    // Actualizar la tabla de categorías
                },
                error: function(xhr, textStatus, error) {
                    // Mostrar mensaje de error o realizar acciones adicionales si es necesario
                    console.log('Error al actualizar el empleado: ' + error);
                }
            });
        }

        function deleteEmployee(id) {
            var id = $('#deleteEmployeeModal input[name="d_id"]').val();

            // Realizar la petición AJAX para la eliminación de el empleado
            $.ajax({
                url: '{{ route('employees.destroy', ['id' => ':id']) }}'.replace(':id', id),
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
                    console.log('Empleado eliminado oon éxito.');
                    //
                    showCustomToast({
                        title: 'Elimación exitosa',
                        body: 'Empleado eliminada con éxito.',
                        class: 'bg-success',
                        icon: 'fas fa-check-circle',
                        close: false,
                        autohide: true,
                        delay: 5000
                    });
                    //
                    $('#deleteEmployeeModal').modal('hide');
                },
                error: function(xhr, textStatus, error) {
                    // Mostrar mensaje de error o realizar acciones adicionales si es necesario
                    console.log('Error al eliminar el empleado: ' + error);
                }
            });
        }

        function openRegisterModal() {
            $('#registerEmployeeModal').modal('show');
        }

        function openEditModal(button) {
            var employee = JSON.parse(button.getAttribute('data-employee')); // Analizar la cadena JSON en un objeto

            employeeId = employee.id;
            $('#editEmployeeModal input[name="e_cod_producto"]').val(employee['cod emp']);
            $('#editEmployeeModal input[name="e_name"]').val(employee.nombre);
            $('#editEmployeeModal input[name="e_last_name"]').val(employee.apellido);
            $('#editEmployeeModal input[name="e_phone"]').val(employee['número de celular']);
            $('#editEmployeeModal input[name="e_correo"]').val(employee.correo);
            $('#updateEmployeeForm input[name="e_state"][value="vigente"]').prop('checked', employee.estado === 'vigente');
            $('#updateEmployeeForm input[name="e_state"][value="retirado"]').prop('checked', employee.estado ===
                'retirado');
            $('#editEmployeeModal').modal('show');
        }

        function openDeleteEmployee(id) {
            // Lógica para mostrar el mensaje de confirmación de eliminación
            $('#deleteEmployeeModal input[name="d_id"]').val(id);
            // y abrir el modal de eliminacións
            $('#deleteEmployeeModal').modal('show');
        }

        $(function() {

            var table = $('#employeesTable').DataTable({
                columns: [{
                        data: 'id',
                        name: 'id',
                        visible: false,
                    },
                    {
                        data: 'cod emp',
                        name: 'cod emp'
                    },
                    {
                        data: 'nombre',
                        name: 'nombre'
                    },
                    {
                        data: 'apellido',
                        name: 'apellido'
                    },
                    {
                        data: 'número de celular',
                        name: 'número de celular'
                    },
                    {
                        data: 'correo',
                        name: 'correo'
                    },
                    {
                        data: 'estado',
                        name: 'estado',
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
                        text: '<i class="fa fa-plus"></i> Registrar Empleado',
                        className: 'btn btn-sm btn-primary bg-danger mx-1',
                        action: () => openRegisterModal(),
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

            function refreshEmployeeDataTable() {
                $.ajax({
                    url: employeesDataRoute,
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
                            console.log('No se encontraron datos de Empleados.');
                        }
                    },
                    error: function(xhr, textStatus, error) {
                        console.log('Error al obtener los datos de Empleados: ' + error);
                    }
                });
            }

            function generateButtons(row) {
                var btnEdit =
                    '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" onclick="openEditModal(this)" data-employee=\'' +
                    JSON.stringify(row) +
                    '\'><i class="fa fa-lg fa-fw fa-pen"></i></button> ';
                var btnDelete =
                    '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" onclick="openDeleteModal(' +
                    row.id +
                    ')"><i class="fa fa-lg fa-fw fa-trash"></i></button> ';

                return '<nobr>' + btnEdit + btnDelete + '</nobr>';
            }

            refreshEmployeeDataTable();

            setInterval(refreshEmployeeDataTable, 5000);
        });
    </script>
@endsection
