@props(['route', 'title', 'size', 'field', 'quetion', 'modalId'])


<x-adminlte-modal id="{{ $modalId }}" :title="$title" :size="$size" theme="danger" icon="fas fa-trash"
    v-centered static-backdrop>
    {{ $quetion }}
    <form action="{{ $route }}" method="POST">
        @csrf
        @method('DELETE')
        <x-adminlte-input name="{{ $field['name'] }}" type="hidden" />
        <x-slot name="footerSlot">
            <x-adminlte-button class="mr-auto" theme="danger" label="Eliminar" type="submit" />
            <x-adminlte-button theme="secondary" label="Cerrar" data-dismiss="modal" />
        </x-slot>
    </form>
</x-adminlte-modal>
