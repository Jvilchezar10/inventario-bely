@props(['route', 'fields', 'title', 'size', 'modalId'])

@php
    $label_class = 'text-lightblue';
@endphp

<x-adminlte-modal id="{{ $modalId }}" :title="$title" :size="$size" theme="pink" icon="fas fa-edit"
    v-centered static-backdrop>
    <form action="{{ $route }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Recorre una lista de inputs --}}
        @foreach ($fields as $field)
            @if ($field['type'] === 'select')
                {{-- Need  name, placeholder, options, --}}
                <x-adminlte-select2 name="{{ $field['name'] }}" igroup-size="lg" label-class="text-lightblue"
                    data-placeholder="{{ $field['placeholder'] }}">
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-gradient-info">
                            <i class="fas fa-car-side"></i>
                        </div>
                    </x-slot>
                    <x-adminlte-options :options="$field['options']" empty-option />
                </x-adminlte-select2>
            @elseif ($field['type'] === 'select2_with_search')
                {{-- Need: name, label, placeholder, options, --}}
                {{-- Need: name, label, placeholder, options, --}}
                <x-adminlte-select2 id="{{ $field['name'] }}" name="{{ $field['name'] }}" label="{{ $field['label'] }}"
                    label-class="{{ $label_class }}" igroup-size="sm" data-placeholder="{{ $field['placeholder'] }}">
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-gradient-pink">
                            <i class="fas fa-square-list"></i>
                        </div>
                    </x-slot>
                </x-adminlte-select2>
            @elseif ($field['type'] === 'radio')
                {{-- Need  name, value, label, --}}
                <div class="form-group">
                    <label class="control-label">{{ $field['label'] }}:</label>
                    @foreach ($field['options'] as $option)
                        <div class="radio">
                            <label>
                                <input type="radio" name="{{ $field['name'] }}" value="{{ $option['value'] }}">
                                {{ $option['label'] }}
                            </label>
                        </div>
                    @endforeach
                </div>
            @elseif ($field['type'] === 'input')
                {{-- Need  name, placeholder, label, --}}
                <x-adminlte-input name="{{ $field['name'] }}" label="{{ $field['label'] }}"
                    placeholder="{{ $field['placeholder'] }}" label-class="text-lightblue" disable-feedback />
            @elseif ($field['type'] === 'datetime')
                {{-- Need  name, config, placeholder, label, --}}
                @php
                    $configDatetime = ['only_date' => ['format' => 'DD/MM/YYYY'], 'only_hour' => ['format' => 'HH:mm'], 'date_hour' => ['format' => 'DD/MM/YYYY HH:mm']];
                    $configDatetime = $configDatetime[$field['config']];
                @endphp
                <x-adminlte-input-date name="{{ $field['name'] }}" :config="$configDatetime"
                    placeholder="{{ $field['placeholder'] }}" label="{{ $field['label'] }}"
                    label-class="{{ $label_class }}">
                    <x-slot name="appendSlot">
                        <x-adminlte-button theme="outline-primary" icon="fas fa-lg fa-calendar-days"
                            title="{{ $field['title'] }}" />
                    </x-slot>
                </x-adminlte-input-date>
            @elseif ($field['type'] === 'number')
                <x-adminlte-input name="{{ $field['name'] }}" label="{{ $field['label'] }}"
                    placeholder="{{ $field['placeholder'] }}" type="number" igroup-size="sm" min=0 max=500
                    step="0.01" label-class="{{ $label_class }}">
                    <x-slot name="appendSlot">
                        <div class="input-group-text bg-pink">
                            <i class="fas fa-hashtag"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
            @elseif ($field['type'] === 'long_text')
                {{-- With prepend slot, sm size and label --}}
                <x-adminlte-textarea name="{{ $field['name'] }}" label="{{ $field['label'] }}" rows=4
                    label-class="{{ $label_class }}" igroup-size="sm" placeholder="{{ $field['placeholder'] }}">
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-pink">
                            <i class="fas fa-lg fa-file-alt text-warning"></i>
                        </div>
                    </x-slot>
                </x-adminlte-textarea>
            @else
                {{-- When type of field is hidden --}}
                <x-adminlte-input name="{{ $field['name'] }}" type="hidden" />
            @endif
        @endforeach
        <x-slot name="footerSlot">
            <x-adminlte-button class="mr-auto" theme="danger" label="Actualizar" type="submit" />
            <x-adminlte-button theme="secondary" label="Cerrar" data-dismiss="modal" />
        </x-slot>
    </form>
</x-adminlte-modal>
