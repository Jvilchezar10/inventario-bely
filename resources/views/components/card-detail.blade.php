@props(['fields', 'formId', 'cardId', 'title', 'onClick'])

@php
    $label_class = 'text-lightblue';
@endphp

<x-adminlte-card title="{{ $title }}" id="{{ $cardId }}" theme="pink" icon="fas fa-shopping-cart"
    class="elevation-3" maximizable>
    <form id="{{ $formId }}" method="POST">
        @csrf
        <div class="row">
            @foreach ($fields as $field)
                @if ($field['type'] === 'select')
                    {{-- Need  name, placeholder, options, --}}
                    <div class="{{ $field['inputClass'] }}">
                        <x-adminlte-select2  id="{{ $field['name'] }}" name="{{ $field['name'] }}" igroup-size="sm" label-class="{{ $label_class }}"
                            label="{{ $field['label'] }}" data-placeholder="{{ $field['placeholder'] }}">
                            <x-slot name="prependSlot">
                                <div class="input-group-text bg-gradient-pink">
                                    <i class="fas fa-square-list"></i>
                                </div>
                            </x-slot>
                            <x-adminlte-options :options="$field['options']" empty-option />
                        </x-adminlte-select2>
                    </div>
                @elseif ($field['type'] === 'select2_with_search')
                    {{-- Need: name, label, placeholder, options, --}}
                    <div class="{{ $field['inputClass'] }}">
                        <x-adminlte-select2 id="{{ $field['name'] }}" name="{{ $field['name'] }}"
                            label="{{ $field['label'] }}" label-class="{{ $label_class }}" igroup-size="sm"
                            data-placeholder="{{ $field['placeholder'] }}">
                            <x-slot name="prependSlot">
                                <div class="input-group-text bg-gradient-pink">
                                    <i class="fas fa-square-list"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-select2>
                    </div>
                @elseif ($field['type'] === 'radio')
                    {{-- Need  name, value, label, --}}
                    <div class="{{ $field['inputClass'] }}">
                        <div class="form-group">
                            <label class="control-label">{{ $field['label'] }}:</label>
                            @foreach ($field['options'] as $option)
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="{{ $field['name'] }}"
                                            value="{{ $option['value'] }}">
                                        {{ $option['label'] }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @elseif ($field['type'] === 'input')
                    @php
                        if (!isset($field['type_input'])) {
                            $field['type_input'] = 'text';
                        }
                    @endphp
                    {{-- Need  name, placeholder, label, --}}
                    <div class="{{ $field['inputClass'] }}">
                        @if (isset($field['required']))
                            <x-adminlte-input name="{{ $field['name'] }}" label="{{ $field['label'] }}"
                                placeholder="{{ $field['placeholder'] }}" label-class="{{ $label_class }}"
                                disable-feedback autofocus autocomplete="{{ $field['name'] }}"
                                type="{{ $field['type_input'] }}" required />
                        @else
                            <x-adminlte-input name="{{ $field['name'] }}" label="{{ $field['label'] }}"
                                placeholder="{{ $field['placeholder'] }}" label-class="{{ $label_class }}"
                                disable-feedback autofocus autocomplete="{{ $field['name'] }}"
                                type="{{ $field['type_input'] }}" />
                        @endif
                    </div>
                @elseif ($field['type'] === 'datetime')
                    {{-- Need  name, config, placeholder, label, --}}
                    @php
                        $configDatetime = ['only_date' => ['format' => 'DD/MM/YYYY'], 'only_hour' => ['format' => 'HH:mm'], 'date_hour' => ['format' => 'DD/MM/YYYY HH:mm']];
                        $configDatetime = $configDatetime[$field['config']];
                    @endphp
                    <div class="{{ $field['inputClass'] }}">
                        <x-adminlte-input-date name="{{ $field['name'] }}" :config="$configDatetime"
                            placeholder="{{ $field['placeholder'] }}" label="{{ $field['label'] }}"
                            label-class="{{ $label_class }}">
                            <x-slot name="appendSlot">
                                <x-adminlte-button theme="outline-primary" icon="fas fa-lg fa-calendar-days"
                                    title="{{ $field['title'] }}" />
                            </x-slot>
                        </x-adminlte-input-date>
                    </div>
                @elseif ($field['type'] === 'number')
                    <div class="{{ $field['inputClass'] }}">
                        <x-adminlte-input name="{{ $field['name'] }}" label="{{ $field['label'] }}"
                            placeholder="{{ $field['placeholder'] }}" type="number" igroup-size="sm" min=0 max=500
                            step="0.01" label-class="{{ $label_class }}" autofocus
                            autocomplete="{{ $field['name'] }}">
                            <x-slot name="appendSlot">
                                <div class="input-group-text bg-pink">
                                    <i class="fas fa-hashtag"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                    </div>
                @elseif ($field['type'] === 'long_text')
                    <div class="{{ $field['inputClass'] }}">
                        <x-adminlte-textarea name="{{ $field['name'] }}" label="{{ $field['label'] }}" rows=4
                            label-class="{{ $label_class }}" igroup-size="sm"
                            placeholder="{{ $field['placeholder'] }}" autofocus autocomplete="{{ $field['name'] }}">
                            <x-slot name="prependSlot">
                                <div class="input-group-text bg-pink">
                                    <i class="fas fa-lg fa-file-alt"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-textarea>
                    </div>
                @endif
            @endforeach
        </div>
        <br />
        <div class="row">
            @foreach ($fields as $field)
                @if (isset($field['typeB']) && $field['typeB'] === 'button')
                    <div class="{{ $field['inputClass'] }}">
                        <div class="float-right">
                            <x-adminlte-button theme="{{ $field['class'] }}" label="{{ $field['label'] }}"
                                onclick="{{ $field['onClick'] }}" id="{{ $field['label'] }}"
                                type="{{ $field['type'] }}" />
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </form>
</x-adminlte-card>
