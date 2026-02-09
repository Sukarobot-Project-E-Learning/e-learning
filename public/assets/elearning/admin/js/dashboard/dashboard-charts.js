/**
 * Dashboard Charts Manager
 * Main controller for all dashboard charts
 */

class DashboardCharts {
  /**
   * @param {Object} chartData - Chart data organized by year
   * @param {Object} options - Configuration options
   */
  constructor(chartData, options = {}) {
    this.chartData = chartData || {};
    this.options = {
      platformChartId: options.platformChartId || 'platformChart',
      revenueChartId: options.revenueChartId || 'revenueChart',
      platformSelectorId: options.platformSelectorId || 'yearSelector',
      revenueSelectorId: options.revenueSelectorId || 'revenueYearSelector',
      ...options
    };

    // Chart instances
    this.platformChart = null;
    this.revenueChart = null;

    // Year management
    this.availableYears = ChartUtils.getAvailableYears(chartData);
    this.currentYear = ChartUtils.getLatestYear(this.availableYears);

    // Theme observer
    this.themeObserver = null;
  }

  /**
   * Initialize all charts and event listeners
   */
  init() {
    // Check if ApexCharts is loaded
    if (typeof ApexCharts === 'undefined') {
      console.error('ApexCharts library not loaded');
      return;
    }

    // Initialize chart instances
    this.platformChart = new PlatformChart(
      this.options.platformChartId,
      this.chartData
    );

    this.revenueChart = new RevenueChart(
      this.options.revenueChartId,
      this.chartData
    );

    // Render initial charts
    this.renderAllCharts();

    // Setup event listeners
    this.setupEventListeners();

    // Setup theme observer
    this.setupThemeObserver();
  }

  /**
   * Render all charts with current year
   */
  renderAllCharts() {
    if (this.platformChart) {
      this.platformChart.render(this.currentYear);
    }

    if (this.revenueChart) {
      this.revenueChart.render(this.currentYear);
    }
  }

  /**
   * Setup event listeners for year selectors
   */
  setupEventListeners() {
    // Platform chart year selector
    const platformSelector = document.getElementById(this.options.platformSelectorId);
    if (platformSelector) {
      platformSelector.addEventListener('change', (e) => {
        const year = parseInt(e.target.value);
        if (!isNaN(year) && this.platformChart) {
          this.platformChart.render(year);
        }
      });
    }

    // Revenue chart year selector
    const revenueSelector = document.getElementById(this.options.revenueSelectorId);
    if (revenueSelector) {
      revenueSelector.addEventListener('change', (e) => {
        const year = parseInt(e.target.value);
        if (!isNaN(year) && this.revenueChart) {
          this.revenueChart.render(year);
        }
      });
    }
  }

  /**
   * Setup theme change observer
   * Re-renders charts when dark mode is toggled
   */
  setupThemeObserver() {
    // Create debounced render function for performance
    const debouncedRender = ChartUtils.debounce(() => {
      this.renderAllCharts();
    }, 150);

    this.themeObserver = new MutationObserver((mutations) => {
      mutations.forEach((mutation) => {
        if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
          debouncedRender();
        }
      });
    });

    // Observe class changes on html element (for dark mode)
    this.themeObserver.observe(document.documentElement, {
      attributes: true,
      attributeFilter: ['class']
    });
  }

  /**
   * Update chart data and re-render
   * @param {Object} newData - New chart data
   */
  updateData(newData) {
    this.chartData = newData;
    this.availableYears = ChartUtils.getAvailableYears(newData);

    if (this.platformChart) {
      this.platformChart.updateData(newData);
    }

    if (this.revenueChart) {
      this.revenueChart.updateData(newData);
    }

    this.renderAllCharts();
  }

  /**
   * Destroy all charts and cleanup
   */
  destroy() {
    if (this.platformChart) {
      this.platformChart.destroy();
      this.platformChart = null;
    }

    if (this.revenueChart) {
      this.revenueChart.destroy();
      this.revenueChart = null;
    }

    if (this.themeObserver) {
      this.themeObserver.disconnect();
      this.themeObserver = null;
    }
  }
}

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
  module.exports = DashboardCharts;
}