document.addEventListener('DOMContentLoaded', () => {
    // --- Scroll Logic ---
    const scrollContainer = document.getElementById('program-nav');
    const leftBtn = document.getElementById('scroll-left');
    const rightBtn = document.getElementById('scroll-right');

    if (scrollContainer && leftBtn && rightBtn) {
        const checkScroll = () => {
            if (scrollContainer.scrollLeft > 0) {
                leftBtn.classList.remove('opacity-0', 'pointer-events-none');
                leftBtn.classList.remove('hidden');
            } else {
                leftBtn.classList.add('opacity-0', 'pointer-events-none');
            }

            if (scrollContainer.scrollLeft < (scrollContainer.scrollWidth - scrollContainer.clientWidth - 10)) {
                rightBtn.classList.remove('opacity-0', 'pointer-events-none');
            } else {
                rightBtn.classList.add('opacity-0', 'pointer-events-none');
            }
        };

        leftBtn.addEventListener('click', () => {
            scrollContainer.scrollBy({ left: -200, behavior: 'smooth' });
        });

        rightBtn.addEventListener('click', () => {
            scrollContainer.scrollBy({ left: 200, behavior: 'smooth' });
        });

        scrollContainer.addEventListener('scroll', checkScroll);
        checkScroll();
        window.addEventListener('resize', checkScroll);
    }

    // --- Filtering Logic ---
    const filterBtns = document.querySelectorAll('.filter-btn');
    // We'll select program cards dynamically or wait for them to be present
    // But DOMPovided ensures they are there.

    function getCards() {
        return document.querySelectorAll('.program-card');
    }

    function setActiveButton(category) {
        filterBtns.forEach(btn => {
            if (btn.dataset.filter === category) {
                btn.classList.add('bg-blue-500', 'text-white');
                btn.classList.remove('text-gray-700', 'bg-gray-100', 'hover:bg-gray-200');
            } else {
                btn.classList.remove('bg-blue-500', 'text-white');
                btn.classList.add('text-gray-700', 'bg-gray-100', 'hover:bg-gray-200');
            }
        });
    }

    function filterCards(category) {
        const cards = getCards();
        const noProgramMsg = document.getElementById('no-program-message');
        let visibleCount = 0;

        cards.forEach(card => {
            const cardCategory = card.dataset.category || '';
            if (category === 'Semua Program' || cardCategory.toLowerCase().trim() === category.toLowerCase().trim()) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (noProgramMsg) {
            if (visibleCount === 0) {
                noProgramMsg.classList.remove('hidden');
            } else {
                noProgramMsg.classList.add('hidden');
            }
        }
    }

    function updateURL(category) {
        const url = new URL(window.location);
        if (category === 'Semua Program') {
            url.searchParams.delete('category');
        } else {
            url.searchParams.set('category', category.toLowerCase());
        }
        window.history.pushState({}, '', url);
    }

    // Initialize
    const urlParams = new URLSearchParams(window.location.search);
    const currentParam = urlParams.get('category');

    let foundBtn = null;
    if (currentParam) {
        foundBtn = Array.from(filterBtns).find(b => b.dataset.filter.toLowerCase() === currentParam.toLowerCase());
    }

    if (foundBtn) {
        const cat = foundBtn.dataset.filter;
        setActiveButton(cat);
        filterCards(cat);
    } else {
        // Default state is already 'Semua Program' active visually in HTML? 
        // No, we set classes in HTML matching 'Semua Program' as active.
        // But let's run filterCards('Semua Program') just in case.
        filterCards('Semua Program');
    }

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const category = btn.dataset.filter;
            setActiveButton(category);
            filterCards(category);
            updateURL(category);
        });
    });
});