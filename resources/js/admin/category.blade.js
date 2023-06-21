function editCategory(id) {
    // Lógica para cargar los datos de la categoría en el formulario de edición
    // y abrir el modal de edición
    $('#editModal').modal('show');
}

function deleteCategory(id) {
    // Lógica para mostrar el mensaje de confirmación de eliminación
    // y abrir el modal de eliminacións
    $('#deleteModal').modal('show');
}

function viewDetails(id) {
    // Lógica para cargar los detalles de la categoría y abrir el modal de detalles
    $('#detailsModal').modal('show');
}
$(function() {

    var table = $('#categoriesTable').DataTable({
        // data: data,
        columns: [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'state',
                name: 'state'
            },
            {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'updated_at',
                name: 'updated_at'
            },
            {
                data: 'id',
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function(id) {
                    return generateButtons(id);
                }
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
            processing: '<i class="fa fa-spinner fa-spin"></i> Cargando...',
            lengthMenu: 'Mostrar _MENU_', //registros por página
            zeroRecords: 'No se encontraron registros',
            info: 'Mostrando página _PAGE_ de _PAGES_',
            infoEmpty: 'No hay registros disponibles',
            infoFiltered: '(filtrado de _MAX_ registros totales)',
            search: 'Buscar:',
            paginate: {
                first: 'Primero',
                last: 'Último',
                next: '→',
                previous: '←'
            }
        },
        dom: "<'row'<'col-auto'l><'col'B><'col-auto'f>>" +
            "<'row'<'col-sm-12't>>" +
            "<'row'<'col-sm-12 col-md-5'><'col-sm-12 col-md-7'p>>",

        buttons: [{
                extend: 'copy',
                text: '<i class="fas fa-sticky-note"></i>', //Copiar
                className: 'btn btn-sm btn-default bg-primary mx-1'
            },
            {
                extend: 'csv',
                text: '<i class="fas fa-file-csv "></i>', //CSV
                className: 'btn btn-sm btn-default bg-primary mx-1'
            },
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i>', //Excel
                className: 'btn btn-sm btn-default bg-primary mx-1'
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i>', //PDF
                className: 'btn btn-sm btn-default bg-primary mx-1'
            },
            {
                extend: 'print',
                text: '<i class="fa fa-print"></i>',
                className: 'btn btn-sm btn-default bg-primary mx-1'
            },
            {
                text: '<i class="fa fa-plus"></i> Registrar Categoría',
                className: 'btn btn-sm btn-primary bg-danger mx-1',
                action: function() {
                    // Lógica para registrar una categoría
                    $('#registerModal').modal('show');
                }
            },
        ],
        responsive: true,
        // destroy: true,
        paging: true,
        // stateSave: true,
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

    function refreshDataTable() {
        $.ajax({
            url: '{{ route('categories.data') }}',
            type: 'POST',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.data) {
                    // console.log('Datos encontrados: \n ' + response.data);
                    initializeDataTable(response.data);
                } else {
                    console.log('No se encontraron datos de categorías.');
                }
            },
            error: function(xhr, textStatus, error) {
                console.log('Error al obtener los datos de categorías: ' + error);
            }
        });
    }

    function generateButtons(categoryId) {
        var btnEdit =
            '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" onclick="editCategory(' +
            categoryId + ')"><i class="fa fa-lg fa-fw fa-pen"></i></button> ';
        var btnDelete =
            '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" onclick="deleteCategory(' +
            categoryId + ')"><i class="fa fa-lg fa-fw fa-trash"></i></button> ';
        var btnDetails =
            '<button class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details" onclick="viewDetails(' +
            categoryId + ')"><i class="fa fa-lg fa-fw fa-eye"></i></button> ';

        return '<nobr>' + btnEdit + btnDelete + btnDetails + '</nobr>';
    }

    refreshDataTable();

    setInterval(refreshDataTable, 10000);
});