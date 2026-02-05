{{--
    Textarea Component
    
    Props:
    - name: string (required) - Textarea name attribute
    - label: string - Label text
    - value: string - Textarea content
    - placeholder: string - Placeholder text
    - required: bool - Whether field is required
    - disabled: bool - Whether field is disabled
    - readonly: bool - Whether field is readonly
    - rows: int - Number of rows (default: 3)
    - id: string - Custom ID (defaults to name)
    - help: string - Help text below textarea
--}}

@php
    $inputId = $id ?? $name;
    $inputValue = $value ?? old($name);
    $isRequired = $required ?? false;
    $rowCount = $rows ?? 3;
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

    <textarea 
        name="{{ $name }}"
        id="{{ $inputId }}"
        rows="{{ $rowCount }}"
        placeholder="{{ $placeholder ?? '' }}"
        @if($isRequired) required @endif
        @if($disabled ?? false) disabled @endif
        @if($readonly ?? false) readonly @endif
        class="block w-full px-4 py-3.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-orange-500 focus:ring-0 transition-colors resize-none {{ $class ?? '' }}"
    >{{ $inputValue }}</textarea>

    @if(!empty($help))
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5">{{ $help }}</p>
    @endif

    @error($name)
        <p class="mt-2 text-sm text-red-500 flex items-center gap-1">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            {{ $message }}
        </p>
    @enderror
</div>
