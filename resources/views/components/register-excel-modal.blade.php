@props(['route', 'field', 'title', 'modalId'])

<x-adminlte-modal id="{{ $modalId }}" title="{{ $title }}" size="md" theme="info" icon="fas fa-file-excel"
    v-centered static-backdrop>
    <form action="{{ $route }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
            <x-adminlte-input-file name="{{ $field['name'] }}" label="{{ $field['label'] }}" accept=".xlsx, .xls"
                placeholder="{{ $field['placeholder'] }}" disable-feedback igroup-size="sm">
                <x-slot name="appendSlot">
                    <x-adminlte-button theme="primary" label="Importar" type="submit"/>
                </x-slot>
            </x-adminlte-input-file>
        </div>
    </form>
</x-adminlte-modal>


