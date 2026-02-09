/**
 * Revenue Chart
 * Manages the revenue trend chart
 */

class RevenueChart {
  /**
   * @param {string} containerId - DOM element ID for chart container
   * @param {Object} chartData - Chart data organized by year
   */
  constructor(containerId, chartData) {
    this.containerId = containerId;
    this.chartData = chartData;
    this.chart = null;
    this.container = document.getElementById(containerId);
    
    if (!this.container) {
      console.error(`Container with ID "${containerId}" not found`);
    }
  }

  /**
   * Build chart configuration
   * @param {Object} yearData - Data for specific year
   * @returns {Object} ApexCharts configuration
   */
  buildConfig(yearData) {
    const colors = ChartTheme.getColors();
    const baseConfig = ChartTheme.getBaseConfig();

    return {
      ...baseConfig,
      series: [
        {
          name: 'Pendapatan',
          data: yearData.revenue || []
        }
      ],
      chart: {
        ...baseConfig.chart,
        type: 'bar',
        height: 320
      },
      colors: [colors.primary.revenue],
      plotOptions: {
        bar: {
          borderRadius: 8,
          columnWidth: '55%'
        }
      },
      dataLabels: {
        enabled: false
      },
      xaxis: {
        categories: yearData.months || [],
        labels: {
          style: {
            fontSize: '12px',
            fontWeight: 500,
            colors: colors.text
          }
        },
        axisBorder: {
          show: true,
          color: colors.grid
        }
      },
      yaxis: {
        min: 0,
        forceNiceScale: true,
        title: {
          text: 'Pendapatan (Rp)',
          style: {
            fontSize: '13px',
            fontWeight: 600,
            color: colors.text
          }
        },
        labels: {
          style: {
            fontSize: '12px',
            colors: colors.text
          },
          formatter: (val) => ChartUtils.formatShortRupiah(val)
        }
      },
      tooltip: {
        theme: colors.tooltip,
        y: {
          formatter: (val) => ChartUtils.formatRupiah(val)
        }
      }
    };
  }

  /**
   * Check if year has valid revenue data
   * @param {Object} yearData - Data for specific year
   * @returns {boolean}
   */
  hasValidData(yearData) {
    return ChartUtils.hasData(yearData.revenue);
  }

  /**
   * Render chart for specific year
   * @param {number|string} year - Year to render
   */
  render(year) {
    if (!this.container) {
      console.error('Chart container not found');
      return;
    }

    // Validate year data
    const yearData = ChartUtils.validateYearData(this.chartData, year);
    
    if (!yearData) {
      this.container.innerHTML = ChartUtils.getNoDataHTML(
        'Data Tidak Tersedia',
        `Belum ada data pendapatan untuk tahun ${year}`
      );
      return;
    }

    // Check if revenue data has any values
    if (!this.hasValidData(yearData)) {
      this.container.innerHTML = ChartUtils.getNoDataHTML(
        'Belum Ada Pendapatan',
        'Data pendapatan akan muncul setelah ada transaksi'
      );
      return;
    }

    // Clear container
    this.container.innerHTML = '';

    // Destroy existing chart
    if (this.chart) {
      this.chart.destroy();
    }

    // Create and render new chart
    const config = this.buildConfig(yearData);
    this.chart = new ApexCharts(this.container, config);
    this.chart.render();
  }

  /**
   * Update chart with new data
   * @param {Object} newData - New chart data
   */
  updateData(newData) {
    this.chartData = newData;
  }

  /**
   * Destroy chart instance
   */
  destroy() {
    if (this.chart) {
      this.chart.destroy();
      this.chart = null;
    }
  }
}

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
  module.exports = RevenueChart;
}