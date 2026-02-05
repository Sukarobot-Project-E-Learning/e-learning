/**
 * User Form Handler
 * Manages user creation/edit form interactions
 * 
 * @author Refactored from Alpine.js
 * @version 1.0.0
 */
class UserForm {
    /**
     * Initialize the user form
     * @param {string} formSelector - Selector for the form element
     */
    constructor(formSelector) {
        this.form = document.querySelector(formSelector);
        
        if (!this.form) {
            console.warn('UserForm: Form not found with selector:', formSelector);
            return;
        }

        this.passwordValidator = null;
        this.imageCropper = null;

        this.init();
    }

    /**
     * Initialize form components and event listeners
     */
    init() {
        this.initPasswordValidation();
        this.initImageCropper();
        this.attachEventListeners();
    }

    /**
     * Initialize password validation
     */
    initPasswordValidation() {
        const passwordInput = this.form.querySelector('#password');
        if (passwordInput) {
            // PasswordValidator is auto-initialized, but we can reference it here
            this.passwordValidator = new PasswordValidator(this.form, {
                minLength: 8,
                passwordSelector: '#password',
                confirmSelector: '#password_confirmation'
            });
        }
    }

    /**
     * Initialize image cropper
     */
    initImageCropper() {
        const cropperContainer = this.form.querySelector('[data-image-cropper]');
        if (cropperContainer) {
            // ImageCropper is auto-initialized, but we can reference it here
            this.imageCropper = new ImageCropper(cropperContainer);
        }
    }

    /**
     * Attach form event listeners
     */
    attachEventListeners() {
        // Form submission
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));

        // Phone number formatting
        const phoneInput = this.form.querySelector('#phone');
        if (phoneInput) {
            phoneInput.addEventListener('input', (e) => this.formatPhoneNumber(e));
        }
    }

    /**
     * Handle form submission
     * @param {Event} event - Submit event
     */
    handleSubmit(event) {
        // Validation is handled by PasswordValidator
        // Additional validations can be added here
        
        const isValid = this.validateForm();
        
        if (!isValid) {
            event.preventDefault();
            return false;
        }
        
        return true;
    }

    /**
     * Validate the entire form
     * @returns {boolean} Whether form is valid
     */
    validateForm() {
        let isValid = true;

        // Check required fields
        const requiredFields = this.form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                this.highlightError(field);
            } else {
                this.clearError(field);
            }
        });

        // Email validation
        const emailInput = this.form.querySelector('#email');
        if (emailInput && emailInput.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailInput.value)) {
                isValid = false;
                this.highlightError(emailInput);
            }
        }

        return isValid;
    }

    /**
     * Highlight field with error
     * @param {HTMLElement} field - Field element
     */
    highlightError(field) {
        field.classList.add('border-red-500');
        field.classList.remove('border-gray-200', 'dark:border-gray-700');
    }

    /**
     * Clear error highlighting from field
     * @param {HTMLElement} field - Field element
     */
    clearError(field) {
        field.classList.remove('border-red-500');
        field.classList.add('border-gray-200', 'dark:border-gray-700');
    }

    /**
     * Format phone number input
     * @param {Event} event - Input event
     */
    formatPhoneNumber(event) {
        let value = event.target.value.replace(/\D/g, '');
        
        // Ensure starts with valid prefix
        if (value.length > 0 && !value.startsWith('0') && !value.startsWith('62')) {
            value = '0' + value;
        }
        
        // Limit length
        if (value.length > 15) {
            value = value.substring(0, 15);
        }
        
        event.target.value = value;
    }
}

/**
 * Auto-initialize user form if exists
 */
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('[data-user-form]');
    if (form) {
        new UserForm('[data-user-form]');
    }
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = UserForm;
}
