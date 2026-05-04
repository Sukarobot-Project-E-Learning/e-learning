document.querySelectorAll(".accordion").forEach(acc => {
  acc.addEventListener("click", () => {
    acc.classList.toggle("open");
    let toggle = acc.querySelector(".toggle");
    toggle.textContent = acc.classList.contains("open") ? "−" : "+";
  });
});

document.querySelectorAll(".js-curriculum-details").forEach((detailsEl) => {
  const summaryEl = detailsEl.querySelector("summary");
  const contentEl = detailsEl.querySelector(".js-curriculum-content");

  if (!summaryEl || !contentEl) {
    return;
  }

  let animation = null;
  let isClosing = false;
  let isExpanding = false;
  const animationDuration = 520;

  detailsEl.style.overflow = "hidden";

  summaryEl.addEventListener("click", (event) => {
    event.preventDefault();

    const isOpen = detailsEl.open;

    if (isClosing || !isOpen) {
      openDetails();
    } else if (isExpanding || isOpen) {
      closeDetails();
    }
  });

  function closeDetails() {
    isClosing = true;

    const startHeight = `${detailsEl.offsetHeight}px`;
    const endHeight = `${summaryEl.offsetHeight}px`;

    if (animation) {
      animation.cancel();
    }

    animation = detailsEl.animate(
      {
        height: [startHeight, endHeight],
      },
      {
        duration: animationDuration,
        easing: "cubic-bezier(0.4, 0, 0.2, 1)",
      }
    );

    animation.onfinish = () => {
      detailsEl.open = false;
      animation = null;
      isClosing = false;
      detailsEl.style.height = "";
    };

    animation.oncancel = () => {
      animation = null;
      isClosing = false;
    };
  }

  function openDetails() {
    detailsEl.style.height = `${detailsEl.offsetHeight}px`;
    detailsEl.open = true;

    requestAnimationFrame(() => {
      expandDetails();
    });
  }

  function expandDetails() {
    isExpanding = true;

    const startHeight = `${detailsEl.offsetHeight}px`;
    const endHeight = `${summaryEl.offsetHeight + contentEl.offsetHeight}px`;

    if (animation) {
      animation.cancel();
    }

    animation = detailsEl.animate(
      {
        height: [startHeight, endHeight],
      },
      {
        duration: animationDuration,
        easing: "cubic-bezier(0.4, 0, 0.2, 1)",
      }
    );

    animation.onfinish = () => {
      animation = null;
      isExpanding = false;
      detailsEl.style.height = "";
    };

    animation.oncancel = () => {
      animation = null;
      isExpanding = false;
    };
  }
});
