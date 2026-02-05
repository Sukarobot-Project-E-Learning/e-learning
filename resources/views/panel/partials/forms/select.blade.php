{{--
    Select Input Component
    
    Props:
    - name: string (required) - Select name attribute
    - label: string - Label text
    - options: array - Array of options [value => label] or [{value, label}]
    - value: string - Selected value
    - required: bool - Whether field is required
    - disabled: bool - Whether field is disabled
    - id: string - Custom ID (defaults to name)
    - placeholder: string - Placeholder option text
--}}

@php
    $inputId = $id ?? $name;
    $selectedValue = $value ?? old($name);
    $isRequired = $required ?? false;
@endphp

<div class="form-group {{ $errors->has($name) ? 'has-error' : '' }}">
    @if(!empty($label))
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="{{ $inputId }}">
            {{ $label }}
            @if($isRequired)
                <span class="text-orange-500">*</span>
            @endif
        </label>
    @endif

    <select 
        name="{{ $name }}" 
        id="{{ $inputId }}"
        @if($isRequired) required @endif
        @if($disabled ?? false) disabled @endif
        class="block w-full px-4 py-3.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-orange-500 focus:ring-0 transition-colors appearance-none bg-no-repeat bg-right"
        style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 24 24%27 stroke=%27%23F97316%27%3E%3Cpath stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%272%27 d=%27M19 9l-7 7-7-7%27/%3E%3C/svg%3E'); background-position: right 1rem center; background-size: 1.25rem;"
    >
        @if(!empty($placeholder))
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach($options as $optionValue => $optionLabel)
            @if(is_array($optionLabel))
                <option value="{{ $optionLabel['value'] }}" {{ $selectedValue == $optionLabel['value'] ? 'selected' : '' }}>
                    {{ $optionLabel['label'] }}
                </option>
            @else
                <option value="{{ $optionValue }}" {{ $selectedValue == $optionValue ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endif
        @endforeach
    </select>

    @error($name)
        <p class="mt-2 text-sm text-red-500 flex items-center gap-1">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            {{ $message }}
        </p>
    @enderror
</div>
