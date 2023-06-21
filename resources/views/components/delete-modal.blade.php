@props(['formId', 'title', 'size', 'field', 'quetion', 'modalId', 'onClick'])


<x-adminlte-modal id="{{ $modalId }}" :title="$title" :size="$size" theme="danger" icon="fas fa-trash"
    v-centered static-backdrop>
    {{ $quetion }}
    <form id="{{ $formId }}" method="POST">
        @csrf
        @method('DELETE')
        <x-adminlte-input name="{{ $field['name'] }}" type="hidden" />
        <x-slot name="footerSlot">

        </x-slot>
        <div class="modal-footer">
            <x-adminlte-button class="mr-auto" theme="danger" label="Eliminar" type="submit"
                onclick="{{ $onClick }}" />
            <x-adminlte-button theme="secondary" label="Cerrar" data-dismiss="modal" />
        </div>
    </form>
</x-adminlte-modal>
