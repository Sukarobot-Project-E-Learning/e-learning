/**
 * Password Validator Component
 * Handles real-time password validation and confirmation matching
 * 
 * @author Refactored from Alpine.js
 * @version 1.0.0
 */
class PasswordValidator {
    /**
     * Initialize the password validator
     * @param {HTMLFormElement} form - The form element containing password fields
     * @param {Object} options - Configuration options
     */
    constructor(form, options = {}) {
        this.form = form;
        this.options = {
            minLength: options.minLength || 8,
            passwordSelector: options.passwordSelector || '#password',
            confirmSelector: options.confirmSelector || '#password_confirmation',
            ...options
        };

        // Get password fields
        this.passwordInput = form.querySelector(this.options.passwordSelector);
        this.confirmInput = form.querySelector(this.options.confirmSelector);

        // Error display elements
        this.passwordError = null;
        this.confirmError = null;
        this.passwordHelp = null;

        if (this.passwordInput) {
            this.init();
        }
    }

    /**
     * Initialize event listeners and error elements
     */
    init() {
        // Find or create error elements
        this.passwordError = this.passwordInput.parentElement.querySelector('[data-password-error]');
        this.passwordHelp = this.passwordInput.parentElement.querySelector('[data-password-help]');

        if (this.confirmInput) {
            this.confirmError = this.confirmInput.parentElement.querySelector('[data-password-error]');
            if (!this.confirmError) {
                this.confirmError = document.createElement('p');
                this.confirmError.className = 'text-red-500 text-xs mt-1.5 hidden';
                this.confirmError.setAttribute('data-password-error', '');
                this.confirmInput.parentElement.appendChild(this.confirmError);
            }
        }

        // Attach event listeners
        this.passwordInput.addEventListener('input', () => this.validate());
        
        if (this.confirmInput) {
            this.confirmInput.addEventListener('input', () => this.validate());
        }

        // Form submit validation
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
    }

    /**
     * Validate password fields
     * @returns {Object} Validation result with isValid flag and errors
     */
    validate() {
        const password = this.passwordInput.value;
        const confirm = this.confirmInput ? this.confirmInput.value : '';
        
        let passwordErrorMsg = '';
        let confirmErrorMsg = '';

        // Password length validation
        if (password.length > 0 && password.length < this.options.minLength) {
            passwordErrorMsg = `Password minimal ${this.options.minLength} karakter.`;
        }

        // Confirmation match validation
        if (confirm && password !== confirm) {
            confirmErrorMsg = 'Konfirmasi password tidak cocok.';
        }

        // Update UI
        this.updatePasswordError(passwordErrorMsg);
        this.updateConfirmError(confirmErrorMsg);

        return {
            isValid: !passwordErrorMsg && !confirmErrorMsg,
            errors: {
                password: passwordErrorMsg,
                confirm: confirmErrorMsg
            }
        };
    }

    /**
     * Update password error display
     * @param {string} message - Error message to display
     */
    updatePasswordError(message) {
        if (this.passwordError) {
            if (message) {
                this.passwordError.textContent = message;
                this.passwordError.classList.remove('hidden');
                if (this.passwordHelp) {
                    this.passwordHelp.classList.add('hidden');
                }
            } else {
                this.passwordError.textContent = '';
                this.passwordError.classList.add('hidden');
                if (this.passwordHelp) {
                    this.passwordHelp.classList.remove('hidden');
                }
            }
        }
    }

    /**
     * Update confirm password error display
     * @param {string} message - Error message to display
     */
    updateConfirmError(message) {
        if (this.confirmError) {
            if (message) {
                this.confirmError.textContent = message;
                this.confirmError.classList.remove('hidden');
            } else {
                this.confirmError.textContent = '';
                this.confirmError.classList.add('hidden');
            }
        }
    }

    /**
     * Handle form submission
     * @param {Event} event - Submit event
     */
    handleSubmit(event) {
        const password = this.passwordInput.value;
        
        // Skip validation if password is empty (for edit forms)
        if (!password && !this.passwordInput.required) {
            return true;
        }

        const result = this.validate();
        
        if (!result.isValid) {
            event.preventDefault();
            
            // Show SweetAlert if available
            if (typeof Swal !== 'undefined') {
                let errorMessage = result.errors.password || result.errors.confirm;
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    text: errorMessage
                });
            }
            
            return false;
        }
        
        return true;
    }
}

/**
 * Auto-initialize password validators on forms
 */
document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('[data-user-form], [data-password-form]');
    forms.forEach(form => {
        new PasswordValidator(form);
    });
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PasswordValidator;
}
