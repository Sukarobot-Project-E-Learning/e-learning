{{--
    Dynamic Select Component
    Select with option to add custom value
    
    Props:
    - name: string (required) - Select name attribute
    - label: string - Label text
    - options: array - Array of options [value => label]
    - value: string - Selected value
    - required: bool - Whether field is required
    - id: string - Custom ID (defaults to name)
    - placeholder: string - Placeholder option text
    - addNewText: string - Text for "add new" option (default: "➕ Tambah Baru...")
    - customPlaceholder: string - Placeholder for custom input
--}}

@php
    $inputId = $id ?? $name;
    $customInputId = $inputId . '_custom';
    $selectedValue = $value ?? old($name);
    $isRequired = $required ?? false;
    $addNewLabel = $addNewText ?? '➕ Tambah Baru...';
    $customInputPlaceholder = $customPlaceholder ?? 'Ketik nilai baru';
    
    // Check if current value is a custom value (not in options)
    $isCustomValue = !empty($selectedValue) && !array_key_exists($selectedValue, $options ?? []);
@endphp

<div class="form-group dynamic-select-wrapper {{ $errors->has($name) ? 'has-error' : '' }}" 
     data-dynamic-select
     data-initial-value="{{ $selectedValue }}"
     data-is-custom="{{ $isCustomValue ? 'true' : 'false' }}">
    
    @if(!empty($label))
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="{{ $inputId }}">
            {{ $label }}
            @if($isRequired)
                <span class="text-orange-500">*</span>
            @endif
        </label>
    @endif

    <!-- Select Wrapper -->
    <div class="select-wrapper" data-select-wrapper>
        <select 
            id="{{ $inputId }}"
            @if(!$isCustomValue) name="{{ $name }}" @endif
            @if($isRequired && !$isCustomValue) required @endif
            class="block w-full px-4 py-3.5 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-orange-500 focus:ring-0 transition-colors appearance-none bg-no-repeat bg-right"
            style="background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 fill=%27none%27 viewBox=%270 0 24 24%27 stroke=%27%23F97316%27%3E%3Cpath stroke-linecap=%27round%27 stroke-linejoin=%27round%27 stroke-width=%272%27 d=%27M19 9l-7 7-7-7%27/%3E%3C/svg%3E'); background-position: right 1rem center; background-size: 1.25rem;"
            data-dynamic-select-dropdown
        >
            @if(!empty($placeholder))
                <option value="">{{ $placeholder }}</option>
            @endif

            @foreach($options ?? [] as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" {{ $selectedValue == $optionValue ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
            
            <option value="__other__" {{ $isCustomValue ? 'selected' : '' }}>{{ $addNewLabel }}</option>
        </select>
    </div>

    <!-- Custom Input Wrapper -->
    <div class="input-wrapper mt-2 {{ !$isCustomValue ? 'hidden' : '' }}" data-custom-input-wrapper>
        <div class="flex gap-2">
            <input 
                type="text"
                id="{{ $customInputId }}"
                @if($isCustomValue) name="{{ $name }}" @endif
                @if($isRequired && $isCustomValue) required @endif
                value="{{ $isCustomValue ? $selectedValue : '' }}"
                placeholder="{{ $customInputPlaceholder }}"
                class="flex-1 block w-full px-4 py-3.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-orange-500 focus:ring-0 transition-colors"
                data-dynamic-select-input
            >
            <button type="button" 
                    class="px-4 py-3 text-sm font-medium text-gray-700 bg-gray-100 border-2 border-gray-200 rounded-xl hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition-colors"
                    title="Kembali ke dropdown"
                    data-dynamic-select-back>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    @error($name)
        <p class="mt-2 text-sm text-red-500 flex items-center gap-1">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            {{ $message }}
        </p>
    @enderror
</div>
