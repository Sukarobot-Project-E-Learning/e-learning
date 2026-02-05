/**
 * Image Upload Manager
 * Vanilla JS handler for image upload with circular preview, zoom, and crop
 * 
 * Usage:
 * const uploader = new ImageUploadManager({
 *     container: '#imageUploadContainer',
 *     inputId: 'photo',
 *     croppedInputId: 'cropped_photo',
 *     maxSize: 2, // MB
 *     allowedTypes: ['image/jpeg', 'image/png', 'image/jpg'],
 *     circleSize: 128,
 *     outputSize: 256
 * });
 */
class ImageUploadManager {
    constructor(options = {}) {
        this.container = typeof options.container === 'string' 
            ? document.querySelector(options.container) 
            : options.container;
        
        if (!this.container) {
            console.error('Image upload container not found');
            return;
        }

        // Configuration
        this.maxSize = (options.maxSize || 2) * 1024 * 1024; // Convert MB to bytes
        this.allowedTypes = options.allowedTypes || ['image/jpeg', 'image/png', 'image/jpg'];
        this.circleSize = options.circleSize || 128;
        this.outputSize = options.outputSize || 256;

        // State
        this.previewUrl = null;
        this.zoom = 1;
        this.posX = 0;
        this.posY = 0;
        this.isDragging = false;
        this.isMoving = false;
        this.startX = 0;
        this.startY = 0;
        this.originalImage = null;

        // Cache elements
        this.cacheElements(options);
        this.init();
    }

    cacheElements(options) {
        this.fileInput = this.container.querySelector(`#${options.inputId || 'photo'}`);
        this.croppedInput = this.container.querySelector(`[name="${options.croppedInputId || 'cropped_photo'}"]`);
        this.uploadArea = this.container.querySelector('.upload-area');
        this.previewArea = this.container.querySelector('.preview-area');
        this.previewContainer = this.container.querySelector('.preview-container');
        this.previewImage = this.container.querySelector('.preview-image');
        this.zoomSlider = this.container.querySelector('.zoom-slider');
        this.zoomText = this.container.querySelector('.zoom-text');
        this.resetBtn = this.container.querySelector('.reset-btn');
        this.clearBtn = this.container.querySelector('.clear-btn');
        this.cropCanvas = this.container.querySelector('.crop-canvas');
    }

    init() {
        this.bindEvents();
        
        // If there's an existing image, show preview
        if (this.previewImage && this.previewImage.src && this.previewImage.src !== window.location.href) {
            this.previewUrl = this.previewImage.src;
            this.originalImage = new Image();
            this.originalImage.src = this.previewUrl;
            this.showPreview();
        }
    }

    bindEvents() {
        // File input change
        this.fileInput?.addEventListener('change', (e) => this.handleFileSelect(e));

        // Upload area drag & drop
        if (this.uploadArea) {
            this.uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                this.uploadArea.classList.add('border-orange-400', 'bg-orange-50', 'dark:bg-orange-900/20');
            });

            this.uploadArea.addEventListener('dragleave', (e) => {
                e.preventDefault();
                this.uploadArea.classList.remove('border-orange-400', 'bg-orange-50', 'dark:bg-orange-900/20');
            });

            this.uploadArea.addEventListener('drop', (e) => this.handleDrop(e));
        }

        // Preview container interactions
        if (this.previewContainer) {
            // Wheel zoom
            this.previewContainer.addEventListener('wheel', (e) => {
                e.preventDefault();
                this.handleZoom(e);
            }, { passive: false });

            // Mouse drag
            this.previewContainer.addEventListener('mousedown', (e) => this.startDrag(e));
            this.previewContainer.addEventListener('mousemove', (e) => this.onDrag(e));
            this.previewContainer.addEventListener('mouseup', () => this.stopDrag());
            this.previewContainer.addEventListener('mouseleave', () => this.stopDrag());

            // Touch drag
            this.previewContainer.addEventListener('touchstart', (e) => {
                e.preventDefault();
                this.startDrag(e.touches[0]);
            }, { passive: false });
            this.previewContainer.addEventListener('touchmove', (e) => this.onDrag(e.touches[0]));
            this.previewContainer.addEventListener('touchend', () => this.stopDrag());
        }

        // Zoom slider
        this.zoomSlider?.addEventListener('input', (e) => {
            this.setZoom(e.target.value / 100);
        });

        // Reset button
        this.resetBtn?.addEventListener('click', () => this.resetPosition());

        // Clear button
        this.clearBtn?.addEventListener('click', () => this.clearImage());

        // Preview image load
        this.previewImage?.addEventListener('load', () => this.onImageLoad());
    }

    handleFileSelect(event) {
        const file = event.target.files[0];
        if (file) this.processFile(file);
    }

    handleDrop(event) {
        event.preventDefault();
        this.uploadArea.classList.remove('border-orange-400', 'bg-orange-50', 'dark:bg-orange-900/20');
        
        const file = event.dataTransfer.files[0];
        if (file) {
            // Update file input
            const dt = new DataTransfer();
            dt.items.add(file);
            this.fileInput.files = dt.files;
            this.processFile(file);
        }
    }

    processFile(file) {
        // Validate type
        if (!this.allowedTypes.includes(file.type)) {
            this.showError('Format file tidak sesuai. Harap unggah JPG, JPEG, atau PNG.');
            this.fileInput.value = '';
            return;
        }

        // Validate size
        if (file.size > this.maxSize) {
            this.showError(`Ukuran file melebihi ${this.maxSize / (1024 * 1024)}MB.`);
            this.fileInput.value = '';
            return;
        }

        // Read file and show preview
        const reader = new FileReader();
        reader.onload = (e) => {
            this.previewUrl = e.target.result;
            this.originalImage = new Image();
            this.originalImage.src = e.target.result;
            this.originalImage.onload = () => {
                this.resetPosition();
                this.showPreview();
            };
        };
        reader.readAsDataURL(file);
    }

    showPreview() {
        if (this.previewImage) {
            this.previewImage.src = this.previewUrl;
        }
        if (this.uploadArea) {
            this.uploadArea.classList.add('hidden');
        }
        if (this.previewArea) {
            this.previewArea.classList.remove('hidden');
        }
    }

    hidePreview() {
        if (this.uploadArea) {
            this.uploadArea.classList.remove('hidden');
        }
        if (this.previewArea) {
            this.previewArea.classList.add('hidden');
        }
    }

    showError(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Upload Gagal',
                text: message
            });
        } else {
            alert(message);
        }
    }

    onImageLoad() {
        this.updateCroppedImage();
    }

    setZoom(value) {
        this.zoom = Math.max(0.1, Math.min(3, value));
        this.updatePreviewTransform();
        this.updateZoomUI();
        this.updateCroppedImage();
    }

    handleZoom(event) {
        if (event.deltaY < 0) {
            this.zoom = Math.min(3, this.zoom + 0.05);
        } else {
            this.zoom = Math.max(0.1, this.zoom - 0.05);
        }
        this.updatePreviewTransform();
        this.updateZoomUI();
        this.updateCroppedImage();
    }

    updatePreviewTransform() {
        if (this.previewImage) {
            this.previewImage.style.transform = `scale(${this.zoom}) translate(${this.posX}px, ${this.posY}px)`;
        }
    }

    updateZoomUI() {
        const zoomPercent = Math.round(this.zoom * 100);
        if (this.zoomSlider) {
            this.zoomSlider.value = zoomPercent;
        }
        if (this.zoomText) {
            this.zoomText.textContent = `${zoomPercent}%`;
        }
    }

    startDrag(event) {
        this.isMoving = true;
        this.startX = event.clientX - this.posX;
        this.startY = event.clientY - this.posY;
        if (this.previewImage) {
            this.previewImage.style.cursor = 'grabbing';
        }
    }

    onDrag(event) {
        if (!this.isMoving) return;
        this.posX = event.clientX - this.startX;
        this.posY = event.clientY - this.startY;
        this.updatePreviewTransform();
    }

    stopDrag() {
        if (this.isMoving) {
            this.isMoving = false;
            if (this.previewImage) {
                this.previewImage.style.cursor = 'grab';
            }
            this.updateCroppedImage();
        }
    }

    resetPosition() {
        this.zoom = 1;
        this.posX = 0;
        this.posY = 0;
        this.updatePreviewTransform();
        this.updateZoomUI();
        this.updateCroppedImage();
    }

    clearImage() {
        this.previewUrl = null;
        this.originalImage = null;
        if (this.fileInput) this.fileInput.value = '';
        if (this.croppedInput) this.croppedInput.value = '';
        if (this.previewImage) this.previewImage.src = '';
        this.resetPosition();
        this.hidePreview();
    }

    updateCroppedImage() {
        if (!this.originalImage || !this.previewUrl || !this.cropCanvas) return;

        const canvas = this.cropCanvas;
        const ctx = canvas.getContext('2d');

        if (!this.previewImage || !this.previewContainer) return;

        canvas.width = this.outputSize;
        canvas.height = this.outputSize;

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

        // Clear and create circular clip
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
                srcX, srcY, Math.max(srcW, srcH), Math.max(srcW, srcH),
                0, 0, this.outputSize, this.outputSize
            );
        } catch (e) {
            console.error('Crop error:', e);
        }

        // Store cropped data
        if (this.croppedInput) {
            this.croppedInput.value = canvas.toDataURL('image/png');
        }
    }

    // Public method to get cropped data URL
    getCroppedDataUrl() {
        return this.croppedInput?.value || null;
    }
}

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ImageUploadManager;
}
