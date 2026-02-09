/**
 * Chart Utilities
 * Helper functions for chart data processing and formatting
 */

const ChartUtils = {
  /**
   * Format number as Indonesian Rupiah
   * @param {number} amount - Amount to format
   * @returns {string} Formatted currency string
   */
  formatRupiah(amount) {
    if (!amount && amount !== 0) return 'Rp 0';
    return 'Rp ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  },

  /**
   * Format large numbers with abbreviations (rb/jt)
   * @param {number} val - Value to format
   * @returns {string} Formatted number string
   */
  formatShortRupiah(val) {
    if (!val && val !== 0) return 'Rp 0';
    
    if (val >= 1000000) {
      return 'Rp ' + (val / 1000000).toFixed(1) + 'jt';
    }
    if (val >= 1000) {
      return 'Rp ' + (val / 1000).toFixed(0) + 'rb';
    }
    return 'Rp ' + val;
  },

  /**
   * Check if data array has any non-zero values
   * @param {Array} dataArray - Array to check
   * @returns {boolean}
   */
  hasData(dataArray) {
    if (!dataArray || !Array.isArray(dataArray)) return false;
    return dataArray.some(val => val > 0);
  },

  /**
   * Generate HTML for "no data" state
   * @param {string} title - Title text
   * @param {string} message - Message text
   * @returns {string} HTML string
   */
  getNoDataHTML(title, message) {
    const isDark = document.documentElement.classList.contains('dark');
    const bgClass = isDark ? 'bg-gray-700' : 'bg-gray-100';
    const iconClass = isDark ? 'text-gray-500' : 'text-gray-400';
    const titleClass = isDark ? 'text-gray-300' : 'text-gray-700';
    const messageClass = isDark ? 'text-gray-500' : 'text-gray-500';
    
    return `
      <div class="flex flex-col items-center justify-center h-full min-h-[280px] text-center p-6">
        <div class="p-4 rounded-full mb-4 ${bgClass}">
          <svg class="w-12 h-12 ${iconClass}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
            </path>
          </svg>
        </div>
        <h4 class="text-base font-semibold ${titleClass} mb-1">${title}</h4>
        <p class="text-sm ${messageClass}">${message}</p>
      </div>
    `;
  },

  /**
   * Validate chart data for a specific year
   * @param {Object} chartData - Full chart data object
   * @param {number|string} year - Year to validate
   * @returns {Object|null} Year data or null if invalid
   */
  validateYearData(chartData, year) {
    if (!chartData || typeof chartData !== 'object') {
      console.warn('Invalid chart data provided');
      return null;
    }
    
    if (!chartData[year]) {
      console.warn(`No data available for year ${year}`);
      return null;
    }
    
    return chartData[year];
  },

  /**
   * Get available years from chart data
   * @param {Object} chartData - Full chart data object
   * @returns {Array} Array of years as numbers
   */
  getAvailableYears(chartData) {
    if (!chartData || typeof chartData !== 'object') {
      return [new Date().getFullYear()];
    }
    
    const years = Object.keys(chartData).map(Number).filter(year => !isNaN(year));
    return years.length > 0 ? years : [new Date().getFullYear()];
  },

  /**
   * Get the most recent year from available years
   * @param {Array} years - Array of years
   * @returns {number} Latest year
   */
  getLatestYear(years) {
    if (!Array.isArray(years) || years.length === 0) {
      return new Date().getFullYear();
    }
    return Math.max(...years);
  },

  /**
   * Debounce function for performance optimization
   * @param {Function} func - Function to debounce
   * @param {number} wait - Wait time in milliseconds
   * @returns {Function} Debounced function
   */
  debounce(func, wait = 300) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }
};

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
  module.exports = ChartUtils;
}