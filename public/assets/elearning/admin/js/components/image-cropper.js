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
        this.googleUrlInput = container.querySelector('[data-google-url-input]');
        this.googleUrlSection = container.querySelector('[data-google-url-section]');

        // Track if using external URL (Google)
        this.isExternalUrl = false;

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

        // Google URL input
        if (this.googleUrlInput) {
            this.googleUrlInput.addEventListener('input', (e) => this.handleGoogleUrlInput(e));
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
        // Clear external URL if file is being uploaded
        if (this.googleUrlInput) {
            this.googleUrlInput.value = '';
        }
        this.isExternalUrl = false;

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
     * Handle Google/External URL input
     * @param {Event} event 
     */
    handleGoogleUrlInput(event) {
        const url = event.target.value.trim();

        if (!url) {
            this.isExternalUrl = false;
            this.clearImage();
            return;
        }

        // Validate URL format
        try {
            new URL(url);
        } catch (e) {
            return; // Invalid URL, don't process
        }

        // Check if it's a valid image URL (simple check)
        if (!this.isValidImageUrl(url)) {
            this.showError('URL Tidak Valid', 'Pastikan URL menunjuk ke gambar yang valid (jpg, png, etc)');
            event.target.value = '';
            return;
        }

        // Set external URL mode
        this.isExternalUrl = true;
        this.previewUrl = url;
        this.resetPosition();

        // Set output directly to URL (no cropping for external URLs)
        if (this.outputInput) {
            this.outputInput.value = url;
        }

        // Show preview without cropping interface
        this.showExternalImagePreview(url);
    }

    /**
     * Validate if URL points to an image
     * @param {string} url 
     * @returns {boolean}
     */
    isValidImageUrl(url) {
        // Static image extensions only
        const imageExtensions = ['.jpg', '.jpeg', '.png', '.webp', '.bmp'];
        const lowerUrl = url.toLowerCase();
        
        // Check if URL contains static image extension
        if (imageExtensions.some(ext => lowerUrl.includes(ext))) {
            return true;
        }
        
        // Check for supported image CDNs and social platforms
        const supportedDomains = [
            // Google services
            'lh3.googleusercontent.com',      // Google accounts, Gmail profile pictures
            'googleusercontent.com',          // Google Drive, Google Photos
            
            // Facebook
            'graph.facebook.com',             // Facebook images
            
            // Instagram
            'scontent.cdninstagram.com',      // Instagram images
            'cdninstagram.com',               // Instagram CDN
            
            // Pinterest
            'i.pinimg.com',                   // Pinterest images
            
            // Twitter/X
            'pbs.twimg.com',                  // Twitter/X images
            'ton.twitter.com',                // Twitter/X images (new CDN)
            
            // Imgur
            'i.imgur.com',                    // Imgur images
            'imgur.com',                      // Imgur direct
            
            // AWS & CDNs
            's3.amazonaws.com',               // AWS S3
            'cloudfront.net',                 // AWS CloudFront
        ];
        
        return supportedDomains.some(domain => lowerUrl.includes(domain));
    }

    /**
     * Show preview for external URL without cropping
     * @param {string} url 
     */
    showExternalImagePreview(url) {
        if (!this.previewWrapper || !this.dropzoneWrapper) {
            return;
        }

        // Create simple preview without cropping tools
        this.previewWrapper.innerHTML = `
            <div class="border-2 border-green-200 dark:border-green-800 rounded-xl p-4 bg-green-50 dark:bg-green-900/20">
                <div class="flex items-center gap-3 mb-3">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <img src="${url}" alt="External image preview" class="w-full max-h-64 rounded-lg object-cover mb-3">
                <button type="button" class="text-sm text-red-500 hover:text-red-700 font-medium" data-google-url-clear-btn>Ganti URL</button>
            </div>
        `;

        this.previewWrapper.classList.remove('hidden');
        this.dropzoneWrapper.classList.add('hidden');
        
        if (this.currentImageWrapper) {
            this.currentImageWrapper.classList.add('hidden');
        }

        // Add clear button listener
        const clearBtn = this.previewWrapper.querySelector('[data-google-url-clear-btn]');
        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                if (this.googleUrlInput) {
                    this.googleUrlInput.value = '';
                    this.handleGoogleUrlInput({ target: this.googleUrlInput });
                }
            });
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
        if (this.googleUrlInput) {
            this.googleUrlInput.value = '';
        }
        this.isExternalUrl = false;
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
        // Skip processing for external URLs (they're stored as-is)
        if (this.isExternalUrl) {
            return;
        }

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
