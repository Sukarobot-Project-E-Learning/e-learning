{{--
    Text Input Component
    
    Props:
    - name: string (required) - Input name attribute
    - label: string - Label text
    - type: string - Input type (text, email, tel, url, number) default: text
    - value: string - Input value
    - placeholder: string - Placeholder text
    - required: bool - Whether field is required
    - disabled: bool - Whether field is disabled
    - readonly: bool - Whether field is readonly
    - icon: string - SVG icon path for left icon
    - help: string - Help text below input
    - id: string - Custom ID (defaults to name)
    - class: string - Additional CSS classes
--}}

@php
    $inputId = $id ?? $name;
    $inputValue = $value ?? old($name);
    $isRequired = $required ?? false;
    $hasIcon = !empty($icon);
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

    <div class="{{ $hasIcon ? 'relative' : '' }}">
        @if($hasIcon)
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $icon !!}
                </svg>
            </div>
        @endif

        <input 
            type="{{ $type ?? 'text' }}"
            name="{{ $name }}"
            id="{{ $inputId }}"
            value="{{ $inputValue }}"
            placeholder="{{ $placeholder ?? '' }}"
            @if($isRequired) required @endif
            @if($disabled ?? false) disabled @endif
            @if($readonly ?? false) readonly @endif
            @if(!empty($minlength)) minlength="{{ $minlength }}" @endif
            @if(!empty($maxlength)) maxlength="{{ $maxlength }}" @endif
            class="block w-full {{ $hasIcon ? 'pl-12 pr-4' : 'px-4' }} py-3.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-orange-500 focus:ring-0 transition-colors {{ $class ?? '' }}"
            {!! $attributes ?? '' !!}
        />
    </div>

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
