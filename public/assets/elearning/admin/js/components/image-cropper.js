/**
 * Image Cropper Component
 * Handles image upload, preview, zoom, drag, and circular cropping
 * 
 * @author Refactored from Alpine.js
 * @version 1.0.0
 */
class ImageCropper {
    /**
     * Initialize the image cropper
     * @param {HTMLElement} container - The container element with [data-image-cropper]
     */
    constructor(container) {
        this.container = container;
        this.maxSize = parseInt(container.dataset.maxSize || 2) * 1024 * 1024;
        this.allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        this.circleSize = 128;
        this.outputSize = 256;

        // State
        this.zoom = 1;
        this.posX = 0;
        this.posY = 0;
        this.isDragging = false;
        this.isMoving = false;
        this.startX = 0;
        this.startY = 0;
        this.originalImage = null;
        this.previewUrl = null;

        // DOM Elements
        this.fileInput = container.querySelector('[data-cropper-file-input]');
        this.dropzone = container.querySelector('[data-cropper-dropzone]');
        this.dropzoneWrapper = container.querySelector('[data-cropper-dropzone-wrapper]');
        this.previewWrapper = container.querySelector('[data-cropper-preview-wrapper]');
        this.previewImage = container.querySelector('[data-cropper-preview-image]');
        this.previewContainer = container.querySelector('[data-cropper-container]');
        this.zoomSlider = container.querySelector('[data-cropper-zoom-slider]');
        this.zoomLabel = container.querySelector('[data-cropper-zoom-label]');
        this.resetBtn = container.querySelector('[data-cropper-reset-btn]');
        this.clearBtn = container.querySelector('[data-cropper-clear-btn]');
        this.outputInput = container.querySelector('[data-cropper-output]');
        this.canvas = container.querySelector('[data-cropper-canvas]');
        this.currentImageWrapper = container.querySelector('[data-current-image-wrapper]');

        this.init();
    }

    /**
     * Initialize event listeners
     */
    init() {
        if (!this.fileInput || !this.dropzone) {
            console.warn('ImageCropper: Required elements not found');
            return;
        }

        // File input change
        this.fileInput.addEventListener('change', (e) => this.handleFileSelect(e));

        // Drag and drop
        this.dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            this.dropzone.classList.add('border-orange-400', 'bg-orange-50', 'dark:bg-orange-900/20');
        });

        this.dropzone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            this.dropzone.classList.remove('border-orange-400', 'bg-orange-50', 'dark:bg-orange-900/20');
        });

        this.dropzone.addEventListener('drop', (e) => this.handleDrop(e));

        // Zoom slider
        if (this.zoomSlider) {
            this.zoomSlider.addEventListener('input', (e) => {
                this.setZoom(parseInt(e.target.value) / 100);
            });
        }

        // Reset button
        if (this.resetBtn) {
            this.resetBtn.addEventListener('click', () => this.resetPosition());
        }

        // Clear button
        if (this.clearBtn) {
            this.clearBtn.addEventListener('click', () => this.clearImage());
        }

        // Preview container interactions
        if (this.previewContainer) {
            // Wheel zoom
            this.previewContainer.addEventListener('wheel', (e) => {
                e.preventDefault();
                this.handleZoom(e);
            });

            // Mouse drag
            this.previewContainer.addEventListener('mousedown', (e) => {
                e.preventDefault();
                this.startDrag(e);
            });

            document.addEventListener('mousemove', (e) => this.onDrag(e));
            document.addEventListener('mouseup', () => this.stopDrag());

            // Touch drag
            this.previewContainer.addEventListener('touchstart', (e) => {
                e.preventDefault();
                this.startDrag(e.touches[0]);
            });

            this.previewContainer.addEventListener('touchmove', (e) => {
                this.onDrag(e.touches[0]);
            });

            this.previewContainer.addEventListener('touchend', () => this.stopDrag());
        }

        // Preview image load
        if (this.previewImage) {
            this.previewImage.addEventListener('load', () => this.onImageLoad());
        }
    }

    /**
     * Handle file selection from input
     * @param {Event} event 
     */
    handleFileSelect(event) {
        const file = event.target.files[0];
        if (file) {
            this.processFile(file);
        }
    }

    /**
     * Handle file drop
     * @param {DragEvent} event 
     */
    handleDrop(event) {
        event.preventDefault();
        this.dropzone.classList.remove('border-orange-400', 'bg-orange-50', 'dark:bg-orange-900/20');

        const file = event.dataTransfer.files[0];
        if (file) {
            // Update file input
            const dt = new DataTransfer();
            dt.items.add(file);
            this.fileInput.files = dt.files;
            this.processFile(file);
        }
    }

    /**
     * Process and validate uploaded file
     * @param {File} file 
     */
    processFile(file) {
        // Validate type
        if (!this.allowedTypes.includes(file.type)) {
            this.showError('Upload Gagal', 'Format file tidak sesuai.');
            this.fileInput.value = '';
            return;
        }

        // Validate size
        if (file.size > this.maxSize) {
            this.showError('Upload Gagal', 'Ukuran file melebihi ' + (this.maxSize / 1024 / 1024) + 'MB.');
            this.fileInput.value = '';
            return;
        }

        // Read file
        const reader = new FileReader();
        reader.onload = (e) => {
            this.previewUrl = e.target.result;
            this.resetPosition();

            // Load original image for cropping
            this.originalImage = new Image();
            this.originalImage.src = e.target.result;

            // Update preview
            if (this.previewImage) {
                this.previewImage.src = e.target.result;
            }

            this.showPreview();
        };
        reader.readAsDataURL(file);
    }

    /**
     * Show error message using SweetAlert if available
     * @param {string} title 
     * @param {string} text 
     */
    showError(title, text) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({ icon: 'error', title, text });
        } else {
            alert(title + ': ' + text);
        }
    }

    /**
     * Show the preview area and hide dropzone
     */
    showPreview() {
        if (this.previewWrapper) {
            this.previewWrapper.classList.remove('hidden');
        }
        if (this.dropzoneWrapper) {
            this.dropzoneWrapper.style.display = 'none';
        }
        if (this.currentImageWrapper) {
            this.currentImageWrapper.style.display = 'none';
        }
    }

    /**
     * Hide the preview area and show dropzone
     */
    hidePreview() {
        if (this.previewWrapper) {
            this.previewWrapper.classList.add('hidden');
        }
        if (this.dropzoneWrapper) {
            this.dropzoneWrapper.style.display = 'flex';
        }
        if (this.currentImageWrapper) {
            this.currentImageWrapper.style.display = 'flex';
        }
    }

    /**
     * Handle image load event
     */
    onImageLoad() {
        setTimeout(() => this.updateCroppedImage(), 50);
    }

    /**
     * Set zoom level
     * @param {number} value - Zoom value (0.1 to 3)
     */
    setZoom(value) {
        this.zoom = Math.max(0.1, Math.min(3, value));
        this.updateZoomUI();
        this.updateCroppedImage();
    }

    /**
     * Handle wheel zoom
     * @param {WheelEvent} event 
     */
    handleZoom(event) {
        if (event.deltaY < 0) {
            this.zoom = Math.min(3, this.zoom + 0.05);
        } else {
            this.zoom = Math.max(0.1, this.zoom - 0.05);
        }
        this.updateZoomUI();
        this.updateCroppedImage();
    }

    /**
     * Update zoom UI elements
     */
    updateZoomUI() {
        const zoomPercent = Math.round(this.zoom * 100);
        if (this.zoomSlider) {
            this.zoomSlider.value = zoomPercent;
        }
        if (this.zoomLabel) {
            this.zoomLabel.textContent = zoomPercent + '%';
        }
        this.updatePreviewTransform();
    }

    /**
     * Update preview image transform
     */
    updatePreviewTransform() {
        if (this.previewImage) {
            this.previewImage.style.transform = `scale(${this.zoom}) translate(${this.posX}px, ${this.posY}px)`;
        }
    }

    /**
     * Start drag operation
     * @param {MouseEvent|Touch} event 
     */
    startDrag(event) {
        this.isMoving = true;
        this.startX = event.clientX - this.posX;
        this.startY = event.clientY - this.posY;
    }

    /**
     * Handle drag movement
     * @param {MouseEvent|Touch} event 
     */
    onDrag(event) {
        if (!this.isMoving) return;
        this.posX = event.clientX - this.startX;
        this.posY = event.clientY - this.startY;
        this.updatePreviewTransform();
    }

    /**
     * Stop drag operation
     */
    stopDrag() {
        if (this.isMoving) {
            this.isMoving = false;
            this.updateCroppedImage();
        }
    }

    /**
     * Reset position and zoom
     */
    resetPosition() {
        this.zoom = 1;
        this.posX = 0;
        this.posY = 0;
        this.updateZoomUI();
        this.updatePreviewTransform();
        setTimeout(() => this.updateCroppedImage(), 50);
    }

    /**
     * Clear the uploaded image
     */
    clearImage() {
        this.previewUrl = null;
        this.originalImage = null;
        this.fileInput.value = '';
        if (this.outputInput) {
            this.outputInput.value = '';
        }
        this.resetPosition();
        this.hidePreview();
    }

    /**
     * Update the cropped image output
     */
    updateCroppedImage() {
        if (!this.originalImage || !this.previewUrl || !this.canvas || !this.previewImage || !this.previewContainer) {
            return;
        }

        const ctx = this.canvas.getContext('2d');
        this.canvas.width = this.outputSize;
        this.canvas.height = this.outputSize;

        const natW = this.originalImage.width;
        const natH = this.originalImage.height;
        const dispW = this.previewImage.offsetWidth;
        const dispH = this.previewImage.offsetHeight;

        if (dispW === 0 || dispH === 0) return;

        const scaleX = natW / dispW;
        const scaleY = natH / dispH;
        const containerCenterX = this.previewContainer.offsetWidth / 2;
        const containerCenterY = this.previewContainer.offsetHeight / 2;

        const translateOffsetX = this.posX * this.zoom;
        const translateOffsetY = this.posY * this.zoom;

        const circleInDisplayedX = (containerCenterX - containerCenterX - translateOffsetX) / this.zoom + dispW / 2;
        const circleInDisplayedY = (containerCenterY - containerCenterY - translateOffsetY) / this.zoom + dispH / 2;
        const circleSizeInDisplayed = this.circleSize / this.zoom;

        const cropDisplayedX = circleInDisplayedX - circleSizeInDisplayed / 2;
        const cropDisplayedY = circleInDisplayedY - circleSizeInDisplayed / 2;

        const srcX = cropDisplayedX * scaleX;
        const srcY = cropDisplayedY * scaleY;
        const srcW = circleSizeInDisplayed * scaleX;
        const srcH = circleSizeInDisplayed * scaleY;

        // Clear and clip to circle
        ctx.clearRect(0, 0, this.outputSize, this.outputSize);
        ctx.beginPath();
        ctx.arc(this.outputSize / 2, this.outputSize / 2, this.outputSize / 2, 0, Math.PI * 2);
        ctx.closePath();
        ctx.clip();

        // Fill white background
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, this.outputSize, this.outputSize);

        // Draw cropped image
        try {
            ctx.drawImage(
                this.originalImage,
                srcX, srcY,
                Math.max(srcW, srcH), Math.max(srcW, srcH),
                0, 0,
                this.outputSize, this.outputSize
            );
        } catch (e) {
            console.warn('ImageCropper: Error drawing image', e);
        }

        // Output to hidden input
        if (this.outputInput) {
            this.outputInput.value = this.canvas.toDataURL('image/png');
        }
    }
}

/**
 * Auto-initialize all image croppers on the page
 */
document.addEventListener('DOMContentLoaded', () => {
    const croppers = document.querySelectorAll('[data-image-cropper]');
    croppers.forEach(container => {
        new ImageCropper(container);
    });
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ImageCropper;
}
