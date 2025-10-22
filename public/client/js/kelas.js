document.addEventListener("DOMContentLoaded", () => {
    const filterButtons = document.querySelectorAll(".filter-btn");
    const kelasCards = document.querySelectorAll(".kelas-card");
    const jumlahKelas = document.querySelector(".jumlah-kelas");

    function updateJumlahKelas(count) {
      if (jumlahKelas) jumlahKelas.textContent = `${count} Kelas`;
    }

    function activateButton(btn) {
      filterButtons.forEach(b => {
        b.classList.remove("bg-orange-500", "text-white");
        b.classList.add("text-blue-600");
      });
      btn.classList.add("bg-orange-500", "text-white");
      btn.classList.remove("text-blue-600");
    }

    function applyFilter(filter) {
      let visibleCount = 0;
      kelasCards.forEach(card => {
        const category = card.getAttribute("data-category");
        if (filter === "all" || category === filter) {
          card.style.display = "block";
          visibleCount++;
        } else {
          card.style.display = "none";
        }
      });
      updateJumlahKelas(visibleCount);
    }

    // handle filter button clicks
    filterButtons.forEach(btn => {
      btn.addEventListener("click", () => {
        const filter = btn.getAttribute("data-filter");
        activateButton(btn);
        applyFilter(filter);

        // update hash so it can be linked
        window.location.hash = `filter=${filter}`;
      });
    });

    // handle initial hash (e.g., #filter=webinar)
    function handleHashFilter() {
      const hash = window.location.hash;
      if (hash.startsWith("#filter=")) {
        const filterValue = hash.replace("#filter=", "");
        const targetBtn = document.querySelector(`[data-filter="${filterValue}"]`);
        if (targetBtn) {
          activateButton(targetBtn);
          applyFilter(filterValue);
          return true;
        }
      }
      return false;
    }

    // listen for hash changes (for same-page navigation)
    window.addEventListener("hashchange", handleHashFilter);

    // initial load
    if (!handleHashFilter()) {
      // Default: aktifkan tombol "Semua Kelas"
      const defaultBtn = document.querySelector('.filter-btn[data-filter="all"]');
      if (defaultBtn) {
        activateButton(defaultBtn);
        applyFilter("all");
      }
    }
  });
