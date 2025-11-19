/**
 * Indonesia Region Selector
 * Handles cascade dropdown for Provinsi, Kabupaten/Kota, Kecamatan, Kelurahan
 */

class RegionSelector {
    constructor(options = {}) {
        this.provinceSelect = document.getElementById(options.provinceId || 'province');
        this.citySelect = document.getElementById(options.cityId || 'city');
        this.districtSelect = document.getElementById(options.districtId || 'district');
        this.villageSelect = document.getElementById(options.villageId || 'village');
        this.apiBaseUrl = options.apiBaseUrl || '/api/regions';
        
        this.init();
    }

    init() {
        if (!this.provinceSelect) {
            console.error('Province select element not found');
            return;
        }

        // Load provinces on init
        this.loadProvinces();

        // Add event listeners
        if (this.provinceSelect) {
            this.provinceSelect.addEventListener('change', () => this.onProvinceChange());
        }
        
        if (this.citySelect) {
            this.citySelect.addEventListener('change', () => this.onCityChange());
        }
        
        if (this.districtSelect) {
            this.districtSelect.addEventListener('change', () => this.onDistrictChange());
        }
    }

    async loadProvinces() {
        try {
            const response = await fetch(`${this.apiBaseUrl}/provinces`);
            const provinces = await response.json();

            // Clear existing options except first
            this.provinceSelect.innerHTML = '<option value="">Pilih Provinsi</option>';

            // Add provinces
            provinces.forEach(province => {
                const option = document.createElement('option');
                option.value = province.id;
                option.textContent = province.nama;
                this.provinceSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading provinces:', error);
            this.showError(this.provinceSelect, 'Gagal memuat data provinsi');
        }
    }

    async onProvinceChange() {
        const provinceId = this.provinceSelect.value;
        
        // Reset dependent selects
        this.resetSelect(this.citySelect);
        this.resetSelect(this.districtSelect);
        this.resetSelect(this.villageSelect);

        if (!provinceId) {
            return;
        }

        // Load kabupaten/kota
        await this.loadKabupatenKota(provinceId);
    }

    async loadKabupatenKota(provinceId) {
        if (!this.citySelect) return;

        try {
            this.setLoading(this.citySelect, true);
            
            const response = await fetch(`${this.apiBaseUrl}/kabupaten-kota/${provinceId}`);
            const kabupatenKota = await response.json();

            this.resetSelect(this.citySelect);
            this.setLoading(this.citySelect, false);

            if (kabupatenKota.length === 0) {
                this.citySelect.innerHTML = '<option value="">Tidak ada data</option>';
                return;
            }

            // Add kabupaten/kota
            kabupatenKota.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.nama;
                this.citySelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading kabupaten/kota:', error);
            this.setLoading(this.citySelect, false);
            this.showError(this.citySelect, 'Gagal memuat data kabupaten/kota');
        }
    }

    async onCityChange() {
        const kabupatenId = this.citySelect.value;
        
        // Reset dependent selects
        this.resetSelect(this.districtSelect);
        this.resetSelect(this.villageSelect);

        if (!kabupatenId) {
            return;
        }

        // Load kecamatan
        await this.loadKecamatan(kabupatenId);
    }

    async loadKecamatan(kabupatenId) {
        if (!this.districtSelect) return;

        try {
            this.setLoading(this.districtSelect, true);
            
            const response = await fetch(`${this.apiBaseUrl}/kecamatan/${kabupatenId}`);
            const kecamatan = await response.json();

            this.resetSelect(this.districtSelect);
            this.setLoading(this.districtSelect, false);

            if (kecamatan.length === 0) {
                this.districtSelect.innerHTML = '<option value="">Tidak ada data</option>';
                return;
            }

            // Add kecamatan
            kecamatan.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.nama;
                this.districtSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading kecamatan:', error);
            this.setLoading(this.districtSelect, false);
            this.showError(this.districtSelect, 'Gagal memuat data kecamatan');
        }
    }

    async onDistrictChange() {
        const kecamatanId = this.districtSelect.value;
        
        // Reset dependent select
        this.resetSelect(this.villageSelect);

        if (!kecamatanId) {
            return;
        }

        // Load kelurahan
        await this.loadKelurahan(kecamatanId);
    }

    async loadKelurahan(kecamatanId) {
        if (!this.villageSelect) return;

        try {
            this.setLoading(this.villageSelect, true);
            
            const response = await fetch(`${this.apiBaseUrl}/kelurahan/${kecamatanId}`);
            const kelurahan = await response.json();

            this.resetSelect(this.villageSelect);
            this.setLoading(this.villageSelect, false);

            if (kelurahan.length === 0) {
                this.villageSelect.innerHTML = '<option value="">Tidak ada data</option>';
                return;
            }

            // Add kelurahan
            kelurahan.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.nama;
                this.villageSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading kelurahan:', error);
            this.setLoading(this.villageSelect, false);
            this.showError(this.villageSelect, 'Gagal memuat data kelurahan');
        }
    }

    resetSelect(select) {
        if (!select) return;
        select.innerHTML = '<option value="">Pilih...</option>';
        select.disabled = false;
    }

    setLoading(select, isLoading) {
        if (!select) return;
        
        if (isLoading) {
            select.disabled = true;
            select.innerHTML = '<option value="">Memuat...</option>';
        } else {
            select.disabled = false;
        }
    }

    showError(select, message) {
        if (!select) return;
        select.innerHTML = `<option value="">${message}</option>`;
        select.disabled = false;
    }

    // Method to set selected values (useful for edit forms)
    setSelectedProvince(provinceId) {
        if (this.provinceSelect && provinceId) {
            this.provinceSelect.value = provinceId;
            this.onProvinceChange();
        }
    }

    setSelectedCity(cityId) {
        if (this.citySelect && cityId) {
            this.citySelect.value = cityId;
            this.onCityChange();
        }
    }

    setSelectedDistrict(districtId) {
        if (this.districtSelect && districtId) {
            this.districtSelect.value = districtId;
            this.onDistrictChange();
        }
    }

    setSelectedVillage(villageId) {
        if (this.villageSelect && villageId) {
            this.villageSelect.value = villageId;
        }
    }
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Check if region selectors exist on the page
    if (document.getElementById('province')) {
        window.regionSelector = new RegionSelector({
            provinceId: 'province',
            cityId: 'city',
            districtId: 'district',
            villageId: 'village'
        });
    }
});

