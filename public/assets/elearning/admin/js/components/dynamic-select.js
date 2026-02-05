/**
 * Dynamic Select Component
 * Handles select with custom value input option
 * 
 * @author Refactored from Alpine.js
 * @version 1.0.0
 */
class DynamicSelect {
    /**
     * Initialize the dynamic select
     * @param {HTMLElement} container - The container element with [data-dynamic-select]
     */
    constructor(container) {
        this.container = container;
        this.initialValue = container.dataset.initialValue || '';
        this.isCustom = container.dataset.isCustom === 'true';
        this.otherValue = '__other__';

        // DOM Elements
        this.selectWrapper = container.querySelector('[data-select-wrapper]');
        this.customWrapper = container.querySelector('[data-custom-input-wrapper]');
        this.dropdown = container.querySelector('[data-dynamic-select-dropdown]');
        this.customInput = container.querySelector('[data-dynamic-select-input]');
        this.backBtn = container.querySelector('[data-dynamic-select-back]');

        this.init();
    }

    /**
     * Initialize event listeners
     */
    init() {
        if (!this.dropdown || !this.customInput) {
            console.warn('DynamicSelect: Required elements not found');
            return;
        }

        // Handle select change
        this.dropdown.addEventListener('change', (e) => this.handleSelectChange(e));

        // Handle back button
        if (this.backBtn) {
            this.backBtn.addEventListener('click', () => this.switchToSelect());
        }

        // Initialize custom input if already in custom mode
        if (this.isCustom) {
            this.switchToCustom(false);
        }
    }

    /**
     * Handle select dropdown change
     * @param {Event} e - Change event
     */
    handleSelectChange(e) {
        if (e.target.value === this.otherValue) {
            this.switchToCustom(true);
        }
    }

    /**
     * Switch to custom input mode
     * @param {boolean} focus - Whether to focus the input
     */
    switchToCustom(focus = true) {
        // Show custom input, hide wrapper (but keep select for reference)
        this.customWrapper.classList.remove('hidden');
        
        // Transfer name attribute from select to input
        const selectName = this.dropdown.getAttribute('name');
        if (selectName) {
            this.dropdown.removeAttribute('name');
            this.dropdown.removeAttribute('required');
            this.customInput.setAttribute('name', selectName);
            
            // Check if original select was required
            if (this.container.querySelector('[data-select-wrapper] select[required]') || 
                this.dropdown.hasAttribute('data-was-required')) {
                this.customInput.setAttribute('required', '');
            }
        }

        // Mark that select had required
        if (this.dropdown.hasAttribute('required')) {
            this.dropdown.setAttribute('data-was-required', 'true');
        }

        if (focus) {
            this.customInput.focus();
        }
    }

    /**
     * Switch back to select mode
     */
    switchToSelect() {
        // Hide custom input
        this.customWrapper.classList.add('hidden');
        
        // Transfer name back to select
        const inputName = this.customInput.getAttribute('name');
        if (inputName) {
            this.customInput.removeAttribute('name');
            this.customInput.removeAttribute('required');
            this.dropdown.setAttribute('name', inputName);
            
            // Restore required if it was set
            if (this.dropdown.hasAttribute('data-was-required')) {
                this.dropdown.setAttribute('required', '');
            }
        }

        // Reset select to placeholder
        this.dropdown.value = '';
        
        // Clear custom input
        this.customInput.value = '';
    }

    /**
     * Get current value (either from select or custom input)
     * @returns {string}
     */
    getValue() {
        if (!this.customWrapper.classList.contains('hidden')) {
            return this.customInput.value;
        }
        return this.dropdown.value === this.otherValue ? '' : this.dropdown.value;
    }

    /**
     * Set value programmatically
     * @param {string} value 
     */
    setValue(value) {
        // Check if value exists in options
        const optionExists = Array.from(this.dropdown.options).some(opt => opt.value === value);
        
        if (optionExists && value !== this.otherValue) {
            this.dropdown.value = value;
            this.switchToSelect();
        } else if (value) {
            this.dropdown.value = this.otherValue;
            this.customInput.value = value;
            this.switchToCustom(false);
        }
    }
}

/**
 * Auto-initialize all dynamic selects on page load
 */
document.addEventListener('DOMContentLoaded', function() {
    const dynamicSelects = document.querySelectorAll('[data-dynamic-select]');
    dynamicSelects.forEach(container => {
        new DynamicSelect(container);
    });
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = DynamicSelect;
}
