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

    <div class="relative">
        <input 
            type="password"
            name="{{ $name }}"
            id="{{ $inputId }}"
            placeholder="{{ $placeholder ?? '' }}"
            @if($isRequired) required @endif
            minlength="{{ $minLen }}"
            data-password-input
            class="block w-full px-4 py-3.5 pr-12 text-sm text-gray-900 dark:text-white placeholder-gray-400 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:border-orange-500 focus:ring-0 transition-colors"
        />
        
        <button
            type="button"
            data-password-toggle
            data-target="{{ $inputId }}"
            class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors"
            tabindex="-1"
            aria-label="Toggle password visibility"
        >
            <!-- Eye Icon (visible) -->
            <svg data-icon-show class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            
            <!-- Eye Off Icon (hidden) -->
            <svg data-icon-hide class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-4.803m5.596-3.856a3.375 3.375 0 11-4.753 4.753m4.753-4.753L3.596 3.039m10.318 10.318L21 21"></path>
            </svg>
        </button>
    </div>

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.querySelector('[data-password-toggle][data-target="{{ $inputId }}"]');
            const input = document.getElementById('{{ $inputId }}');
            
            if (!toggleBtn || !input) return;
            
            const showIcon = toggleBtn.querySelector('[data-icon-show]');
            const hideIcon = toggleBtn.querySelector('[data-icon-hide]');
            
            toggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                
                showIcon.classList.toggle('hidden');
                hideIcon.classList.toggle('hidden');
            });
        });
    </script>
</div>
