@extends('adminlte::page')

@section('content_header')
    <h1 class="m-0 text-dark">Comprobantes</h1>
@stop

@section('content')
    <div id="error-message" class="alert alert-danger" style="display: none;"></div>
    @if ($message = Session::get('success'))
        <div id="success-message" class="alert alert-success">
            {{ $message }}
        </div>
    @endif

    <x-adminlte-card title="Lista de comprobantes" theme="pink" icon="fas fa-tags" class="elevation-3" maximizable>
        <x-datatable :columns=$columns :data=$data id="proofofpaymentsTable" />
    </x-adminlte-card>

    {{-- Modales --}}
    <x-register-modal formId="registerProofofpaymentForm" :fields="[
        [
            'name' => 'i_name',
            'label' => 'Comprobante',
            'placeholder' => 'Ingrese el tipo de comprobante',
            'type' => 'input',
        ],
        [
            'name' => 'i_state',
            'label' => 'Estado',
            'type' => 'radio',
            'options' => [
                ['value' => 'vigente', 'label' => 'Vigente'],
                ['value' => 'descontinuado', 'label' => 'Descontinuado'],
            ],
        ],
    ]" title='Añadir Comprobante' size='md'
        modalId='registerProofofpaymentModal' onClick="registerProofofpayment()" />

    <x-edit-modal formId="updateProofofpaymentForm" :fields="[
        ['name' => 'e_id', 'type' => 'hidden'],
        [
            'name' => 'e_name',
            'label' => 'Comprobantes',
            'placeholder' => 'Ingrese el tipo de comprobante',
            'type' => 'input',
        ],
        [
            'name' => 'e_state',
            'label' => 'Estado',
            'type' => 'radio',
            'options' => [
                ['value' => 'vigente', 'label' => 'Vigente'],
                ['value' => 'descontinuado', 'label' => 'Descontinuado'],
            ],
        ],
    ]" title='Editar Comprobante' size='md'
        modalId='editProofofpaymentModal' onClick="updateProofofpayment()" />

    <x-delete-modal title='Eliminar Comprobante' size='md' modalId='deleteProofofpaymentModal'
        formId="destroyProofofpaymentForm" quetion='¿Está seguro que desea eliminar el comprobante?' :field="['name' => 'd_id']"
        onClick="deleteProofofpayment()" />

@endsection

@section('js')
    <script>
        var proofofpaymentId = 0;
        var proofofpayment_DataRoute = '{{ route('proofofpayments.data') }}';
        var csrfToken = '{{ csrf_token() }}';
    </script>
    <script src="{{ asset('js/toast.js') }}"></script>
    <script>
        document.getElementById("registerProofofpaymentForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Detiene el envío del formulario predeterminado
        });

        document.getElementById("updateProofofpaymentForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Detiene el envío del formulario predeterminado
        });
        document.getElementById("destroyProofofpaymentForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Detiene el envío del formulario predeterminado
        });

        function registerProofofpayment() {
            // Obtener los datos del formulario
            var formData = $('#registerProofofpaymentModal form').serialize();
            // Realizar la petición AJAX para el registro del comprobante
            $.ajax({
                url: '{{ route('proofofpayments.store') }}',
                type: 'POST',
                dataType: 'json',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    // Mostrar mensaje de éxito o realizar acciones adicionales si es necesario
                    console.log('Comprobante registrado con éxito.');
                    // Cerrar el modal después de la operación exitosa
                    showCustomToast({
                        title: 'Registro exitoso',
                        body: 'Comprobante registrada con éxito.',
                        class: 'bg-success',
                        icon: 'fas fa-check-circle',
                        close: false,
                        autohide: true,
                        delay: 5000
                    });
                    $('#registerProofofpaymentModal').modal('hide');
                },
                error: function(xhr, textStatus, errorThrown) {
                    // Si se produce un error en la solicitud
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

        function updateProofofpayment() {
            // Obtener los datos del formulario
            var formData = $('#editProofofpaymentModal form').serialize();
            var id = $('#editProofofpaymentModal input[name="e_id"]').val();

            event.preventDefault();
            // Realizar la petición AJAX para la actualización del comprobante
            $.ajax({
                url: '{{ route('proofofpayments.update', ['id' => ':id']) }}'.replace(':id', id),
                type: 'POST',
                dataType: 'json',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(response) {
                    // Mostrar mensaje de éxito o realizar acciones adicionales si es necesario
                    console.log('Comprobante actualizado con éxito.');
                    // Cerrar el modal después de la operación exitosa
                    showCustomToast({
                        title: 'Actualización exitosa',
                        body: 'Comprobante actualizado con éxito.',
                        class: 'bg-success',
                        icon: 'fas fa-check-circle',
                        close: false,
                        autohide: true,
                        delay: 5000
                    });

                    $('#editProofofpaymentModal').modal('hide');
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

        function deleteProofofpayment() {
            var id = $('#deleteProofofpaymentModal input[name="d_id"]').val();

            // Realizar la petición AJAX para la eliminación del comprobantes
            $.ajax({
                url: '{{ route('proofofpayments.destroy', ['id' => ':id']) }}'.replace(':id', id),
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
                    console.log('Comprobante eliminado con éxito.');
                    // Actualizar la tabla de comprobantes
                    showCustomToast({
                        title: 'Eliminación exitosa',
                        body: 'Comprobante eliminado con éxito.',
                        class: 'bg-success',
                        icon: 'fas fa-check-circle',
                        close: false,
                        autohide: true,
                        delay: 5000
                    });
                    $('#deleteProofofpaymentModal').modal('hide');
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

        function openRegisterModal() {
            $('#registerProofofpaymentModal').modal('show');
        }

        function openEditModal(button) {
            var proofofpayment = JSON.parse(button.getAttribute('data-product')); // Analizar la cadena JSON en un objeto

            // Asignar los valores a los campos del modal
            $('#editProofofpaymentModal input[name="e_id"]').val(proofofpayment.id);
            $('#editProofofpaymentModal input[name="e_name"]').val(proofofpayment.nombre);
            $('#updateProofofpaymentForm input[name="e_state"][value="vigente"]').prop('checked', proofofpayment.estado ===
                'vigente');
            $('#updateProofofpaymentForm input[name="e_state"][value="descontinuado"]').prop('checked', proofofpayment
                .estado ===
                'descontinuado');
            $('#editProofofpaymentModal').modal('show'); // Invocar al modal de edición
        }

        function openDeleteModal(id) {
            $('#deleteProofofpaymentModal input[name="d_id"]').val(id);
            // abrir modal
            $('#deleteProofofpaymentModal').modal('show');
        }

        $(function() {

            var table = $('#proofofpaymentsTable').DataTable({
                columns: [{
                        data: 'id',
                        name: 'id',
                    },
                    {
                        data: 'nombre',
                        name: 'nombre',
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
                        text: '<i class="fa fa-plus"></i> Registrar Comprobante',
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

            function refreshProofofpaymentDataTable() {
                $.ajax({
                    url: proofofpayment_DataRoute,
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

            refreshProofofpaymentDataTable();

            setInterval(refreshProofofpaymentDataTable, 5000);
        });
    </script>
@endsection
