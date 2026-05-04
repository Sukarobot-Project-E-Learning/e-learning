document.addEventListener('DOMContentLoaded', () => {
  const progressBar = document.getElementById('class-progress-bar');
  if (progressBar) {
    const progress = Number(progressBar.getAttribute('data-progress') || 0);
    const normalizedProgress = Math.max(0, Math.min(100, progress));
    progressBar.style.width = normalizedProgress + '%';
  }

  const searchInput = document.getElementById('lesson-search');
  const lessonEntries = Array.from(document.querySelectorAll('.lesson-entry[data-lesson-title]'));

  if (!searchInput || lessonEntries.length === 0) {
    return;
  }

  searchInput.addEventListener('input', () => {
    const term = searchInput.value.trim().toLowerCase();

    lessonEntries.forEach((entry) => {
      const title = entry.getAttribute('data-lesson-title') || '';
      const isVisible = term.length === 0 || title.includes(term);
      entry.style.display = isVisible ? '' : 'none';
    });
  });
});
