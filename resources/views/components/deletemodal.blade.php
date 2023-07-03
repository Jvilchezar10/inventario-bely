@props(['route', 'route_id', 'title', 'size', 'quetion', 'modalId'])


<x-adminlte-modal id="{{ $modalId }}" :title="$title" :size="$size" theme="danger" icon="fas fa-trash"
    v-centered static-backdrop>
    {{ $quetion }}
    <form id="{{ $route_id }}" action="{{ $route }}" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-footer">
            <x-adminlte-button class="mr-auto" theme="danger" label="Eliminar" type="submit" />
            <x-adminlte-button theme="secondary" label="Cerrar" data-dismiss="modal" />
        </div>
    </form>
</x-adminlte-modal>
