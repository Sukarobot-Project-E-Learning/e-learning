document.querySelectorAll(".accordion").forEach(acc => {
  acc.addEventListener("click", () => {
    acc.classList.toggle("open");
    let toggle = acc.querySelector(".toggle");
    toggle.textContent = acc.classList.contains("open") ? "−" : "+";
  });
});
