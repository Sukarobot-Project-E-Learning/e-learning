document.addEventListener("DOMContentLoaded", () => {
    const sortSelect = document.getElementById("sort-select");
    const container = document.querySelector(".kelas-container");
    const jumlahKelas = document.querySelector(".jumlah-kelas");
    const navItems = document.querySelectorAll(".nav-item");
    const heroTitle = document.getElementById("hero-title");

    if (!container) return;

    let cards = Array.from(container.querySelectorAll(".kelas-card"));
    let activeCategory = "all";

    // Hero Descriptions
    const heroDescriptions = {
        all: "Kelas di E-Learning tersedia dari level <br> dasar hingga profesional sesuai kebutuhan <br> industri terkini.",
        kursus: "Tingkatkan keahlianmu dengan berbagai <br> kursus intensif yang dirancang oleh ahli.",
        pelatihan: "Ikuti pelatihan praktis untuk <br> mengasah keterampilan teknis dan soft skill.",
        sertifikasi: "Dapatkan pengakuan profesional <br> melalui program sertifikasi berstandar industri.",
        outingclass: "Belajar di luar kelas dengan <br> pengalaman langsung yang menyenangkan.",
        outboard: "Program outboard untuk <br> pengembangan karakter dan kerja sama tim."
    };

    // Store original index for "default" sort if needed, though we usually sort by date
    cards.forEach((c, i) => (c.dataset.origIndex = i));

    const updateJumlahKelas = (count) => {
        if (jumlahKelas) jumlahKelas.textContent = `Menampilkan ${count} program`;
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
        const raw = card.dataset.category || "";
        const cats = raw.split(",").map((c) => normalize(c));
        return cats.includes(normalize(activeCategory));
    }

    function applySortAndFilter() {
        const sortValue = sortSelect ? sortSelect.value : "newest";

        let visibleCards = cards.filter(c => {
            if (!matchesCategory(c)) return false;

            if (sortValue === 'available') {
                return getSlots(c) > 0;
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
                // Newest or Available (sorted by newest)
                return db - da;
            }
        });

        // Update DOM
        container.innerHTML = "";
        visibleCards.forEach(c => {
            container.appendChild(c);
            c.classList.remove("hidden");
        });

        updateJumlahKelas(visibleCards.length);
    }

    // Nav Click Handlers
    navItems.forEach(btn => {
        btn.addEventListener("click", () => {
            // Update UI
            navItems.forEach(b => {
                b.classList.remove("text-blue-600", "border-b-2", "border-blue-600", "font-semibold");
                b.classList.add("text-gray-500", "font-medium");
            });
            btn.classList.remove("text-gray-500", "font-medium");
            btn.classList.add("text-blue-600", "border-b-2", "border-blue-600", "font-semibold");

            // Update Filter
            activeCategory = btn.dataset.filter;

            // Update Hero Text
            if (heroTitle && heroDescriptions[activeCategory]) {
                heroTitle.innerHTML = heroDescriptions[activeCategory];
            }

            applySortAndFilter();
        });
    });

    if (sortSelect) {
        sortSelect.addEventListener("change", applySortAndFilter);
    }

    // Initial apply
    applySortAndFilter();
});
