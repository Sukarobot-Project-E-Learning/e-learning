/**
 * Reviews Manager
*/

class ReviewsManager {
  /**
   * @param {Array} reviews - Array of review objects
   * @param {Object} options - Configuration options
   */
  constructor(reviews, options = {}) {
    this.reviews = reviews || [];
    this.selectedRating = 'all';
    this.options = {
      containerMobileId: options.containerMobileId || 'reviewsMobileContainer',
      containerDesktopId: options.containerDesktopId || 'reviewsDesktopContainer',
      selectorId: options.selectorId || 'ratingSelector',
      ...options
    };

    this.mobileContainer = document.getElementById(this.options.containerMobileId);
    this.desktopContainer = document.getElementById(this.options.containerDesktopId);
    this.selector = document.getElementById(this.options.selectorId);
  }

  /**
   * Initialize the reviews manager
   */
  init() {
    if (!this.mobileContainer || !this.desktopContainer || !this.selector) {
      console.error('Reviews containers or selector not found');
      return;
    }

    // Setup event listener for rating filter
    this.setupEventListeners();

    // Initial render
    this.render();
  }

  /**
   * Setup event listeners
   */
  setupEventListeners() {
    if (this.selector) {
      this.selector.addEventListener('change', (e) => {
        this.selectedRating = e.target.value;
        this.render();
      });
    }
  }

  /**
   * Get filtered reviews based on selected rating
   * @returns {Array} Filtered reviews
   */
  getFilteredReviews() {
    if (this.selectedRating === 'all') {
      return this.reviews;
    }
    return this.reviews.filter(review => review.rating == this.selectedRating);
  }

  /**
   * Generate stars HTML
   * @param {number} rating - Rating value
   * @returns {string} Stars HTML
   */
  generateStars(rating) {
    let starsHtml = '';
    for (let i = 1; i <= 5; i++) {
      const colorClass = i <= rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600';
      starsHtml += `
        <svg class="${colorClass} w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
        </svg>
      `;
    }
    return starsHtml;
  }

  /**
   * Format date
   * @param {string} dateString - Date string
   * @returns {string} Formatted date
   */
  formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
      day: 'numeric',
      month: 'short',
      year: 'numeric'
    });
  }

  /**
   * Get user avatar HTML
   * @param {Object} review - Review object
   * @returns {string} Avatar HTML
   */
  getUserAvatar(review) {
    if (review.student_avatar) {
      return `<img src="/storage/${review.student_avatar}" class="w-full h-full object-cover" alt="${review.student_name}">`;
    }
    const initial = review.student_name ? review.student_name.charAt(0).toUpperCase() : 'U';
    return `<span class="text-white text-sm font-bold">${initial}</span>`;
  }

  /**
   * Generate empty state HTML
   * @returns {string} Empty state HTML
   */
  getEmptyStateHTML() {
    return `
      <div class="flex flex-col items-center justify-center py-12 text-center">
        <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
          <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
            </path>
          </svg>
        </div>
        <p class="text-base font-medium text-gray-600 dark:text-gray-300">Belum ada ulasan</p>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ulasan kursus akan muncul di sini</p>
      </div>
    `;
  }

  /**
   * Generate mobile card HTML for a review
   * @param {Object} review - Review object
   * @returns {string} Card HTML
   */
  getMobileCardHTML(review) {
    return `
      <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-100 dark:border-gray-600">
        <div class="flex items-start gap-3 mb-3">
          <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center overflow-hidden flex-shrink-0">
            ${this.getUserAvatar(review)}
          </div>
          <div class="flex-1 min-w-0">
            <p class="font-semibold text-gray-900 dark:text-white truncate">${review.student_name}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">${review.program_name}</p>
          </div>
          <div class="flex items-center gap-0.5">
            ${this.generateStars(review.rating)}
          </div>
        </div>
        <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">${review.review || 'Tidak ada ulasan'}</p>
        <p class="text-xs text-gray-400 dark:text-gray-500">${this.formatDate(review.created_at)}</p>
      </div>
    `;
  }

  /**
   * Generate desktop table row HTML for a review
   * @param {Object} review - Review object
   * @returns {string} Table row HTML
   */
  getDesktopRowHTML(review) {
    return `
      <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
        <td class="px-5 py-4">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center overflow-hidden flex-shrink-0">
              ${this.getUserAvatar(review)}
            </div>
            <span class="font-medium text-gray-900 dark:text-white">${review.student_name}</span>
          </div>
        </td>
        <td class="px-5 py-4">
          <span class="text-gray-700 dark:text-gray-300 font-medium">${review.program_name}</span>
        </td>
        <td class="px-5 py-4">
          <div class="flex items-center gap-1">
            ${this.generateStars(review.rating)}
            <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">(${review.rating})</span>
          </div>
        </td>
        <td class="px-5 py-4 max-w-xs">
          <p class="text-gray-600 dark:text-gray-300 truncate" title="${review.review || ''}">${review.review || '-'}</p>
        </td>
        <td class="px-5 py-4">
          <span class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">${this.formatDate(review.created_at)}</span>
        </td>
      </tr>
    `;
  }

  /**
   * Render reviews
   */
  render() {
    const filteredReviews = this.getFilteredReviews();

    // Render mobile view
    if (this.mobileContainer) {
      if (filteredReviews.length === 0) {
        this.mobileContainer.innerHTML = this.getEmptyStateHTML();
      } else {
        const cardsHTML = filteredReviews.map(review => this.getMobileCardHTML(review)).join('');
        this.mobileContainer.innerHTML = cardsHTML;
      }
    }

    // Render desktop view
    if (this.desktopContainer) {
      if (filteredReviews.length === 0) {
        this.desktopContainer.innerHTML = `
          <tr>
            <td colspan="5" class="px-5 py-12 text-center">
              ${this.getEmptyStateHTML()}
            </td>
          </tr>
        `;
      } else {
        const rowsHTML = filteredReviews.map(review => this.getDesktopRowHTML(review)).join('');
        this.desktopContainer.innerHTML = rowsHTML;
      }
    }
  }

  /**
   * Update reviews data
   * @param {Array} newReviews - New reviews array
   */
  updateReviews(newReviews) {
    this.reviews = newReviews || [];
    this.render();
  }

  /**
   * Destroy and cleanup
   */
  destroy() {
    if (this.selector) {
      this.selector.removeEventListener('change', this.setupEventListeners);
    }
  }
}

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
  module.exports = ReviewsManager;
}