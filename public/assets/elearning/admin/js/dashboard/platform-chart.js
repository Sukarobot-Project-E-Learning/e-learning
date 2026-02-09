/**
 * Platform Statistics Chart
 * Manages the platform statistics chart (Users, Instructors, Programs)
 */

class PlatformChart {
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
          name: 'Pengguna',
          data: yearData.users || []
        },
        {
          name: 'Instruktur',
          data: yearData.instructors || []
        },
        {
          name: 'Total Program',
          data: yearData.programs || []
        }
      ],
      chart: {
        ...baseConfig.chart,
        type: 'line',
        height: 320
      },
      colors: [
        colors.primary.users,
        colors.primary.instructors,
        colors.primary.programs
      ],
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'smooth',
        width: 3
      },
      markers: {
        size: 5,
        colors: [
          colors.primary.users,
          colors.primary.instructors,
          colors.primary.programs
        ],
        strokeColors: colors.background,
        strokeWidth: 2,
        hover: {
          size: 7
        }
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
        },
        axisTicks: {
          show: true,
          color: colors.grid
        }
      },
      yaxis: {
        min: 0,
        forceNiceScale: true,
        title: {
          text: 'Total Kumulatif',
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
          formatter: (val) => Math.floor(val)
        }
      },
      legend: {
        position: 'bottom',
        horizontalAlign: 'center',
        fontSize: '13px',
        fontWeight: 500,
        labels: {
          colors: colors.text
        },
        markers: {
          width: 10,
          height: 10,
          radius: 10
        },
        itemMargin: {
          horizontal: 12,
          vertical: 8
        }
      }
    };
  }

  /**
   * Check if year has valid data
   * @param {Object} yearData - Data for specific year
   * @returns {boolean}
   */
  hasValidData(yearData) {
    return ChartUtils.hasData(yearData.users) ||
           ChartUtils.hasData(yearData.instructors) ||
           ChartUtils.hasData(yearData.programs);
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
        `Belum ada data statistik untuk tahun ${year}`
      );
      return;
    }

    // Check if data has any values
    if (!this.hasValidData(yearData)) {
      this.container.innerHTML = ChartUtils.getNoDataHTML(
        'Belum Ada Data',
        'Data statistik platform akan muncul setelah ada aktivitas'
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
  module.exports = PlatformChart;
}