  // Slider
  let index = 0;
  const slider = document.getElementById('slider');
  if(slider){
    const slides = slider.children;
    setInterval(() => {
      index = (index + 1) % slides.length;
      slider.style.transform = `translateX(-${index * 100}%)`;
    }, 4000);
  }

  // Filter Event
  function filterEvents(category) {
    const events = document.querySelectorAll('.event');
    events.forEach(event => {
      if (category === 'all' || event.classList.contains(category)) {
        event.classList.remove("hidden");
        event.style.opacity = 0;
        event.style.transform = "translateY(20px)";
        setTimeout(() => {
          event.style.transition = "all 0.4s ease";
          event.style.opacity = 1;
          event.style.transform = "translateY(0)";
        }, 50);
      } else {
        event.classList.add("hidden");
      }
    });
  }

  // View More Event Animation
  const viewMoreBtn = document.getElementById("viewMoreBtn");
  const allEvents = document.querySelectorAll("#eventGrid .event");
  const initialShow = 8; // jumlah event tampil awal

  // sembunyikan semua event setelah initialShow
  allEvents.forEach((event, i) => {
    if (i >= initialShow) {
      event.classList.add("hidden");
      event.style.opacity = 0;
      event.style.transform = "translateY(20px)";
    }
  });

  if(viewMoreBtn){
    viewMoreBtn.addEventListener("click", () => {
      const hiddenEvents = Array.from(allEvents).filter(e => e.classList.contains("hidden"));
      hiddenEvents.forEach((event, i) => {
        setTimeout(() => {
          event.classList.remove("hidden");
          event.style.transition = "all 0.4s ease";
          event.style.opacity = 1;
          event.style.transform = "translateY(0)";
        }, i * 100); // efek stagger tiap 100ms
      });
      viewMoreBtn.style.display = "none"; // hilangkan tombol setelah klik
    });
  }