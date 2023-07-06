@props(['title', 'size', 'quetion', 'onClick', 'modalId'])


<x-adminlte-modal id="{{ $modalId }}" :title="$title" :size="$size" theme="danger" icon="fas fa-trash"
    v-centered static-backdrop>
    {{ $quetion }}
    <x-slot name="footerSlot">
        <x-adminlte-button class="mr-auto" theme="danger" label="Eliminar" onclick="{{ $onClick }}" />
        <x-adminlte-button theme="secondary" label="Cerrar" data-dismiss="modal" />
    </x-slot>
</x-adminlte-modal>
