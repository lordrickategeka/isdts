@php
    $type = $field['field_type'] ?? 'text';
    $options = preg_split('/\r?\n/', $field['options'] ?? '');
@endphp

{{-- TEXT INPUT --}}
@if ($type === 'text')
    <input type="text"
           class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-100"
           placeholder="{{ $field['placeholder'] ?? '' }}"
           disabled>

{{-- TEXTAREA --}}
@elseif ($type === 'textarea')
    <textarea rows="2"
              class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-100"
              placeholder="{{ $field['placeholder'] ?? '' }}"
              disabled></textarea>

{{-- SELECT --}}
@elseif ($type === 'select')
    <select class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-100" disabled>
        @foreach ($options as $option)
            @if (trim($option) !== '')
                <option>{{ $option }}</option>
            @endif
        @endforeach
    </select>

{{-- RADIO GROUP --}}
@elseif ($type === 'radio')
    <div class="flex flex-col gap-1">
        @foreach ($options as $option)
            @if (trim($option) !== '')
                <label class="inline-flex items-center text-gray-700">
                    <input type="radio" disabled>
                    <span class="ml-2">{{ $option }}</span>
                </label>
            @endif
        @endforeach
    </div>

{{-- CHECKBOX GROUP --}}
@elseif ($type === 'checkbox')
    <div class="flex flex-col gap-1">
        @foreach ($options as $option)
            @if (trim($option) !== '')
                <label class="inline-flex items-center text-gray-700">
                    <input type="checkbox" disabled>
                    <span class="ml-2">{{ $option }}</span>
                </label>
            @endif
        @endforeach
    </div>

{{-- DATE --}}
@elseif ($type === 'date')
    <input type="date"
           class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-100"
           disabled>

{{-- NUMBER --}}
@elseif ($type === 'number')
    <input type="number"
           class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-100"
           disabled>

{{-- EMAIL --}}
@elseif ($type === 'email')
    <input type="email"
           class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-100"
           disabled>

{{-- TEL --}}
@elseif ($type === 'tel')
    <input type="tel"
           class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-100"
           disabled>

{{-- URL --}}
@elseif ($type === 'url')
    <input type="url"
           class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-100"
           disabled>

{{-- FILE --}}
@elseif ($type === 'file')
    <input type="file"
           class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-100"
           disabled>
@endif

{{-- HELP TEXT --}}
@if (!empty($field['help_text']))
    <p class="text-xs text-gray-500 mt-1">{{ $field['help_text'] }}</p>
@endif
