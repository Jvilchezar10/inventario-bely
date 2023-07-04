@props(['columns', 'data', 'id'])

<div class="table-responsive">
    <form id="deleteForm" action="/eliminar-registros" method="POST">
        @csrf
        <table id="{{ $id }}" class="table" style="width:100%">
            <thead>
                <tr>
                    <th></th> <!-- Columna para las casillas de verificación -->
                    @foreach ($columns as $column)
                        <th>{{ $column }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $row)
                    <tr>
                        <td>
                            <input type="checkbox" name="selectedRows[]" value="{{ $row->id }}">
                        </td>
                        @foreach ($row as $column)
                            <td>{{ $column }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="btn btn-danger">Eliminar seleccionados</button>
    </form>
</div>
