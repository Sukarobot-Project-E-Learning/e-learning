/**
 * Dynamic Select Manager
 * Vanilla JS handler for select with custom "other" option toggle
 * 
 * Usage:
 * const selector = new DynamicSelectManager({
 *     container: '#expertiseContainer',
 *     selectId: 'expertise',
 *     inputId: 'expertiseCustom',
 *     name: 'expertise',
 *     otherValue: '__other__',
 *     initialValue: 'Web Development',
 *     defaultOptions: ['Web Development', 'Mobile Development', 'Data Science']
 * });
 */
class DynamicSelectManager {
    constructor(options = {}) {
        this.container = typeof options.container === 'string'
            ? document.querySelector(options.container)
            : options.container;

        if (!this.container) {
            console.error('Dynamic select container not found');
            return;
        }

        // Configuration
        this.name = options.name || 'dynamic_select';
        this.otherValue = options.otherValue || '__other__';
        this.defaultOptions = options.defaultOptions || [];
        this.initialValue = options.initialValue || '';

        // State
        this.showCustomInput = false;
        this.currentValue = this.initialValue;

        // Cache elements
        this.cacheElements(options);
        this.init();
    }

    cacheElements(options) {
        this.selectElement = this.container.querySelector(`#${options.selectId || 'dynamicSelect'}`);
        this.customInput = this.container.querySelector(`#${options.inputId || 'dynamicSelectCustom'}`);
        this.selectWrapper = this.container.querySelector('.select-wrapper');
        this.inputWrapper = this.container.querySelector('.input-wrapper');
        this.backToSelectBtn = this.container.querySelector('.back-to-select-btn');
    }

    init() {
        // Check if initial value is custom (not in default options)
        if (this.initialValue && !this.defaultOptions.includes(this.initialValue)) {
            this.showCustomInput = true;
            this.showInput();
            if (this.customInput) {
                this.customInput.value = this.initialValue;
            }
        }

        this.bindEvents();
        this.updateNameAttribute();
    }

    bindEvents() {
        // Select change handler
        this.selectElement?.addEventListener('change', (e) => {
            const value = e.target.value;
            if (value === this.otherValue) {
                this.showCustomInput = true;
                this.currentValue = '';
                this.showInput();
            } else {
                this.currentValue = value;
            }
            this.updateNameAttribute();
        });

        // Custom input change handler
        this.customInput?.addEventListener('input', (e) => {
            this.currentValue = e.target.value;
        });

        // Back to select button
        this.backToSelectBtn?.addEventListener('click', () => {
            this.showCustomInput = false;
            this.currentValue = '';
            if (this.selectElement) {
                this.selectElement.value = '';
            }
            if (this.customInput) {
                this.customInput.value = '';
            }
            this.showSelect();
            this.updateNameAttribute();
        });
    }

    showSelect() {
        if (this.selectWrapper) {
            this.selectWrapper.classList.remove('hidden');
        }
        if (this.inputWrapper) {
            this.inputWrapper.classList.add('hidden');
        }
    }

    showInput() {
        if (this.selectWrapper) {
            this.selectWrapper.classList.add('hidden');
        }
        if (this.inputWrapper) {
            this.inputWrapper.classList.remove('hidden');
        }
        // Focus the input
        this.customInput?.focus();
    }

    updateNameAttribute() {
        // Only the active input/select should have the name attribute
        if (this.showCustomInput) {
            if (this.selectElement) {
                this.selectElement.removeAttribute('name');
            }
            if (this.customInput) {
                this.customInput.setAttribute('name', this.name);
            }
        } else {
            if (this.selectElement) {
                this.selectElement.setAttribute('name', this.name);
            }
            if (this.customInput) {
                this.customInput.removeAttribute('name');
            }
        }
    }

    // Public method to get current value
    getValue() {
        return this.currentValue;
    }

    // Public method to set value programmatically
    setValue(value) {
        this.currentValue = value;
        
        if (value && !this.defaultOptions.includes(value)) {
            // Custom value
            this.showCustomInput = true;
            if (this.customInput) {
                this.customInput.value = value;
            }
            this.showInput();
        } else {
            // Default option
            this.showCustomInput = false;
            if (this.selectElement) {
                this.selectElement.value = value || '';
            }
            this.showSelect();
        }
        this.updateNameAttribute();
    }

    // Public method to check if custom input is shown
    isCustomMode() {
        return this.showCustomInput;
    }
}

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = DynamicSelectManager;
}
