@extends('adminlte::page')

@section('content_header')
    <h1 class="m-0 text-dark">Categorías</h1>
@stop

@section('content')
    <div id="error-message" class="alert alert-danger" style="display: none;"></div>
    @if ($message = Session::get('success'))
        <div id="success-message" class="alert alert-success">
            {{ $message }}
        </div>
    @endif

    <x-adminlte-card title="Lista de Categorías" theme="pink" icon="fas fa-tags" class="elevation-3" maximizable>
        <x-datatable :columns=$columns :data=$data id="categoriesTable" />
    </x-adminlte-card>

    {{-- Modales --}}
    <x-register-modal formId="registerCategoryForm" :fields="[
        ['name' => 'i_name', 'label' => 'Categoría', 'placeholder' => 'Ingrese la categoría', 'type' => 'input'],
        [
            'name' => 'i_state',
            'label' => 'Estado',
            'type' => 'radio',
            'options' => [
                ['value' => 'vigente', 'label' => 'Vigente'],
                ['value' => 'descontinuado', 'label' => 'Descontinuado'],
            ],
        ],
    ]" title='Añadir Categoria' size='md'
        modalId='registerCategoryModal' onClick="registerCategory()" />

    <x-edit-modal formId="updateCategoryForm" :fields="[
        ['name' => 'e_id', 'type' => 'hidden'],
        ['name' => 'e_name', 'label' => 'Categoría', 'placeholder' => 'Ingrese la categoría', 'type' => 'input'],
        [
            'name' => 'e_state',
            'label' => 'Estado',
            'type' => 'radio',
            'options' => [
                ['value' => 'vigente', 'label' => 'Vigente'],
                ['value' => 'descontinuado', 'label' => 'Descontinuado'],
            ],
        ],
    ]" title='Editar Categoria' size='md'
        modalId='editCategoryModal' onClick="updateCategory()" />

    <x-delete-modal title='Eliminar Categoría' size='md' modalId='deleteCategoryModal' formId="destroyCategoryForm"
        quetion='¿Está seguro que desea eliminar la categoria?' :field="['name' => 'd_id']" onClick="deleteCategory()" />

@endsection

@section('js')
    <script>
        var categoryId = 0;
        var categorysDataRoute = '{{ route('categories.data') }}';
        var csrfToken = '{{ csrf_token() }}';
    </script>
    <script src="{{ asset('js/toast.js') }}"></script>
    <script>
        document.getElementById("registerCategoryForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Detiene el envío del formulario predeterminado
        });
        document.getElementById("updateCategoryForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Detiene el envío del formulario predeterminado
        });
        document.getElementById("destroyCategoryForm").addEventListener("submit", function(event) {
            event.preventDefault(); // Detiene el envío del formulario predeterminado
        });

        function registerCategory() {
            // Obtener los datos del formulario
            var formData = $('#registerCategoryModal form').serialize();
            // Realizar la petición AJAX para el registro de la categoría
            $.ajax({
                url: '{{ route('categories.store') }}',
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
                    $('#registerCategoryModal').modal('hide');
                },
                error: function(xhr, textStatus, errorThrown) {
                    // Si se produce un error en la solicitud
                    var errorContainer = $('#error-message');

                    if (xhr.status === 403) {
                        errorContainer.text('Acceso denegado').show();
                    } else {
                        showCustomToast({
                            title: 'Elimación denegada',
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

        function updateCategory() {
            // Obtener los datos del formulario
            var formData = $('#editCategoryModal form').serialize();
            var id = $('#editCategoryModal input[name="e_id"]').val();

            event.preventDefault();
            // Realizar la petición AJAX para la actualización de la categoría
            $.ajax({
                url: '{{ route('categories.update', ['id' => ':id']) }}'.replace(':id', id),
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
                    $('#editCategoryModal').modal('hide');
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

        function deleteCategory() {
            var id = $('#deleteCategoryModal input[name="d_id"]').val();

            // Realizar la petición AJAX para la eliminación de la categoría
            $.ajax({
                url: '{{ route('categories.destroy', ['id' => ':id']) }}'.replace(':id', id),
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
                    $('#deleteCategoryModal').modal('hide');
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

        function updateCategoryIDs() {
            // Realizar la petición AJAX para actualizar los IDs de las categorías
            $.ajax({
                url: '{{ route('categories.update-ids') }}',
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Mostrar mensaje de éxito o realizar acciones adicionales si es necesario
                    console.log('IDs de categoría actualizados con éxito.');
                    // Actualizar la vista o realizar otras acciones necesarias
                },
                error: function(xhr, textStatus, error) {
                    console.error('Error al actualizar los IDs de categoría:', error);
                    // Mostrar mensaje de error o realizar acciones adicionales si es necesario
                }
            });
        }


        function openRegisterModal() {
            $('#registerCategoryModal').modal('show');
        }

        function openEditModal(button) {
            var category = JSON.parse(button.getAttribute('data-product')); // Analizar la cadena JSON en un objeto

            // Asignar los valores a los campos del modal
            $('#editCategoryModal input[name="e_id"]').val(category.id);
            $('#editCategoryModal input[name="e_name"]').val(category.nombre);
            $('#updateCategoryForm input[name="e_state"][value="vigente"]').prop('checked', category.estado === 'vigente');
            $('#updateCategoryForm input[name="e_state"][value="descontinuado"]').prop('checked', category.estado ===
                'descontinuado');
            $('#editCategoryModal').modal('show'); // Invocar al modal de edición
        }

        function openDeleteModal(id) {
            $('#deleteCategoryModal input[name="d_id"]').val(id);
            // abrir modal
            $('#deleteCategoryModal').modal('show');
        }

        $(function() {

            var table = $('#categoriesTable').DataTable({
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
                        text: '<i class="fa fa-plus"></i> Registrar Categoria',
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

            function refreshCategoryDataTable() {
                $.ajax({
                    url: categorysDataRoute,
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

            refreshCategoryDataTable();

            setInterval(refreshCategoryDataTable, 5000);
        });
    </script>
@endsection
