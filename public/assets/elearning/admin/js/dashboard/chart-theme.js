/**
 * Chart Theme Utilities
 * Handles theme detection and color schemes for charts
 */

const ChartTheme = {
  /**
   * Check if dark mode is active
   * @returns {boolean}
   */
  isDarkMode() {
    return document.documentElement.classList.contains('dark');
  },

  /**
   * Get theme-specific colors for charts
   * @returns {Object} Color scheme object
   */
  getColors() {
    const isDark = this.isDarkMode();
    
    return {
      background: isDark ? '#1f2937' : '#ffffff',
      text: isDark ? '#e5e7eb' : '#374151',
      grid: isDark ? '#374151' : '#e5e7eb',
      tooltip: isDark ? 'dark' : 'light',
      
      // Chart specific colors
      primary: {
        revenue: '#10B981',      // Green
        users: '#F97316',        // Orange
        instructors: '#3B82F6',  // Blue
        programs: '#8B5CF6'      // Purple
      },
      
      // State colors
      success: '#10B981',
      warning: '#F59E0B',
      danger: '#EF4444',
      info: '#3B82F6'
    };
  },

  /**
   * Get responsive breakpoints for charts
   * @returns {Array} Breakpoint configurations
   */
  getResponsiveConfig() {
    return [
      {
        breakpoint: 640,
        options: {
          chart: {
            height: 280
          },
          legend: {
            fontSize: '11px'
          }
        }
      }
    ];
  },

  /**
   * Get common chart options
   * @returns {Object} Base chart configuration
   */
  getBaseConfig() {
    const colors = this.getColors();
    
    return {
      chart: {
        fontFamily: 'inherit',
        background: 'transparent',
        toolbar: {
          show: true,
          tools: {
            download: true,
            selection: false,
            zoom: false,
            zoomin: false,
            zoomout: false,
            pan: false,
            reset: false
          }
        },
        animations: {
          enabled: true,
          easing: 'easeinout',
          speed: 800
        }
      },
      grid: {
        borderColor: colors.grid,
        strokeDashArray: 4
      },
      tooltip: {
        theme: colors.tooltip
      },
      responsive: this.getResponsiveConfig()
    };
  }
};

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
  module.exports = ChartTheme;
}