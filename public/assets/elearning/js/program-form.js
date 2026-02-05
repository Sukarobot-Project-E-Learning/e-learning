/**
 * Program Form Manager
 * Vanilla JS handler for multi-step program creation/edit form
 */
class ProgramFormManager {
    constructor(containerId = 'programFormContainer') {
        this.container = document.getElementById(containerId);
        if (!this.container) {
            console.error('Program form container not found');
            return;
        }

        this.form = this.container.querySelector('#programForm');
        this.currentStep = 1;
        this.totalSteps = 5;
        this.isAdmin = this.container.dataset.isAdmin === 'true';
        this.isEdit = this.container.dataset.isEdit === 'true';
        this.primaryColor = this.container.dataset.primaryColor || 'orange';
        this.materialIndex = 0;

        this.init();
    }

    init() {
        this.cacheElements();
        this.bindEvents();
        this.initTypeToggle();
        this.updateNavigationState();
        this.countExistingMaterials();
        this.handleServerValidationErrors();
        this.loadLocationDataFromContainer();
    }

    // Load location data from container data attributes (for edit mode) or hidden inputs (for validation errors)
    loadLocationDataFromContainer() {
        // Check data attributes first (edit mode), then hidden inputs (validation error redirect)
        let provinceId = this.container.dataset.locationProvince;
        let cityId = this.container.dataset.locationCity;
        let districtId = this.container.dataset.locationDistrict;
        let villageId = this.container.dataset.locationVillage;
        
        // If no data attributes, check hidden inputs (for old() values after validation errors)
        if (!provinceId) {
            const hiddenProvinceId = this.container.querySelector('#input-province-id');
            const hiddenCityId = this.container.querySelector('#input-city-id');
            const hiddenDistrictId = this.container.querySelector('#input-district-id');
            const hiddenVillageId = this.container.querySelector('#input-village-id');
            
            provinceId = hiddenProvinceId?.value || '';
            cityId = hiddenCityId?.value || '';
            districtId = hiddenDistrictId?.value || '';
            villageId = hiddenVillageId?.value || '';
        }
        
        // Only proceed if we have at least a province ID
        if (!provinceId) return;
        
        // Load location data after region selector is initialized
        const locationData = {
            province_id: provinceId,
            city_id: cityId,
            district_id: districtId,
            village_id: villageId
        };
        
        this.loadLocationData(locationData);
    }

    // Handle server-side validation errors - jump to the step with errors
    handleServerValidationErrors() {
        // Check if there are any visible field errors from server-side validation
        const errorElements = this.container.querySelectorAll('.field-error');
        let hasErrors = false;
        let targetStep = 1;

        // Map field names to steps
        const fieldStepMap = {
            'title': 1, 'program': 1, 'instructor_id': 1, 'category': 1, 'type': 1, 'description': 1,
            'quota': 2, 'available_slots': 2, 'price': 2, 'tools': 2, 'benefits': 2,
            'start_date': 3, 'end_date': 3, 'start_time': 3, 'end_time': 3,
            'zoom_link': 3, 'province': 3, 'city': 3, 'district': 3, 'village': 3, 'full_address': 3,
            'materials': 4,
            'image': 5
        };

        // Check for error step data attribute
        const errorStep = this.container.dataset.errorStep;
        if (errorStep) {
            this.goToStep(parseInt(errorStep));
            return;
        }

        // Check for any pre-rendered error messages
        errorElements.forEach(el => {
            if (el.textContent && el.textContent.trim() !== '') {
                hasErrors = true;
                el.classList.remove('hidden');
                const field = el.dataset.field;
                if (field && fieldStepMap[field]) {
                    targetStep = Math.max(targetStep, fieldStepMap[field]);
                }
            }
        });

        if (hasErrors && targetStep > 1) {
            this.goToStep(targetStep);
        }
    }

    cacheElements() {
        // Navigation
        this.prevBtn = this.container.querySelector('#prevStepBtn');
        this.nextBtn = this.container.querySelector('#nextStepBtn');
        this.submitBtn = this.container.querySelector('#submitBtn');
        this.cancelLink = this.container.querySelector('#cancelLink');

        // Steps
        this.steps = this.container.querySelectorAll('.form-step');
        this.stepIndicators = this.container.querySelectorAll('.step-indicator');
        this.stepLines = this.container.querySelectorAll('.step-line');
        this.stepLabels = this.container.querySelectorAll('.step-label');

        // Dynamic lists containers
        this.toolsContainer = this.container.querySelector('#toolsContainer');
        this.benefitsContainer = this.container.querySelector('#benefitsContainer');
        this.materialsContainer = this.container.querySelector('#materialsContainer');
        this.emptyMaterialsState = this.container.querySelector('#emptyMaterialsState');

        // Image elements
        this.imageInput = this.container.querySelector('#input-image');
        this.imagePreview = this.container.querySelector('#imagePreview');
        this.uploadPlaceholder = this.container.querySelector('#uploadPlaceholder');
        this.removeImageBtn = this.container.querySelector('#removeImageBtn');

        // Type/Location fields
        this.onlineFields = this.container.querySelector('#onlineFields');
        this.offlineFields = this.container.querySelector('#offlineFields');
        this.typeRadios = this.container.querySelectorAll('input[name="type"]');
    }

    bindEvents() {
        // Navigation
        this.prevBtn?.addEventListener('click', () => this.prevStep());
        this.nextBtn?.addEventListener('click', () => this.nextStep());

        // Form submission
        this.form?.addEventListener('submit', (e) => this.handleSubmit(e));

        // Type toggle
        this.typeRadios.forEach(radio => {
            radio.addEventListener('change', () => this.handleTypeChange(radio.value));
        });

        // Dynamic lists - Add buttons
        this.container.querySelector('#addToolBtn')?.addEventListener('click', () => this.addTool());
        this.container.querySelector('#addBenefitBtn')?.addEventListener('click', () => this.addBenefit());
        this.container.querySelector('#addMaterialBtn')?.addEventListener('click', () => this.addMaterial());

        // Dynamic lists - Remove buttons (delegated)
        this.toolsContainer?.addEventListener('click', (e) => {
            if (e.target.closest('.remove-tool-btn')) {
                e.target.closest('.tool-item').remove();
            }
        });
        this.benefitsContainer?.addEventListener('click', (e) => {
            if (e.target.closest('.remove-benefit-btn')) {
                e.target.closest('.benefit-item').remove();
            }
        });
        this.materialsContainer?.addEventListener('click', (e) => {
            if (e.target.closest('.remove-material-btn')) {
                e.target.closest('.material-item').remove();
                this.updateEmptyMaterialsState();
                this.reindexMaterials();
            }
        });

        // Image upload
        this.imageInput?.addEventListener('change', (e) => this.handleImageUpload(e));
        this.removeImageBtn?.addEventListener('click', () => this.removeImage());

        // Region selector integration
        this.initRegionSelectors();
    }

    // ==================== STEP NAVIGATION ====================

    goToStep(step) {
        if (step < 1 || step > this.totalSteps) return;

        // Hide all steps
        this.steps.forEach(s => s.classList.add('hidden'));

        // Show target step
        const targetStep = this.container.querySelector(`.form-step[data-step="${step}"]`);
        if (targetStep) {
            targetStep.classList.remove('hidden');
            targetStep.classList.add('animate-fade-in');
        }

        this.currentStep = step;
        this.updateStepIndicators();
        this.updateNavigationState();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    nextStep() {
        if (this.validateStep(this.currentStep)) {
            this.goToStep(this.currentStep + 1);
        }
    }

    prevStep() {
        this.goToStep(this.currentStep - 1);
    }

    updateStepIndicators() {
        this.stepIndicators.forEach((indicator, index) => {
            const step = index + 1;
            indicator.classList.remove(
                `bg-${this.primaryColor}-600`, 'text-white', 'shadow-lg',
                'bg-green-600', 'bg-gray-200', 'text-gray-500'
            );

            if (step < this.currentStep) {
                // Completed
                indicator.classList.add('bg-green-600', 'text-white');
                indicator.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            } else if (step === this.currentStep) {
                // Current
                indicator.classList.add(`bg-${this.primaryColor}-600`, 'text-white', 'shadow-lg');
                indicator.textContent = step;
            } else {
                // Upcoming
                indicator.classList.add('bg-gray-200', 'text-gray-500', 'dark:bg-gray-700', 'dark:text-gray-400');
                indicator.textContent = step;
            }
        });

        // Update lines
        this.stepLines.forEach((line, index) => {
            const step = index + 1;
            line.classList.remove(`bg-${this.primaryColor}-600`, 'bg-green-500');
            if (step < this.currentStep) {
                line.classList.add('bg-green-500');
            }
        });

        // Update labels
        this.stepLabels.forEach((label, index) => {
            const step = index + 1;
            label.classList.remove(`text-${this.primaryColor}-600`, `dark:text-${this.primaryColor}-400`, 'font-medium');
            if (step <= this.currentStep) {
                label.classList.add(`text-${this.primaryColor}-600`, `dark:text-${this.primaryColor}-400`, 'font-medium');
            }
        });
    }

    updateNavigationState() {
        // Previous button
        if (this.currentStep === 1) {
            this.prevBtn?.classList.add('hidden');
            this.prevBtn?.classList.remove('flex');
            this.cancelLink?.classList.remove('hidden');
            this.cancelLink?.classList.add('flex');
        } else {
            this.prevBtn?.classList.remove('hidden');
            this.prevBtn?.classList.add('flex');
            this.cancelLink?.classList.add('hidden');
            this.cancelLink?.classList.remove('flex');
        }

        // Next/Submit buttons
        if (this.currentStep === this.totalSteps) {
            this.nextBtn?.classList.add('hidden');
            this.nextBtn?.classList.remove('flex');
            this.submitBtn?.classList.remove('hidden');
            this.submitBtn?.classList.add('flex');
        } else {
            this.nextBtn?.classList.remove('hidden');
            this.nextBtn?.classList.add('flex');
            this.submitBtn?.classList.add('hidden');
            this.submitBtn?.classList.remove('flex');
        }
    }

    // ==================== VALIDATION ====================

    validateStep(step) {
        this.clearErrors();
        let isValid = true;
        const errors = [];

        switch (step) {
            case 1:
                isValid = this.validateStep1(errors);
                break;
            case 2:
                isValid = this.validateStep2(errors);
                break;
            case 3:
                isValid = this.validateStep3(errors);
                break;
            case 4:
                // Materials are optional
                isValid = true;
                break;
            case 5:
                isValid = this.validateStep5(errors);
                break;
        }

        if (!isValid && errors.length > 0) {
            this.showValidationErrors(errors);
        }

        return isValid;
    }

    validateStep1(errors) {
        let isValid = true;
        const titleInput = this.container.querySelector('#input-title');
        const categoryInput = this.container.querySelector('#input-category');
        const descriptionInput = this.container.querySelector('#input-description');
        const checkedType = this.container.querySelector('input[name="type"]:checked');

        if (!titleInput?.value.trim()) {
            this.showFieldError('title', 'Judul program wajib diisi.');
            errors.push('Judul program wajib diisi.');
            isValid = false;
        } else if (titleInput.value.length > 255) {
            this.showFieldError('title', 'Judul program maksimal 255 karakter.');
            errors.push('Judul program maksimal 255 karakter.');
            isValid = false;
        }

        if (this.isAdmin) {
            const instructorInput = this.container.querySelector('#input-instructor_id');
            if (!instructorInput?.value) {
                this.showFieldError('instructor_id', 'Instruktur wajib dipilih.');
                errors.push('Instruktur wajib dipilih.');
                isValid = false;
            }
        }

        if (!categoryInput?.value) {
            this.showFieldError('category', 'Kategori wajib dipilih.');
            errors.push('Kategori wajib dipilih.');
            isValid = false;
        }

        if (!checkedType) {
            this.showFieldError('type', 'Tipe program wajib dipilih.');
            errors.push('Tipe program wajib dipilih.');
            isValid = false;
        }

        if (!descriptionInput?.value.trim()) {
            this.showFieldError('description', 'Deskripsi wajib diisi.');
            errors.push('Deskripsi wajib diisi.');
            isValid = false;
        }

        return isValid;
    }

    validateStep2(errors) {
        let isValid = true;
        const quotaInput = this.container.querySelector('#input-quota');
        const priceInput = this.container.querySelector('#input-price');

        if (!quotaInput?.value) {
            this.showFieldError('quota', 'Kuota peserta wajib diisi.');
            errors.push('Kuota peserta wajib diisi.');
            isValid = false;
        } else if (Number(quotaInput.value) < 1) {
            this.showFieldError('quota', 'Kuota peserta minimal 1.');
            errors.push('Kuota peserta minimal 1.');
            isValid = false;
        }

        if (priceInput?.value === '' || priceInput?.value === null) {
            this.showFieldError('price', 'Harga wajib diisi.');
            errors.push('Harga wajib diisi.');
            isValid = false;
        } else if (Number(priceInput.value) < 0) {
            this.showFieldError('price', 'Harga tidak boleh negatif.');
            errors.push('Harga tidak boleh negatif.');
            isValid = false;
        }

        return isValid;
    }

    validateStep3(errors) {
        let isValid = true;
        const startDate = this.container.querySelector('#input-start_date');
        const endDate = this.container.querySelector('#input-end_date');
        const startTime = this.container.querySelector('#input-start_time');
        const endTime = this.container.querySelector('#input-end_time');
        const selectedType = this.container.querySelector('input[name="type"]:checked')?.value;

        if (!startDate?.value) {
            this.showFieldError('start_date', 'Tanggal mulai wajib diisi.');
            errors.push('Tanggal mulai wajib diisi.');
            isValid = false;
        }

        if (!endDate?.value) {
            this.showFieldError('end_date', 'Tanggal selesai wajib diisi.');
            errors.push('Tanggal selesai wajib diisi.');
            isValid = false;
        } else if (startDate?.value && new Date(endDate.value) < new Date(startDate.value)) {
            this.showFieldError('end_date', 'Tanggal selesai harus sama atau setelah tanggal mulai.');
            errors.push('Tanggal selesai harus sama atau setelah tanggal mulai.');
            isValid = false;
        }

        if (!startTime?.value) {
            this.showFieldError('start_time', 'Waktu mulai wajib diisi.');
            errors.push('Waktu mulai wajib diisi.');
            isValid = false;
        }

        if (!endTime?.value) {
            this.showFieldError('end_time', 'Waktu selesai wajib diisi.');
            errors.push('Waktu selesai wajib diisi.');
            isValid = false;
        }

        if (selectedType === 'online') {
            const zoomLink = this.container.querySelector('#input-zoom_link');
            if (!zoomLink?.value.trim()) {
                this.showFieldError('zoom_link', 'Link Zoom/Google Meet wajib diisi.');
                errors.push('Link Zoom/Google Meet wajib diisi.');
                isValid = false;
            }
        } else if (selectedType === 'offline') {
            const province = this.container.querySelector('#input-province');
            const city = this.container.querySelector('#input-city');
            const district = this.container.querySelector('#input-district');
            const village = this.container.querySelector('#input-village');
            const fullAddress = this.container.querySelector('#input-full_address');

            if (!province?.value) {
                this.showFieldError('province', 'Provinsi wajib dipilih.');
                errors.push('Provinsi wajib dipilih.');
                isValid = false;
            }
            if (!city?.value) {
                this.showFieldError('city', 'Kabupaten/Kota wajib dipilih.');
                errors.push('Kabupaten/Kota wajib dipilih.');
                isValid = false;
            }
            if (!district?.value) {
                this.showFieldError('district', 'Kecamatan wajib dipilih.');
                errors.push('Kecamatan wajib dipilih.');
                isValid = false;
            }
            if (!village?.value) {
                this.showFieldError('village', 'Kelurahan/Desa wajib dipilih.');
                errors.push('Kelurahan/Desa wajib dipilih.');
                isValid = false;
            }
            if (!fullAddress?.value.trim()) {
                this.showFieldError('full_address', 'Alamat lengkap wajib diisi.');
                errors.push('Alamat lengkap wajib diisi.');
                isValid = false;
            }
        }

        return isValid;
    }

    validateStep5(errors) {
        let isValid = true;
        const imageInput = this.container.querySelector('#input-image');
        const existingImage = this.container.querySelector('#existing-image');

        const hasNewImage = imageInput?.files?.length > 0;
        const hasExistingImage = existingImage?.value;

        if (!hasNewImage && !hasExistingImage) {
            this.showFieldError('image', 'Gambar program wajib diunggah.');
            errors.push('Gambar program wajib diunggah.');
            isValid = false;
        }

        return isValid;
    }

    showFieldError(field, message) {
        const errorEl = this.container.querySelector(`.field-error[data-field="${field}"]`);
        if (errorEl) {
            errorEl.textContent = message;
            errorEl.classList.remove('hidden');
        }
    }

    // Show validation errors summary (no SweetAlert - just scroll to first error)
    showValidationErrors(errors) {
        // Simply scroll to the first visible error if any - no duplicate alert
        const firstError = this.container.querySelector('.field-error:not(.hidden)');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    clearErrors() {
        this.container.querySelectorAll('.field-error').forEach(el => {
            el.textContent = '';
            el.classList.add('hidden');
        });
    }

    // Check for server-side validation errors and jump to correct step
    checkServerErrors() {
        // Map field names to steps
        const fieldStepMap = {
            'title': 1, 'program': 1, 'instructor_id': 1, 'category': 1, 'type': 1, 'description': 1,
            'quota': 2, 'available_slots': 2, 'price': 2, 'tools': 2, 'benefits': 2,
            'start_date': 3, 'end_date': 3, 'start_time': 3, 'end_time': 3,
            'zoom_link': 3, 'province': 3, 'city': 3, 'district': 3, 'village': 3, 'full_address': 3,
            'materials': 4,
            'image': 5
        };

        // Find visible error messages
        const errorElements = this.container.querySelectorAll('.field-error:not(.hidden)');
        let targetStep = 1;

        errorElements.forEach(el => {
            const field = el.dataset.field;
            if (field && fieldStepMap[field]) {
                targetStep = Math.max(targetStep, fieldStepMap[field]);
            }
        });

        // Also check for Laravel validation errors passed via data attribute
        const errorStep = this.container.dataset.errorStep;
        if (errorStep) {
            targetStep = parseInt(errorStep);
        }

        return targetStep;
    }

    // ==================== TYPE TOGGLE ====================

    initTypeToggle() {
        const checkedType = this.container.querySelector('input[name="type"]:checked');
        if (checkedType) {
            this.handleTypeChange(checkedType.value);
        }
    }

    handleTypeChange(type) {
        // Update visual state
        this.container.querySelectorAll('.type-card').forEach(card => {
            card.classList.remove(
                `border-${this.primaryColor}-500`,
                `bg-${this.primaryColor}-50`,
                `dark:bg-${this.primaryColor}-900/20`
            );
            card.classList.add('border-gray-200', 'dark:border-gray-600');
        });

        const selectedCard = this.container.querySelector(`input[name="type"][value="${type}"]`)
            ?.closest('.type-option')?.querySelector('.type-card');
        if (selectedCard) {
            selectedCard.classList.remove('border-gray-200', 'dark:border-gray-600');
            selectedCard.classList.add(
                `border-${this.primaryColor}-500`,
                `bg-${this.primaryColor}-50`,
                `dark:bg-${this.primaryColor}-900/20`
            );
        }

        // Show/hide fields
        if (type === 'online') {
            this.onlineFields?.classList.remove('hidden');
            this.offlineFields?.classList.add('hidden');
        } else if (type === 'offline') {
            this.onlineFields?.classList.add('hidden');
            this.offlineFields?.classList.remove('hidden');
        }
    }

    // ==================== DYNAMIC LISTS ====================

    addTool() {
        const html = `
            <div class="tool-item flex gap-2 animate-fade-in">
                <input type="text" name="tools[]" 
                       class="flex-1 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:border-${this.primaryColor}-500 transition-all"
                       placeholder="Nama alat/bahan">
                <button type="button" class="remove-tool-btn p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        `;
        this.toolsContainer?.insertAdjacentHTML('beforeend', html);
    }

    addBenefit() {
        const html = `
            <div class="benefit-item flex gap-2 animate-fade-in">
                <input type="text" name="benefits[]"
                       class="flex-1 px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:border-${this.primaryColor}-500 transition-all"
                       placeholder="Manfaat yang didapat">
                <button type="button" class="remove-benefit-btn p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        `;
        this.benefitsContainer?.insertAdjacentHTML('beforeend', html);
    }

    countExistingMaterials() {
        const existingMaterials = this.materialsContainer?.querySelectorAll('.material-item');
        this.materialIndex = existingMaterials?.length || 0;
    }

    addMaterial() {
        const duration = this.getCalculatedDuration();
        const html = `
            <div class="material-item bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-200 dark:border-gray-600 animate-fade-in">
                <div class="flex items-start justify-between mb-3">
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400 material-number">Materi ${this.materialIndex + 1}</span>
                    <button type="button" class="remove-material-btn p-1 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="space-y-3">
                    <input type="text" name="materials[${this.materialIndex}][title]"
                           class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:border-${this.primaryColor}-500 transition-all"
                           placeholder="Judul Materi">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs text-gray-500 dark:text-gray-400">Jam</label>
                            <input type="number" name="materials[${this.materialIndex}][duration_hours]" min="0" value="${duration.hours}"
                                   class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm">
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 dark:text-gray-400">Menit</label>
                            <input type="number" name="materials[${this.materialIndex}][duration_minutes]" min="0" max="59" value="${duration.minutes}"
                                   class="w-full px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white text-sm">
                        </div>
                    </div>
                    <textarea name="materials[${this.materialIndex}][description]" rows="2"
                              class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:border-${this.primaryColor}-500 transition-all resize-none text-sm"
                              placeholder="Deskripsi materi (opsional)"></textarea>
                </div>
            </div>
        `;
        this.materialsContainer?.insertAdjacentHTML('beforeend', html);
        this.materialIndex++;
        this.updateEmptyMaterialsState();
    }

    reindexMaterials() {
        const materials = this.materialsContainer?.querySelectorAll('.material-item');
        materials?.forEach((item, index) => {
            // Update display number
            const numberEl = item.querySelector('.material-number');
            if (numberEl) numberEl.textContent = `Materi ${index + 1}`;

            // Update input names
            item.querySelectorAll('input, textarea').forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    input.setAttribute('name', name.replace(/materials\[\d+\]/, `materials[${index}]`));
                }
            });
        });
        this.materialIndex = materials?.length || 0;
    }

    updateEmptyMaterialsState() {
        const hasMaterials = this.materialsContainer?.querySelectorAll('.material-item').length > 0;
        if (hasMaterials) {
            this.emptyMaterialsState?.classList.add('hidden');
        } else {
            this.emptyMaterialsState?.classList.remove('hidden');
        }
    }

    getCalculatedDuration() {
        const startTime = this.container.querySelector('#input-start_time')?.value;
        const endTime = this.container.querySelector('#input-end_time')?.value;

        if (startTime && endTime) {
            const start = startTime.split(':');
            const end = endTime.split(':');
            const startMinutes = parseInt(start[0]) * 60 + parseInt(start[1]);
            const endMinutes = parseInt(end[0]) * 60 + parseInt(end[1]);
            let diffMinutes = endMinutes - startMinutes;
            if (diffMinutes < 0) diffMinutes += 24 * 60;
            return {
                hours: Math.floor(diffMinutes / 60),
                minutes: diffMinutes % 60
            };
        }
        return { hours: 0, minutes: 0 };
    }

    // ==================== IMAGE HANDLING ====================

    handleImageUpload(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Validate size
        if (file.size > 2 * 1024 * 1024) {
            this.showFieldError('image', 'Ukuran gambar maksimal 2MB.');
            e.target.value = '';
            return;
        }

        // Validate type
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            this.showFieldError('image', 'Format gambar harus jpeg, png, jpg, gif, atau webp.');
            e.target.value = '';
            return;
        }

        // Show preview
        const reader = new FileReader();
        reader.onload = (ev) => {
            const img = this.imagePreview?.querySelector('img');
            if (img) img.src = ev.target.result;
            this.uploadPlaceholder?.classList.add('hidden');
            this.imagePreview?.classList.remove('hidden');
        };
        reader.readAsDataURL(file);

        // Clear existing image flag
        const existingImageInput = this.container.querySelector('#existing-image');
        if (existingImageInput) existingImageInput.value = '';
    }

    removeImage() {
        this.imageInput.value = '';
        this.uploadPlaceholder?.classList.remove('hidden');
        this.imagePreview?.classList.add('hidden');
        
        const existingImageInput = this.container.querySelector('#existing-image');
        if (existingImageInput) existingImageInput.value = '';
    }

    // ==================== REGION SELECTORS ====================

    initRegionSelectors() {
        // Initialize RegionSelector with correct IDs for program form
        const provinceSelect = this.container.querySelector('#province-select');
        const citySelect = this.container.querySelector('#city-select');
        const districtSelect = this.container.querySelector('#district-select');
        const villageSelect = this.container.querySelector('#village-select');

        // Only initialize if province select exists (offline mode fields)
        if (provinceSelect && typeof RegionSelector !== 'undefined') {
            // Initialize RegionSelector with the correct element IDs
            this.regionSelector = new RegionSelector({
                provinceId: 'province-select',
                cityId: 'city-select',
                districtId: 'district-select',
                villageId: 'village-select'
            });
            
            // Expose region selector globally for edit pages to use
            window.regionSelector = this.regionSelector;
        }

        // Update hidden inputs when selects change (store text value AND ID)
        provinceSelect?.addEventListener('change', (e) => {
            const selected = e.target.options[e.target.selectedIndex];
            const hiddenInput = this.container.querySelector('#input-province');
            const hiddenIdInput = this.container.querySelector('#input-province-id');
            if (hiddenInput) {
                hiddenInput.value = selected?.text || '';
            }
            if (hiddenIdInput) {
                hiddenIdInput.value = e.target.value || '';
            }
        });

        citySelect?.addEventListener('change', (e) => {
            const selected = e.target.options[e.target.selectedIndex];
            const hiddenInput = this.container.querySelector('#input-city');
            const hiddenIdInput = this.container.querySelector('#input-city-id');
            if (hiddenInput) {
                hiddenInput.value = selected?.text || '';
            }
            if (hiddenIdInput) {
                hiddenIdInput.value = e.target.value || '';
            }
        });

        districtSelect?.addEventListener('change', (e) => {
            const selected = e.target.options[e.target.selectedIndex];
            const hiddenInput = this.container.querySelector('#input-district');
            const hiddenIdInput = this.container.querySelector('#input-district-id');
            if (hiddenInput) {
                hiddenInput.value = selected?.text || '';
            }
            if (hiddenIdInput) {
                hiddenIdInput.value = e.target.value || '';
            }
        });

        villageSelect?.addEventListener('change', (e) => {
            const selected = e.target.options[e.target.selectedIndex];
            const hiddenInput = this.container.querySelector('#input-village');
            const hiddenIdInput = this.container.querySelector('#input-village-id');
            if (hiddenInput) {
                hiddenInput.value = selected?.text || '';
            }
            if (hiddenIdInput) {
                hiddenIdInput.value = e.target.value || '';
            }
        });
    }

    // Method to load location data for edit mode
    loadLocationData(locationData) {
        if (!this.regionSelector || !locationData) return;

        const loadSequence = async () => {
            try {
                if (locationData.province_id) {
                    this.regionSelector.setSelectedProvince(locationData.province_id);
                    
                    // Wait for cities to load
                    await this.waitForSelectOptions('city-select', 1000);
                    
                    if (locationData.city_id) {
                        this.regionSelector.setSelectedCity(locationData.city_id);
                        
                        // Wait for districts to load
                        await this.waitForSelectOptions('district-select', 1000);
                        
                        if (locationData.district_id) {
                            this.regionSelector.setSelectedDistrict(locationData.district_id);
                            
                            // Wait for villages to load
                            await this.waitForSelectOptions('village-select', 1000);
                            
                            if (locationData.village_id) {
                                this.regionSelector.setSelectedVillage(locationData.village_id);
                            }
                        }
                    }
                }
            } catch (error) {
                console.error('Error loading location data:', error);
            }
        };

        // Start loading after a small delay to ensure RegionSelector is fully initialized
        setTimeout(loadSequence, 500);
    }

    // Helper to wait for select options to be populated
    waitForSelectOptions(selectId, timeout = 1000) {
        return new Promise((resolve) => {
            const select = document.getElementById(selectId);
            if (!select) {
                resolve();
                return;
            }

            const startTime = Date.now();
            const checkInterval = setInterval(() => {
                // Check if options are loaded (more than just the placeholder)
                if (select.options.length > 1 || Date.now() - startTime > timeout) {
                    clearInterval(checkInterval);
                    setTimeout(resolve, 100); // Small delay after options load
                }
            }, 100);
        });
    }

    // ==================== FORM SUBMISSION ====================

    handleSubmit(e) {
        if (!this.validateStep(this.currentStep)) {
            e.preventDefault();
            return false;
        }
        // Form will submit normally
        return true;
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('programFormContainer')) {
        window.programForm = new ProgramFormManager('programFormContainer');
    }
});
