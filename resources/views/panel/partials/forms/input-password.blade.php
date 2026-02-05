{{--
    Password Input Component
    
    Props:
    - name: string (required) - Input name attribute
    - label: string - Label text
    - placeholder: string - Placeholder text
    - required: bool - Whether field is required
    - help: string - Help text below input
    - id: string - Custom ID (defaults to name)
    - minlength: int - Minimum password length
    - showValidation: bool - Show validation messages container
--}}

@php
    $inputId = $id ?? $name;
    $isRequired = $required ?? false;
    $minLen = $minlength ?? 8;
@endphp

<div class="form-group password-field" data-password-field>
    @if(!empty($label))
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="{{ $inputId }}">
            {{ $label }}
            @if($isRequired)
                <span class="text-orange-500">*</span>
            @endif
            @if(!empty($labelHint))
                <span class="text-gray-500">{{ $labelHint }}</span>
            @endif
        </label>
    @endif

    <input 
        type="password"
        name="{{ $name }}"
        id="{{ $inputId }}"
        placeholder="{{ $placeholder ?? '' }}"
        @if($isRequired) required @endif
        minlength="{{ $minLen }}"
        data-password-input
        class="block w-full px-4 py-3.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-orange-500 focus:ring-0 transition-colors"
    />

    @if(!empty($help))
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5 password-help" data-password-help>{{ $help }}</p>
    @endif

    @if($showValidation ?? false)
        <p class="text-red-500 text-xs mt-1.5 hidden" data-password-error></p>
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
