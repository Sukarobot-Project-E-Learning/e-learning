/**
 * Simple Image Upload Component
 * Handles image upload preview without cropping
 * 
 * @author Refactored from Alpine.js
 * @version 1.0.0
 */
class SimpleImageUpload {
    /**
     * Initialize the simple image upload
     * @param {HTMLElement} container - The container element with [data-simple-image-upload]
     */
    constructor(container) {
        this.container = container;
        this.maxSize = parseInt(container.dataset.maxSize || 5) * 1024 * 1024;
        this.allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

        // DOM Elements
        this.uploadContainer = container.querySelector('[data-upload-container]');
        this.uploadState = container.querySelector('[data-upload-state]');
        this.previewState = container.querySelector('[data-preview-state]');
        this.previewImage = container.querySelector('[data-preview-image]');
        this.fileInput = container.querySelector('[data-file-input]');
        this.clearBtn = container.querySelector('[data-clear-button]');

        this.init();
    }

    /**
     * Initialize event listeners
     */
    init() {
        if (!this.fileInput || !this.uploadContainer) {
            console.warn('SimpleImageUpload: Required elements not found');
            return;
        }

        // File input change
        this.fileInput.addEventListener('change', (e) => this.handleFileSelect(e));

        // Clear button
        if (this.clearBtn) {
            this.clearBtn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                this.clearImage();
            });
        }

        // Drag and drop on container
        this.uploadContainer.addEventListener('dragover', (e) => {
            e.preventDefault();
            this.uploadContainer.classList.add('border-orange-400', 'bg-orange-50', 'dark:bg-orange-900/20');
        });

        this.uploadContainer.addEventListener('dragleave', (e) => {
            e.preventDefault();
            this.uploadContainer.classList.remove('border-orange-400', 'bg-orange-50', 'dark:bg-orange-900/20');
        });

        this.uploadContainer.addEventListener('drop', (e) => this.handleDrop(e));
    }

    /**
     * Handle file selection from input
     * @param {Event} e - Input change event
     */
    handleFileSelect(e) {
        const file = e.target.files[0];
        if (file) {
            this.processFile(file);
        }
    }

    /**
     * Handle file drop
     * @param {DragEvent} e - Drop event
     */
    handleDrop(e) {
        e.preventDefault();
        this.uploadContainer.classList.remove('border-orange-400', 'bg-orange-50', 'dark:bg-orange-900/20');
        
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            // Update file input
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            this.fileInput.files = dataTransfer.files;
            
            this.processFile(file);
        }
    }

    /**
     * Process selected file
     * @param {File} file - The selected file
     */
    processFile(file) {
        // Validate file type
        if (!this.allowedTypes.includes(file.type)) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Format Tidak Didukung',
                    text: 'Hanya file JPG, JPEG, dan PNG yang diperbolehkan!'
                });
            } else {
                alert('Hanya file JPG, JPEG, dan PNG yang diperbolehkan!');
            }
            this.fileInput.value = '';
            return;
        }

        // Validate file size
        if (file.size > this.maxSize) {
            const maxMB = Math.round(this.maxSize / 1024 / 1024);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar',
                    text: `Ukuran file maksimal ${maxMB}MB!`
                });
            } else {
                alert(`Ukuran file maksimal ${maxMB}MB!`);
            }
            this.fileInput.value = '';
            return;
        }

        // Read and display preview
        const reader = new FileReader();
        reader.onload = (e) => {
            this.showPreview(e.target.result);
        };
        reader.readAsDataURL(file);
    }

    /**
     * Show image preview
     * @param {string} src - Image source URL
     */
    showPreview(src) {
        if (this.previewImage) {
            this.previewImage.src = src;
        }
        
        if (this.uploadState) {
            this.uploadState.classList.add('hidden');
        }
        
        if (this.previewState) {
            this.previewState.classList.remove('hidden');
        }
    }

    /**
     * Clear the image
     */
    clearImage() {
        // Reset file input
        this.fileInput.value = '';
        
        // Clear preview
        if (this.previewImage) {
            this.previewImage.src = '';
        }
        
        // Show upload state, hide preview
        if (this.uploadState) {
            this.uploadState.classList.remove('hidden');
        }
        
        if (this.previewState) {
            this.previewState.classList.add('hidden');
        }
    }
}

/**
 * Auto-initialize all simple image uploads on page load
 */
document.addEventListener('DOMContentLoaded', function() {
    const uploads = document.querySelectorAll('[data-simple-image-upload]');
    uploads.forEach(container => {
        new SimpleImageUpload(container);
    });
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SimpleImageUpload;
}
