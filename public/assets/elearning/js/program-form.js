function programForm(config = {}) {
    return {
        currentStep: 1,
        totalSteps: 5,
        isAdmin: config.isAdmin || false,

        // Data Models
        title: config.data?.title || '',
        instructor_id: config.data?.instructor_id || '',
        category: config.data?.category || '',
        type: config.data?.type || '',
        description: config.data?.description || '',
        
        
        // Quota (Admin: quota, Instructor: available_slots)
        quota: config.data?.quota || '', 
        
        price: config.data?.price || '',
        
        start_date: config.data?.start_date || '',
        start_time: config.data?.start_time || '',
        end_date: config.data?.end_date || '',
        end_time: config.data?.end_time || '',
        
        province: config.data?.province || '',
        city: config.data?.city || '',
        district: config.data?.district || '',
        village: config.data?.village || '',
        full_address: config.data?.full_address || '',
        
        zoom_link: config.data?.zoom_link || '',
        
        image: null,
        imagePreview: config.data?.image_url || null,
        existingImage: config.data?.image || null,
        
        tools: config.data?.tools || [],
        materials: config.data?.materials || [],
        benefits: config.data?.benefits || [],
        
        errors: {},
        
        init() {
            this.$watch('start_time', () => this.updateAllMaterialDurations());
            this.$watch('end_time', () => this.updateAllMaterialDurations());
        },

        validateStep(step) {
            this.errors = {};
            let isValid = true;

            if (step === 1) {
                if (!this.title) {
                    this.errors.title = "Judul program wajib diisi.";
                    isValid = false;
                } else if (this.title.length > 255) {
                    this.errors.title = "Judul program maksimal 255 karakter.";
                    isValid = false;
                }

                if (this.isAdmin && !this.instructor_id) {
                    this.errors.instructor_id = "Instruktur wajib dipilih.";
                    isValid = false;
                }

                if (!this.category) {
                    this.errors.category = "Kategori wajib dipilih.";
                    isValid = false;
                }

                if (!this.type) {
                    this.errors.type = "Tipe program wajib dipilih.";
                    isValid = false;
                }

                if (!this.description) {
                    this.errors.description = "Deskripsi wajib diisi.";
                    isValid = false;
                }
            }

            if (step === 2) {
                if (!this.quota) {
                    this.errors.quota = "Kuota peserta wajib diisi.";
                    isValid = false;
                } else if (Number(this.quota) < 1) {
                    this.errors.quota = "Kuota peserta minimal 1.";
                    isValid = false;
                }

                if (this.price === '' || this.price === null) {
                    this.errors.price = "Harga wajib diisi.";
                    isValid = false;
                } else {
                     const priceVal = Number(this.price);
                     if (isNaN(priceVal)) {
                        this.errors.price = "Harga harus berupa angka.";
                        isValid = false;
                    } else if (priceVal < 0) {
                        this.errors.price = "Harga tidak boleh negatif.";
                        isValid = false;
                    }
                }
            }

            if (step === 3) {
                 if (!this.start_date) { this.errors.start_date = "Tanggal mulai wajib diisi."; isValid = false; }
                 
                 if (!this.end_date) { 
                     this.errors.end_date = "Tanggal selesai wajib diisi."; 
                     isValid = false; 
                 } else if (this.start_date) {
                     if (new Date(this.end_date) < new Date(this.start_date)) {
                         this.errors.end_date = "Tanggal selesai harus sama atau setelah tanggal mulai.";
                         isValid = false;
                     }
                 }

                 if (!this.start_time) { this.errors.start_time = "Waktu mulai wajib diisi."; isValid = false; }
                 if (!this.end_time) { this.errors.end_time = "Waktu selesai wajib diisi."; isValid = false; }

                 if (this.type === 'offline') {
                     if (!this.province) { this.errors.province = "Provinsi wajib dipilih."; isValid = false; }
                     if (!this.city) { this.errors.city = "Kabupaten/Kota wajib dipilih."; isValid = false; }
                     if (!this.district) { this.errors.district = "Kecamatan wajib dipilih."; isValid = false; }
                     if (!this.village) { this.errors.village = "Kelurahan/Desa wajib dipilih."; isValid = false; }
                     if (!this.full_address) { this.errors.full_address = "Alamat lengkap wajib diisi."; isValid = false; }
                 } else if (this.type === 'online') {
                     if (!this.zoom_link) { this.errors.zoom_link = "Link Zoom/Google Meet wajib diisi."; isValid = false; }
                 }
            }

            if (step === 4) {
                // Materials validation can be added here if needed
                isValid = true;
            }

            if (step === 5) {
                if (!this.image && !this.existingImage) {
                    this.errors.image = "Gambar program wajib diunggah.";
                    isValid = false;
                }
            }

            return isValid;
        },

        nextStep() {
            if (this.validateStep(this.currentStep)) {
                this.currentStep++;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },
        
        prevStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },

        addTool() {
            this.tools.push('');
        },
        removeTool(index) {
            this.tools.splice(index, 1);
        },

        addBenefit() {
            this.benefits.push('');
        },
        removeBenefit(index) {
            this.benefits.splice(index, 1);
        },
        
        addMaterial() {
            const duration = this.getCalculatedDuration();
            this.materials.push({
                title: '',
                duration_hours: duration.hours,
                duration_minutes: duration.minutes,
                description: ''
            });
        },
        removeMaterial(index) {
            this.materials.splice(index, 1);
        },
        
        getCalculatedDuration() {
            if (this.start_time && this.end_time) {
                const start = this.start_time.split(':');
                const end = this.end_time.split(':');

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
        },

        updateAllMaterialDurations() {
            const duration = this.getCalculatedDuration();
            this.materials.forEach((material) => {
                material.duration_hours = duration.hours;
                material.duration_minutes = duration.minutes;
            });
        },
        
        validateImage(event) {
             const file = event.target.files[0];
             if (file) {
                 if (file.size > 2 * 1024 * 1024) {
                     this.errors.image = "Ukuran gambar maksimal 2MB.";
                     event.target.value = ''; 
                     this.image = null;
                     this.imagePreview = null;
                     return;
                 }
                 const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                 if (!validTypes.includes(file.type)) {
                     this.errors.image = "Format gambar harus jpeg, png, jpg, gif, atau webp.";
                     event.target.value = '';
                     this.image = null;
                     this.imagePreview = null;
                     return;
                 }
                 this.errors.image = null;
                 this.image = file;

                 // Set preview
                 const reader = new FileReader();
                 reader.onload = (e) => {
                     this.imagePreview = e.target.result;
                 };
                 reader.readAsDataURL(file);
             } else {
                 this.image = null;
                 this.imagePreview = null;
             }
        }
    };
}
