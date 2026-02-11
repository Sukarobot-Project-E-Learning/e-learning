document.addEventListener("DOMContentLoaded", () => {
    const sortSelect = document.getElementById("sort-select");
    const container = document.querySelector(".kelas-container");
    const jumlahKelas = document.querySelector(".jumlah-kelas");
    const navItems = document.querySelectorAll(".nav-item");

    const searchInput = document.getElementById("program-search-input");

    if (!container) return;

    let cards = Array.from(container.querySelectorAll(".kelas-card"));
    let activeCategory = "all";

    // Get initial category from URL or button state
    const urlParams = new URLSearchParams(window.location.search);
    const urlCategory = urlParams.get('category');
    if (urlCategory) {
        activeCategory = urlCategory;
    } else {
        // Check which button has active class
        navItems.forEach(btn => {
            if (btn.classList.contains('text-blue-600')) {
                activeCategory = btn.dataset.filter || 'all';
            }
        });
    }

    // Category display names
    const categoryNames = {
        'all': 'semua program',
        'kursus': 'program kursus',
        'pelatihan': 'program pelatihan',
        'sertifikasi': 'program sertifikasi',
        'outing-class': 'program outing class',
        'outboard': 'program outboard'
    };

    // Hero Content Data
    const heroContent = {
        'all': {
            title: 'Kelas di E-Learning tersedia dari level <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-orange-500">Dasar hingga Profesional</span>',
            description: 'Tingkatkan kompetensi Anda sesuai kebutuhan industri terkini dengan kurikulum yang terstruktur dan mentor berpengalaman.'
        },
        'kursus': {
            title: 'Tingkatkan keahlianmu dengan berbagai <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-orange-500">Kursus Intensif</span>',
            description: 'Materi dirancang oleh ahli untuk pemula hingga profesional.'
        },
        'pelatihan': {
            title: 'Ikuti pelatihan praktis untuk <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-orange-500">Mengasah Keterampilan</span>',
            description: 'Tingkatkan soft skill dan hard skill Anda untuk dunia kerja.'
        },
        'sertifikasi': {
            title: 'Dapatkan pengakuan profesional melalui <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-orange-500">Program Sertifikasi</span>',
            description: 'Validasi keahlian Anda dengan sertifikat berstandar industri nasional dan internasional.'
        },
        'outing-class': {
            title: 'Belajar di luar kelas dengan <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-orange-500">Pengalaman Langsung</span>',
            description: 'Kegiatan edukatif yang menyenangkan dan interaktif untuk semua usia.'
        },
        'outboard': {
            title: 'Bangun karakter dan kerjasama tim melalui <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-orange-500">Program Outboard</span>',
            description: 'Kegiatan luar ruangan yang menantang untuk meningkatkan kepemimpinan dan soliditas tim.'
        }
    };

    // Store original index for sorting
    cards.forEach((c, i) => (c.dataset.origIndex = i));

    const updateJumlahKelas = (count, searchTerm = '') => {
        if (jumlahKelas) {
            const categoryText = categoryNames[activeCategory] || 'program';
            let text = `Menampilkan ${count} ${categoryText}`;
            if (searchTerm) {
                text += ` (hasil pencarian "${searchTerm}")`;
            }
            jumlahKelas.innerHTML = `<span class="w-2 h-8 bg-blue-600 rounded-full inline-block"></span> ${text}`;
        }
    };

    const updateHero = (category) => {
        const titleEl = document.getElementById('hero-title');
        const descEl = document.getElementById('hero-description');
        const content = heroContent[category] || heroContent['all'];

        if (titleEl) titleEl.innerHTML = content.title;
        if (descEl) descEl.textContent = content.description;
    };

    function getSlots(card) {
        if (card.dataset.slots) return parseInt(card.dataset.slots, 10) || 0;
        return 0;
    }

    function normalize(s = "") {
        return String(s).trim().replace(/\s+/g, " ").toLowerCase();
    }

    function matchesCategory(card) {
        if (!activeCategory || activeCategory === "all") return true;
        const cardCategory = normalize(card.dataset.category || "");
        return cardCategory === normalize(activeCategory);
    }

    function matchesSearch(card, term) {
        if (!term) return true;
        const title = normalize(card.querySelector('h3')?.innerText || "");
        const description = normalize(card.querySelector('p.text-gray-600')?.innerText || "");
        // Also search in instructor name if desired, but sticking to title/desc for consistency
        return title.includes(term) || description.includes(term);
    }

    function applySortAndFilter() {
        const sortValue = sortSelect ? sortSelect.value : "newest";
        const searchTerm = searchInput ? normalize(searchInput.value) : "";

        // Filter by category, availability, and search
        let visibleCards = cards.filter(c => {
            if (!matchesCategory(c)) return false;
            if (!matchesSearch(c, searchTerm)) return false;

            if (sortValue === 'available') {
                const isRunning = c.dataset.isRunning === 'true';
                const isFinished = c.dataset.isFinished === 'true';
                const slots = getSlots(c);

                // Tersedia = Punya slot AND Tidak Sedang Berjalan AND Tidak Selesai (Upcoming)
                return slots > 0 && !isRunning && !isFinished;
            }
            return true;
        });

        // Sorting
        visibleCards.sort((a, b) => {
            const da = a.dataset.date ? new Date(a.dataset.date).getTime() : 0;
            const db = b.dataset.date ? new Date(b.dataset.date).getTime() : 0;

            if (sortValue === 'oldest') {
                return da - db;
            } else {
                return db - da; // newest or available (default latest)
            }
        });

        // Update DOM - hide all first
        cards.forEach(c => c.classList.add("hidden"));

        // Show visible cards
        container.innerHTML = "";

        if (visibleCards.length === 0) {
            container.innerHTML = `
                <div class="col-span-full w-full flex flex-col items-center justify-center py-20 text-center">
                    <div class="relative w-48 h-48 mb-6 animate-bounce" style="animation-duration: 3s;">
                        <!-- Animated Illustration (Robotic/Tech Theme) -->
                        <svg class="w-full h-full drop-shadow-xl" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Floating Elements -->
                            <circle cx="100" cy="100" r="80" class="fill-blue-50 animate-pulse" style="animation-duration: 4s;"/>
                            
                            <!-- Robot Head / Search Icon Composite -->
                            <path d="M60 90C60 67.9086 77.9086 50 100 50C122.091 50 140 67.9086 140 90V130C140 141.046 131.046 150 120 150H80C68.9543 150 60 141.046 60 130V90Z" class="fill-white"/>
                            <rect x="75" y="80" width="15" height="15" rx="7.5" class="fill-blue-200"/>
                            <rect x="110" y="80" width="15" height="15" rx="7.5" class="fill-blue-200"/>
                            <path d="M85 115C85 115 90 122 100 122C110 122 115 115 115 115" stroke="#93C5FD" stroke-width="4" stroke-linecap="round"/>
                            
                            <!-- Gear Icon (Rotating) -->
                            <g class="origin-center animate-[spin_10s_linear_infinite]" style="transform-box: fill-box;">
                                <path d="M160 50L170 45L165 35L155 40L160 50Z" class="fill-orange-400"/>
                                <circle cx="160" cy="45" r="3" class="fill-white"/>
                            </g>

                            <!-- Search Magnifier -->
                            <path d="M130 130L150 150" stroke="#F97316" stroke-width="8" stroke-linecap="round"/>
                            <circle cx="125" cy="125" r="15" class="stroke-orange-500 fill-white" stroke-width="4"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Belum Ada Program</h3>
                    <p class="text-gray-500 max-w-md mx-auto mb-8 text-lg">
                        Saat ini kami sedang menyiapkan program terbaik untuk kategori ini. <br>Silakan cek kategori lainnya!
                    </p>
                </div>
            `;
        } else {
            visibleCards.forEach(c => {
                container.appendChild(c);
                c.classList.remove("hidden");
            });
        }

        updateJumlahKelas(visibleCards.length, searchTerm ? searchInput.value : '');
    }

    // Tab Click Handlers
    navItems.forEach(btn => {
        btn.addEventListener("click", (e) => {
            e.preventDefault();

            // Update active category
            activeCategory = btn.dataset.filter;

            // Update URL without reload
            const newUrl = activeCategory === 'all'
                ? window.location.pathname
                : `${window.location.pathname}?category=${activeCategory}`;
            window.history.pushState({ category: activeCategory }, '', newUrl);

            // Update UI - tab highlighting
            navItems.forEach(b => {
                b.classList.remove("text-blue-600", "border-b-2", "border-blue-600", "font-bold");
                b.classList.add("text-gray-500", "font-medium");
            });
            btn.classList.remove("text-gray-500", "font-medium");
            btn.classList.add("text-blue-600", "border-b-2", "border-blue-600", "font-bold");
            btn.classList.add("text-gray-500", "font-medium"); // This line seems redundant/wrong logic in original provided snippet, fixing highlighting here
            // Correct highlighting logic:

            navItems.forEach(b => {
                b.classList.remove("text-blue-600", "border-b-2", "border-blue-600", "font-bold");
                b.classList.add("text-gray-500", "font-medium");
            });
            btn.classList.remove("text-gray-500", "font-medium");
            btn.classList.add("text-blue-600", "border-b-2", "border-blue-600", "font-bold");


            // Apply filter instantly
            applySortAndFilter();
            updateHero(activeCategory);
        });
    });

    // Sort change handler
    if (sortSelect) {
        sortSelect.addEventListener("change", applySortAndFilter);
    }

    // Search input handler
    if (searchInput) {
        searchInput.addEventListener("input", applySortAndFilter);
    }

    // Handle browser back/forward
    window.addEventListener('popstate', (e) => {
        if (e.state && e.state.category) {
            activeCategory = e.state.category;
        } else {
            activeCategory = 'all';
        }

        // Update tab highlighting
        navItems.forEach(btn => {
            if (btn.dataset.filter === activeCategory) {
                btn.classList.add("text-blue-600", "border-b-2", "border-blue-600", "font-bold");
                btn.classList.remove("text-gray-500", "font-medium");
            } else {
                btn.classList.remove("text-blue-600", "border-b-2", "border-blue-600", "font-bold");
                btn.classList.add("text-gray-500", "font-medium");
            }
        });

        applySortAndFilter();
        updateHero(activeCategory);
    });

    // Initial apply
    applySortAndFilter();
    updateHero(activeCategory);

    // Navigation Scroll Logic
    const navContainer = document.getElementById("program-nav");
    const btnLeft = document.getElementById("nav-scroll-left");
    const btnRight = document.getElementById("nav-scroll-right");

    if (navContainer && btnLeft && btnRight) {
        const checkScroll = () => {
            // Show/Hide Left Button
            if (navContainer.scrollLeft > 0) {
                btnLeft.classList.remove("hidden");
            } else {
                btnLeft.classList.add("hidden");
            }

            // Show/Hide Right Button
            // Tolerance of 1px for float calculation differences
            if (navContainer.scrollLeft + navContainer.clientWidth < navContainer.scrollWidth - 1) {
                btnRight.classList.remove("hidden");
            } else {
                btnRight.classList.add("hidden");
            }
        };

        btnLeft.addEventListener("click", () => {
            navContainer.scrollBy({ left: -200, behavior: "smooth" });
        });

        btnRight.addEventListener("click", () => {
            navContainer.scrollBy({ left: 200, behavior: "smooth" });
        });

        navContainer.addEventListener("scroll", checkScroll);
        window.addEventListener("resize", checkScroll);

        // Initial check
        checkScroll();
    }
});
