/**
 * Dashboard Initialization Script
 * This file initializes the dashboard charts and reviews when DOM is ready
 */

(function() {
  'use strict';

  // Global instances
  let dashboardCharts = null;
  let reviewsManager = null;

  /**
   * Initialize dashboard when DOM is ready
   */
  function initDashboard() {
    // Get chart data from global variable (set by Blade)
    const chartData = window.chartDataByYear || {};
    const reviewsData = window.programReviews || [];

    // Initialize charts
    initializeCharts(chartData);
    
    // Initialize reviews
    initializeReviews(reviewsData);

    // Initialize date display
    initializeDateDisplay();
  }

  /**
   * Initialize charts
   */
  function initializeCharts(chartData) {
    // Check if ApexCharts is available
    if (typeof ApexCharts === 'undefined') {
      console.error('ApexCharts library not loaded. Please include ApexCharts before this script.');
      return;
    }

    // Check if required classes are available
    if (typeof DashboardCharts === 'undefined') {
      console.error('DashboardCharts class not loaded. Please include dashboard-charts.js');
      return;
    }

    // Initialize dashboard charts
    try {
      dashboardCharts = new DashboardCharts(chartData, {
        platformChartId: 'platformChart',
        revenueChartId: 'revenueChart',
        platformSelectorId: 'yearSelector',
        revenueSelectorId: 'revenueYearSelector'
      });

      dashboardCharts.init();
      
      console.log('Dashboard charts initialized successfully');
    } catch (error) {
      console.error('Error initializing dashboard charts:', error);
    }
  }

  /**
   * Initialize reviews manager
   */
  function initializeReviews(reviewsData) {
    // Check if required class is available
    if (typeof ReviewsManager === 'undefined') {
      console.error('ReviewsManager class not loaded. Please include reviews-manager.js');
      return;
    }

    try {
      reviewsManager = new ReviewsManager(reviewsData, {
        containerMobileId: 'reviewsMobileContainer',
        containerDesktopId: 'reviewsDesktopContainer',
        selectorId: 'ratingSelector'
      });

      reviewsManager.init();
      
      console.log('Reviews manager initialized successfully');
    } catch (error) {
      console.error('Error initializing reviews manager:', error);
    }
  }

  /**
   * Initialize date display
   */
  function initializeDateDisplay() {
    const dateElement = document.getElementById('currentDate');
    if (dateElement) {
      const options = { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
      };
      dateElement.textContent = new Date().toLocaleDateString('id-ID', options);
    }
  }

  /**
   * Cleanup function
   */
  function cleanup() {
    if (dashboardCharts) {
      dashboardCharts.destroy();
      dashboardCharts = null;
    }

    if (reviewsManager) {
      reviewsManager.destroy();
      reviewsManager = null;
    }
  }

  // Initialize when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDashboard);
  } else {
    // DOM is already ready
    initDashboard();
  }

  // Cleanup on page unload
  window.addEventListener('beforeunload', cleanup);

  // Expose cleanup function globally if needed
  window.cleanupDashboard = cleanup;
})();