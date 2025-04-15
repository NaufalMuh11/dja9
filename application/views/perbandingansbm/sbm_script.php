<script>
    // Initialize the module using an IIFE
    const SBMChartModule = (function() {
        // Private variables
        const baseUrl = document.querySelector('meta[name="base_url"]')?.content || '';
        const refreshInterval = 5 * 60 * 1000; // 5 minutes
        let lastRefreshTime = new Date();
        let refreshTimer;
        let isLoadingProvinceData = false;
        let pendingProvinceDataRequest = false;

        // Chart instances
        let charts = {
            boxplot: null,
            barDetail: null,
            province: null
        };

        // Data storage
        let chartData = {
            bar: [],
            boxplot: [],
            province: []
        };

        // DOM element selectors
        const elements = {
            sbmSelect: document.querySelector('.form-select'),
            subtitleSelect: document.getElementById('sbm-subtitle-select'),
            subSubtitleDropdown: document.getElementById('sub-subtitle-dropdown'),
            subSubtitleMenu: document.getElementById('sub-subtitle-dropdown-menu'),
            selectedSubSubtitle: document.getElementById('selected-sub-subtitle'),
            yearToggle: document.querySelector('#selectThang .dropdown-toggle'),
            yearInput: document.getElementById('selected_thang'),
            yearMenu: document.querySelector('#selectThang .dropdown-menu'),
            refreshButton: document.getElementById('refreshButton'),
            refreshIndicator: document.getElementById('refresh-indicator'),
            lastUpdateElement: document.getElementById('lastUpdate'),
            barChartContainer: document.getElementById('chart-bar-detail'),
            boxplotContainer: document.getElementById('chart-boxplot'),
            provinceChartContainer: document.getElementById('province-chart-container'),
            provinceTableBody: document.getElementById('province-table-body'),
            barChartSubtitle: document.getElementById('bar-chart-subtitle'),
            subtitleDropdown: document.getElementById('subtitle-dropdown')
        };

        // Chart colors
        const chartColors = {
            primary: "#648FFF",
            secondary: typeof tabler !== 'undefined' ? tabler.getColor("secondary") : "#9e9e9e",
            success: typeof tabler !== 'undefined' ? tabler.getColor("green") : "#4caf50",
            warning: "#ffb000",
            danger: typeof tabler !== 'undefined' ? tabler.getColor("red") : "#f44336",
            info: typeof tabler !== 'undefined' ? tabler.getColor("cyan") : "#00bcd4",
            dark: typeof tabler !== 'undefined' ? tabler.getColor("dark") : "#212121",
            purple: "#785EF0",
            orange: "#FE6100",
            lime: typeof tabler !== 'undefined' ? tabler.getColor("lime") : "#cddc39",
            indigo: typeof tabler !== 'undefined' ? tabler.getColor("indigo") : "#3f51b5",
            teal: typeof tabler !== 'undefined' ? tabler.getColor("teal") : "#009688",
            pink: "#DC267F"
        };

        // Common chart settings
        const commonChartSettings = {
            fontFamily: "inherit",
            toolbar: {
                show: false
            },
            animations: {
                enabled: true
            },
            tooltip: {
                theme: "dark"
            },
            grid: {
                padding: {
                    top: -20,
                    right: 0,
                    left: -4,
                    bottom: -4
                },
                strokeDashArray: 4
            },
            zoom: {
                zoomedArea: {
                    fill: {
                        color: '#17a2b8',
                        opacity: 0.4
                    }
                }
            }
        };

        // No data settings
        const noDataSettings = {
            text: 'Data tidak tersedia',
            align: 'center',
            verticalAlign: 'middle',
            style: {
                color: undefined,
                fontSize: '14px',
                fontFamily: undefined,
                fontWeight: undefined,
                cssClass: 'apexcharts-xaxis-label',
            }
        };

        // Current user selections
        let selections = {
            title: '127',
            subtitle: null,
            subSubtitle: null,
            year: getCurrentYear()
        };

        // Utility functions
        function showLoader() {
            if (elements.refreshIndicator) {
                elements.refreshIndicator.style.display = 'block';
            }
        }

        function hideLoader() {
            if (elements.refreshIndicator) {
                elements.refreshIndicator.style.display = 'none';
            }
        }

        function getCurrentYear() {
            return elements.yearInput ? elements.yearInput.value : '2025';
        }

        // Fetch data functions
        async function fetchData(endpoint, params = {}) {
            try {
                // Add year parameter
                params.thang = params.thang || selections.year;

                // Convert params to query string
                const queryString = Object.keys(params)
                    .map(key => `${encodeURIComponent(key)}=${encodeURIComponent(params[key])}`)
                    .join('&');

                const response = await fetch(`${baseUrl}${endpoint}?${queryString}`);

                if (!response.ok) {
                    throw new Error(`Network response was not ok: ${response.status}`);
                }

                return await response.json();
            } catch (error) {
                console.error(`Error fetching data from ${endpoint}:`, error);
                throw error;
            }
        }

        async function fetchTitlesFromHierarchy() {
            try {
                const response = await fetch(`${baseUrl}perbandingan/get_titles_from_hierarchy?thang=${selections.year}`);

                if (!response.ok) {
                    throw new Error(`Network response was not ok: ${response.status}`);
                }

                const data = await response.json();
                return data || [];
            } catch (error) {
                console.error('Error fetching titles from hierarchy:', error);
                // Fallback to original titles if error
                return null;
            }
        }

        async function fetchBoxplotData(titleCode) {
            try {
                const data = await fetchData('perbandingan/get_boxplot_data', {
                    kode: titleCode
                });
                return data || [];
            } catch (error) {
                console.error('Error fetching boxplot data:', error);
                return [];
            }
        }

        async function fetchBarData(titleCode) {
            try {
                const data = await fetchData('perbandingan/get_bar_data', {
                    kode: titleCode
                });
                return Array.isArray(data) ? data : [];
            } catch (error) {
                console.error('Error fetching bar data:', error);
                return [];
            }
        }

        async function fetchProvinceData() {
            try {
                const data = await fetchData('perbandingan/get_comparison_data', {
                    kode: selections.title,
                    thang: selections.year,
                    subtitle: selections.subtitle,
                    sub_subtitle: selections.subSubtitle
                });
                return data || [];
            } catch (error) {
                console.error('Error fetching province data:', error);
                return [];
            }
        }

        async function fetchYears() {
            try {
                return await fetchData('dashboard', {
                    q: 'rangeThang'
                });
            } catch (error) {
                console.error('Error fetching years:', error);
                return ['2025'];
            }
        }

        function updateTitleSelect(titles) {
            if (!elements.sbmSelect) return;

            // Remember current selection
            const currentValue = elements.sbmSelect.value;

            // Clear existing options
            elements.sbmSelect.innerHTML = '';

            // Add title options
            titles.forEach(title => {
                const option = document.createElement('option');
                option.value = title.kdsbu.substring(0, 3);
                option.textContent = title.nmsbu;
                elements.sbmSelect.appendChild(option);
            });

            // Try to restore previous selection
            if (currentValue) {
                elements.sbmSelect.value = currentValue;
            }
        }

        // Chart rendering functions
        function renderBoxplotChart(data, subtitle) {
            if (!elements.boxplotContainer) return;

            if (!data || data.length === 0) {
                if (charts.boxplot) {
                    charts.boxplot.updateOptions({
                        series: [],
                        noData: noDataSettings
                    });
                }
                return;
            }

            // Convert values to thousands (divide by 1000)
            const convertedData = data.map(item => ({
                x: item.x,
                y: item.y.map(val => val / 1000)
            }));

            const options = {
                series: [{
                    type: 'boxPlot',
                    data: convertedData
                }],
                chart: {
                    ...commonChartSettings,
                    type: 'boxPlot',
                    height: 350
                },
                plotOptions: {
                    boxPlot: {
                        colors: {
                            upper: chartColors.pink,
                            lower: chartColors.primary
                        }
                    }
                },
                xaxis: {
                    labels: {
                        rotate: -90,
                        trim: false,
                        maxHeight: 120
                    }
                },
                tooltip: {
                    ...commonChartSettings.tooltip,
                    y: {
                        formatter: function(value) {
                            return new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                },
                noData: noDataSettings
            };

            if (charts.boxplot) {
                charts.boxplot.updateOptions(options);
            } else {
                charts.boxplot = new ApexCharts(elements.boxplotContainer, options);
                charts.boxplot.render();
            }
        }

        function renderBarChart(data) {
            if (!elements.barChartContainer) return;

            if (!data || data.length === 0) {
                // Hide subtitle and dropdowns
                if (elements.barChartSubtitle) {
                    elements.barChartSubtitle.style.display = 'none';
                }

                if (elements.subtitleDropdown) {
                    elements.subtitleDropdown.style.display = 'none';
                }

                if (elements.subSubtitleDropdown) {
                    elements.subSubtitleDropdown.style.display = 'none';
                }

                // Update chart with no data
                if (charts.barDetail) {
                    charts.barDetail.updateOptions({
                        series: [{
                            name: 'Biaya',
                            data: []
                        }],
                        xaxis: {
                            categories: []
                        },
                        noData: noDataSettings
                    });
                } else {
                    charts.barDetail = new ApexCharts(elements.barChartContainer, {
                        series: [{
                            name: 'Biaya',
                            data: []
                        }],
                        chart: {
                            ...commonChartSettings,
                            type: 'bar',
                            height: 400
                        },
                        noData: noDataSettings
                    });
                    charts.barDetail.render();
                }
                return;
            }

            // Prepare data for chart
            const categories = data.map(item => item.name);
            const values = data.map(item => parseFloat(item.data) / 1000);

            // Get chart title (from sub-subtitle if selected)
            let chartTitle = "";
            if (selections.subSubtitle) {
                chartTitle = selections.subSubtitle;
            }

            // Update subtitle element
            if (elements.barChartSubtitle) {
                if (chartTitle) {
                    elements.barChartSubtitle.textContent = chartTitle;
                    elements.barChartSubtitle.style.display = 'block';
                } else {
                    elements.barChartSubtitle.style.display = 'none';
                }
            }

            // Chart options
            const options = {
                series: [{
                    name: 'Biaya',
                    data: values
                }],
                chart: {
                    ...commonChartSettings,
                    type: 'bar',
                    height: 400,
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        endingShape: 'rounded'
                    },
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: categories,
                    labels: {
                        rotate: -90,
                        trim: false,
                        maxHeight: 120
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(value) {
                            return new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                },
                colors: [chartColors.primary],
                noData: noDataSettings
            };

            if (charts.barDetail) {
                charts.barDetail.updateOptions(options);
            } else {
                charts.barDetail = new ApexCharts(elements.barChartContainer, options);
                charts.barDetail.render();
            }
        }

        function renderProvinceChart(data) {
            if (!elements.provinceChartContainer) return;

            if (!data || data.length === 0) {
                // Update chart with no data
                if (charts.province) {
                    charts.province.updateOptions({
                        series: [],
                        noData: noDataSettings
                    });
                }

                // Clear table
                if (elements.provinceTableBody) {
                    elements.provinceTableBody.innerHTML = '';
                }
                return;
            }

            const provinces = data.map(item => item.province);
            const values2025 = data.map(item => item.biaya_2025 / 1000);
            const values2026 = data.map(item => item.biaya_2026 / 1000);
            const percentageChanges = data.map(item => item.percentage_change);

            const options = {
                series: [{
                        name: 'Biaya 2025',
                        data: values2025,
                        type: 'line'
                    },
                    {
                        name: 'Biaya 2026',
                        data: values2026,
                        type: 'line'
                    },
                    {
                        name: '% Perubahan',
                        data: percentageChanges,
                        yAxisIndex: 1
                    }
                ],
                chart: {
                    ...commonChartSettings,
                    type: 'line',
                    height: 500,
                },
                stroke: {
                    width: [3, 3, 0],
                    curve: 'smooth',
                    dashArray: [0, 0, 0]
                },
                markers: {
                    size: [5, 5, 0],
                    hover: {
                        size: 7
                    }
                },
                xaxis: {
                    categories: provinces,
                    labels: {
                        rotate: -90,
                        rotateAlways: true,
                        style: {
                            fontSize: '11px'
                        }
                    },
                    title: {
                        text: 'Provinsi',
                        style: {
                            fontSize: '12px',
                            fontWeight: 'bold'
                        }
                    }
                },
                yaxis: [{
                        title: {
                            text: 'Biaya (Rupiah)'
                        },
                        labels: {
                            formatter: function(value) {
                                return new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    },
                    {
                        opposite: true,
                        title: {
                            text: 'Persentase Perubahan (%)'
                        },
                        labels: {
                            formatter: function(value) {
                                return value.toFixed(2) + '%';
                            }
                        },
                        min: function(min) {
                            return Math.min(0, min);
                        }
                    }
                ],
                tooltip: {
                    theme: "dark",
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: function(value, {
                            seriesIndex
                        }) {
                            if (seriesIndex === 2) {
                                return value.toFixed(2) + '%';
                            }
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                },
                colors: ['#4169E1', '#32CD32'],
                legend: {
                    position: 'top'
                },
                grid: {
                    borderColor: '#e7e7e7',
                    padding: {
                        top: -20,
                        right: 0,
                        left: -4,
                        bottom: -4
                    },
                    strokeDashArray: 4
                },
                dataLabels: {
                    enabled: true,
                    enabledOnSeries: [2],
                    formatter: function(val) {
                        return val.toFixed(2) + '%';
                    },
                    style: {
                        fontSize: '10px',
                        colors: ['#333']
                    },
                    offsetY: -5
                },
                noData: noDataSettings
            };

            if (charts.province) {
                charts.province.updateOptions(options);
            } else {
                charts.province = new ApexCharts(elements.provinceChartContainer, options);
                charts.province.render();
            }

            // Update the table data
            updateProvinceTable(data);
        }

        function updateProvinceTable(data) {
            if (!elements.provinceTableBody) return;

            elements.provinceTableBody.innerHTML = '';

            data.forEach(item => {
                const row = document.createElement('tr');

                // Add CSS class for positive/negative values
                const changeClass = item.percentage_change > 0 ?
                    'text-success' :
                    (item.percentage_change < 0 ? 'text-danger' : '');

                row.innerHTML = `
                    <td>${item.province}</td>
                    <td>${new Intl.NumberFormat('id-ID').format(item.biaya_2025)}</td>
                    <td>${new Intl.NumberFormat('id-ID').format(item.biaya_2026)}</td>
                    <td class="${item.difference > 0 ? 'text-success' : (item.difference < 0 ? 'text-danger' : '')}">${new Intl.NumberFormat('id-ID').format(item.difference)}</td>
                    <td class="${changeClass}">${item.percentage_change.toFixed(2)}%</td>
                `;

                elements.provinceTableBody.appendChild(row);
            });
        }

        // Helper function for populating subtitle select
        function populateSubtitleSelect(subtitles) {
            if (!elements.subtitleSelect) return;

            elements.subtitleSelect.innerHTML = '';

            // Add subtitle options
            subtitles.forEach(subtitle => {
                const option = document.createElement('option');
                option.value = subtitle;
                option.textContent = subtitle;
                if (subtitle === selections.subtitle) {
                    option.selected = true;
                }
                elements.subtitleSelect.appendChild(option);
            });

            // Enable the select
            elements.subtitleSelect.disabled = false;
        }

        // Helper function for sub-subtitles dropdown
        function updateSubSubtitleDropdown(subSubtitles) {
            if (!elements.subSubtitleDropdown || !elements.subSubtitleMenu || !elements.selectedSubSubtitle) return;

            // Clear existing menu items
            elements.subSubtitleMenu.innerHTML = '';

            if (subSubtitles.length === 0) {
                // Hide dropdown if no sub-subtitles
                elements.subSubtitleDropdown.style.display = 'none';
                elements.selectedSubSubtitle.textContent = 'Pilih Sub-subjudul';
                return;
            }

            // Add sub-subtitle options
            subSubtitles.forEach(subSubtitle => {
                const item = document.createElement('a');
                item.className = 'dropdown-item';
                item.href = '#';
                item.textContent = subSubtitle;
                item.setAttribute('data-value', subSubtitle);
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const selectedValue = this.getAttribute('data-value');
                    elements.selectedSubSubtitle.textContent = selectedValue;
                    handleSubSubtitleChange(selectedValue);
                });
                elements.subSubtitleMenu.appendChild(item);
            });

            // Show dropdown
            elements.subSubtitleDropdown.style.display = 'block';

            // Auto-select first option
            if (subSubtitles.length > 0) {
                elements.selectedSubSubtitle.textContent = subSubtitles[0];
                handleSubSubtitleChange(subSubtitles[0]);
            }
        }

        // Get unique sub-subtitles from data
        function getSubSubtitles(data) {
            if (!data || data.length === 0) return [];

            return [...new Set(data
                .filter(item => item.hasOwnProperty('sub_subtitle') && item.sub_subtitle)
                .map(item => item.sub_subtitle))];
        }

        // Process sub-subtitles
        function processSubSubtitles(data, shouldLoadProvinceData = true) {
            if (!data || data.length === 0) {
                renderBarChart([]);
                return;
            }

            // Check if we have any sub-subtitles
            const subSubtitles = getSubSubtitles(data);

            if (subSubtitles.length > 0) {
                // Update sub-subtitle dropdown
                updateSubSubtitleDropdown(subSubtitles, shouldLoadProvinceData);

                // Auto-select first sub-subtitle
                if (subSubtitles.length > 0 && elements.selectedSubSubtitle) {
                    elements.selectedSubSubtitle.textContent = subSubtitles[0];
                    handleSubSubtitleChange(subSubtitles[0], shouldLoadProvinceData);
                }
            } else {
                // No sub-subtitles, hide dropdown
                if (elements.subSubtitleDropdown) {
                    elements.subSubtitleDropdown.style.display = 'none';
                }

                // Update chart with all data for this subtitle
                renderBarChart(data);

                // Only load province data if requested
                if (shouldLoadProvinceData) {
                    loadProvinceData();
                }
            }

            // Always hide subtitle dropdown (legacy UI element)
            if (elements.subtitleDropdown) {
                elements.subtitleDropdown.style.display = 'none';
            }
        }

        // Handle sub-subtitle change
        function handleSubSubtitleChange(subSubtitle, shouldLoadProvinceData = true) {
            // Skip if sub-subtitle hasn't actually changed
            if (selections.subSubtitle === subSubtitle) {
                return;
            }

            // Update selection
            selections.subSubtitle = subSubtitle;

            // Filter data by both subtitle and sub-subtitle
            const filteredData = chartData.bar.filter(item =>
                (item.subtitle === selections.subtitle || !selections.subtitle) &&
                item.sub_subtitle === subSubtitle
            );

            // Update chart with filtered data
            renderBarChart(filteredData);

            // Update province data only if requested
            if (shouldLoadProvinceData) {
                loadProvinceData();
            }
        }

        // Data processing functions
        async function loadBoxplotData(titleCode) {
            try {
                showLoader();

                // Fetch boxplot data
                const data = await fetchBoxplotData(titleCode);

                // Store the data
                chartData.boxplot = data;

                // Reset subtitle selection if no data
                if (!data || data.length === 0) {
                    if (elements.subtitleSelect) {
                        elements.subtitleSelect.disabled = true;
                        elements.subtitleSelect.innerHTML = '<option disabled selected>Subjudul</option>';
                    }
                    renderBoxplotChart([], null);
                    return;
                }

                // Check if data has subtitle property
                const hasSubtitles = data.some(item => item.hasOwnProperty('subtitle'));

                if (hasSubtitles) {
                    // Get unique subtitles
                    const subtitles = [...new Set(data.map(item => item.subtitle))];

                    // Populate subtitle select
                    populateSubtitleSelect(subtitles);

                    // Set initial subtitle if not already set
                    if (!selections.subtitle && subtitles.length > 0) {
                        selections.subtitle = subtitles[0];
                        if (elements.subtitleSelect) {
                            elements.subtitleSelect.value = selections.subtitle;
                        }
                    }

                    // Filter data by current subtitle
                    const filteredData = data.filter(item =>
                        !selections.subtitle || item.subtitle === selections.subtitle
                    );

                    // Update chart with filtered data
                    renderBoxplotChart(filteredData, selections.subtitle);
                } else {
                    // No subtitles, use all data
                    if (elements.subtitleSelect) {
                        elements.subtitleSelect.disabled = true;
                        elements.subtitleSelect.innerHTML = '<option disabled selected>Subjudul</option>';
                    }
                    selections.subtitle = null;
                    renderBoxplotChart(data, null);
                }
            } catch (error) {
                console.error('Error loading boxplot data:', error);
                renderBoxplotChart([], null);

                // Disable subtitle select on error
                if (elements.subtitleSelect) {
                    elements.subtitleSelect.disabled = true;
                    elements.subtitleSelect.innerHTML = '<option disabled selected>Subjudul</option>';
                }
            } finally {
                hideLoader();
            }
        }

        async function loadBarData(titleCode) {
            try {
                showLoader();

                const data = await fetchBarData(titleCode);
                chartData.bar = data;

                // Reset selections
                selections.title = titleCode;
                selections.subSubtitle = null;

                // Get subtitle from boxplot if selected
                if (selections.subtitle) {
                    const filteredData = data.filter(item =>
                        item.subtitle === selections.subtitle ||
                        (!item.subtitle && !selections.subtitle)
                    );
                    processSubSubtitles(filteredData, false);
                } else {
                    // Get first subtitle from data if available
                    const subtitles = [...new Set(data.map(item => item.subtitle).filter(Boolean))];
                    if (subtitles.length > 0) {
                        selections.subtitle = subtitles[0];
                        const filteredData = data.filter(item => item.subtitle === subtitles[0]);
                        processSubSubtitles(filteredData, false);
                    } else {
                        // No subtitles, use all data
                        processSubSubtitles(data, false);
                    }
                }
            } catch (error) {
                console.error('Error loading bar data:', error);

                // Show error message
                renderBarChart([]);

                // Hide dropdowns
                if (elements.subSubtitleDropdown) {
                    elements.subSubtitleDropdown.style.display = 'none';
                }
                if (elements.subtitleDropdown) {
                    elements.subtitleDropdown.style.display = 'none';
                }
            } finally {
                hideLoader();
            }
        }

        async function loadProvinceData() {
            // If a request is already in progress, mark as pending and return
            if (isLoadingProvinceData) {
                pendingProvinceDataRequest = true;
                return;
            }

            try {
                isLoadingProvinceData = true;
                pendingProvinceDataRequest = false;
                showLoader();

                // Add loading state to chart container
                if (elements.provinceChartContainer) {
                    elements.provinceChartContainer.classList.add('opacity-50');
                }

                // Fetch province data
                const data = await fetchProvinceData();

                // Store the data
                chartData.province = data;

                // Update chart
                renderProvinceChart(data);
            } catch (error) {
                console.error('Error loading province data:', error);
                // Error handling code...
            } finally {
                // Remove loading state
                if (elements.provinceChartContainer) {
                    elements.provinceChartContainer.classList.remove('opacity-50');
                }

                hideLoader();
                isLoadingProvinceData = false;

                // If a request came in while we were processing, handle it now
                if (pendingProvinceDataRequest) {
                    setTimeout(loadProvinceData, 100);
                }
            }
        }

        // Update functions
        async function updateLastUpdateTime() {
            const now = new Date();

            // Format tanggal
            const dateFormatter = new Intl.DateTimeFormat('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });

            // Format waktu
            const timeFormatter = new Intl.DateTimeFormat('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });

            const dateStr = dateFormatter.format(now);
            const timeStr = timeFormatter.format(now);

            if (elements.lastUpdateElement) {
                elements.lastUpdateElement.innerHTML = `
                    <div class="text-end">${dateStr}</div>
                    <div class="mt-1 small text-muted text-end">
                    Update terakhir: <b>${timeStr}</b> WIB
                    </div>
                `;
            }
        }

        async function refreshAllCharts() {
            showLoader();

            try {
                // Update the last update time first
                await updateLastUpdateTime();

                // Get current selections
                const sbmCode = elements.sbmSelect ? elements.sbmSelect.value : '127';

                // Try to get titles from hierarchy table
                const titles = await fetchTitlesFromHierarchy();

                if (titles && titles.length > 0) {
                    // Update SBM select with titles from hierarchy
                    updateTitleSelect(titles);
                }

                // Update charts with current selection
                await loadBoxplotData(sbmCode);
                await loadBarData(sbmCode);

                // Update lastRefreshTime after successful refresh
                lastRefreshTime = new Date();
            } catch (error) {
                console.error('Error refreshing charts:', error);
            } finally {
                hideLoader();
            }
        }

        // Initialize Thang (year) dropdown
        async function initYearDropdown() {
            if (!elements.yearMenu || !elements.yearToggle || !elements.yearInput) return;

            // Clear dropdown menu
            elements.yearMenu.innerHTML = '';

            try {
                const years = await fetchYears();

                // Add years to dropdown
                years.forEach(year => {
                    const item = document.createElement('a');
                    item.className = 'dropdown-item';
                    item.href = '#';
                    item.textContent = `Tahun Anggaran ${year}`;
                    item.addEventListener('click', function(e) {
                        e.preventDefault();

                        // Remove active class from all items
                        document.querySelectorAll('#selectThang .dropdown-item')
                            .forEach(el => el.classList.remove('active'));

                        // Add active class to selected item
                        this.classList.add('active');

                        // Update UI
                        elements.yearToggle.textContent = ` T.A. ${year}`;
                        elements.yearInput.value = year;

                        // Update current year selection
                        selections.year = year;

                        // Refresh data for the selected year
                        refreshAllCharts();
                    });
                    elements.yearMenu.appendChild(item);
                });

                // Set default value to first available year
                const defaultYear = years[0] || '2025';
                elements.yearToggle.textContent = ` T.A. ${defaultYear}`;
                elements.yearInput.value = defaultYear;
                selections.year = defaultYear;

                // Set active class on default item
                const defaultItem = Array.from(document.querySelectorAll('#selectThang .dropdown-item'))
                    .find(item => item.textContent.includes(defaultYear));

                if (defaultItem) {
                    defaultItem.classList.add('active');
                }

            } catch (error) {
                console.error('Error loading years:', error);

                // Add fallback year if no years found
                const fallbackYear = '2025';
                const item = document.createElement('a');
                item.className = 'dropdown-item active';
                item.href = '#';
                item.textContent = `Tahun Anggaran ${fallbackYear}`;
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    elements.yearToggle.textContent = ` T.A. ${fallbackYear}`;
                    elements.yearInput.value = fallbackYear;
                    selections.year = fallbackYear;
                    refreshAllCharts();
                });
                elements.yearMenu.appendChild(item);

                // Set default value
                elements.yearToggle.textContent = ` T.A. ${fallbackYear}`;
                elements.yearInput.value = fallbackYear;
                selections.year = fallbackYear;
            }
        }

        // Event handlers
        function handleTitleChange(titleCode) {
            if (!titleCode) return;

            // Update current selection
            selections.title = titleCode;
            selections.subtitle = null;
            selections.subSubtitle = null;

            // Reload data for the new selection
            loadBoxplotData(titleCode);
            loadBarData(titleCode);
        }

        function handleSubtitleChange(e) {
            const subtitleValue = e.target.value;

            // Skip if subtitle hasn't actually changed
            if (selections.subtitle === subtitleValue) {
                return;
            }

            // Update current selection
            selections.subtitle = subtitleValue;
            selections.subSubtitle = null;

            // Filter existing bar data by subtitle
            const filteredData = chartData.bar.filter(item =>
                item.subtitle === subtitleValue || (!item.subtitle && !subtitleValue)
            );

            // Process sub-subtitles for the selected subtitle
            processSubSubtitles(filteredData, false); // Pass false to avoid loading province data

            // Update boxplot chart with current subtitle
            const boxplotData = chartData.boxplot.filter(item =>
                item.subtitle === subtitleValue || (!item.subtitle && !subtitleValue)
            );
            renderBoxplotChart(boxplotData, subtitleValue);

            // Now update province data since all other UI changes are complete
            loadProvinceData();
        }

        // Setup auto-refresh
        function setupAutoRefresh() {
            // Clear existing timer if any
            if (refreshTimer) {
                clearInterval(refreshTimer);
            }

            // Set up new timer for auto-refresh
            refreshTimer = setInterval(() => {
                // Only auto-refresh if the tab is visible
                if (!document.hidden) {
                    refreshAllCharts();
                }
            }, refreshInterval);
        }

        // Initialize event listeners
        function initEventListeners() {
            // Title (SBM) select change
            if (elements.sbmSelect) {
                elements.sbmSelect.addEventListener('change', function() {
                    handleTitleChange(this.value);
                });
            }

            // Subtitle select change
            if (elements.subtitleSelect) {
                elements.subtitleSelect.addEventListener('change', handleSubtitleChange);
            }

            // Refresh button click
            if (elements.refreshButton) {
                elements.refreshButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    refreshAllCharts();
                });
            }

            // Handle visibility change for auto-refresh
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden) {
                    // Tab became visible, check if refresh is needed
                    const now = new Date();
                    const timeSinceLastRefresh = now - lastRefreshTime;

                    if (timeSinceLastRefresh > refreshInterval) {
                        refreshAllCharts();
                    }
                }
            });
        }

        // Export public API
        return {
            // Initialize the module
            init: async function() {
                try {
                    initEventListeners();

                    await initYearDropdown();

                    const titles = await fetchTitlesFromHierarchy();

                    if (titles && titles.length > 0) {
                        // Update SBM select with titles from hierarchy
                        updateTitleSelect(titles);
                    }

                    // Initial data load
                    const sbmCode = elements.sbmSelect ? elements.sbmSelect.value : '127';
                    await loadBoxplotData(sbmCode);
                    await loadBarData(sbmCode);
                    await updateLastUpdateTime();

                    setupAutoRefresh();
                } catch (error) {
                    console.error('Error initializing SBM Chart Module:', error);
                }
            },

            // Manual refresh method
            refresh: refreshAllCharts,

            // Get current selections
            getSelections: function() {
                return {
                    ...selections
                };
            },

            // Update chart settings (for external use)
            updateSettings: function(newSettings) {
                // Merge new settings with current selections
                Object.assign(selections, newSettings);

                // Refresh charts with new settings
                refreshAllCharts();
            },

            // Destroy charts and clean up resources
            destroy: function() {
                // Clear auto-refresh timer
                if (refreshTimer) {
                    clearInterval(refreshTimer);
                    refreshTimer = null;
                }

                // Destroy chart instances
                Object.keys(charts).forEach(key => {
                    if (charts[key]) {
                        charts[key].destroy();
                        charts[key] = null;
                    }
                });

                console.log('SBM Chart Module destroyed');
            }
        };
    })();

    // Initialize the module when the DOM is fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        SBMChartModule.init().catch(error => {
            console.error('Error initializing SBM Chart Module:', error);
        });
    });

    // Expose module globally for external access
    window.SBMChartModule = SBMChartModule;
</script>
