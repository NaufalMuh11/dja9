<script>
    // Initialize the module using an IIFE
    const SBMChartModule = (function() {
        // Private variables
        const baseUrl = document.querySelector('meta[name="base_url"]') ? document.querySelector('meta[name="base_url"]').content || '' : '';
        const refreshInterval = 5 * 60 * 1000;
        let lastRefreshTime = new Date();
        let refreshTimer;

        // Chart instances
        let charts = {
            boxplot: null,
            compare: null
        };

        // Data storage
        let chartData = {
            boxplot: [],
            compare: [],
            // Store original data for sorting purposes
            original: {
                boxplot: [],
                compare: []
            }
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
            boxplotContainer: document.getElementById('chart-boxplot'),
            provinceChartContainer: document.getElementById('province-chart-container'),
            provinceTableBody: document.getElementById('province-table-body'),
            subtitleDropdown: document.getElementById('subtitle-dropdown'),
            sortOrderDropdown: document.getElementById('sort-order-dropdown'),
            selectedSortOrder: document.getElementById('selected-sort-order'),
            sortOrderMenu: document.querySelector('#sort-order-dropdown .dropdown-menu')
        };

        // Chart colors
        const chartColors = {
            primary: "#648FFF",
            secondary: "#9e9e9e",
            success: "#4caf50",
            warning: "#ffc107",
            danger: "#f44336",
            info: "#00bcd4",
            dark: "#212121",
            purple: "#7a36b1",
            orange: "#FE6100",
            lime: "#cddc39",
            indigo: "#3f51b5",
            teal: "#009688",
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
                theme: "dark",
                shared: true,
                intersect: false
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
                enabled: false
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
            year: getCurrentYear(),
            sortOrder: 'normal'
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
                const data = await fetchData('perbandingan/get_titles_from_hierarchy', {
                    thang: selections.year
                });

                return data || [];
            } catch (error) {
                console.error('Error fetching titles from hierarchy:', error);
                // Fallback to original titles if error
                return [];
            }
        }

        async function fetchBoxplotData(titleCode) {
            try {
                const data = await fetchData('perbandingan/get_boxplot_data', {
                    thang: selections.year,
                    kode: titleCode
                });
                return data || [];
            } catch (error) {
                console.error('Error fetching boxplot data:', error);
                return [];
            }
        }

        async function fetchCompareData(titleCode) {
            try {
                const data = await fetchData('perbandingan/get_comparison_data', {
                    thang: selections.year,
                    kode: titleCode,
                    sortOrder: getCurrentSortOrder()
                });
                return data || [];
            } catch (error) {
                console.error('Error fetching compare data:', error);
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
            if (!elements.boxplotContainer) {
                console.warn('Boxplot container element not found');
                return;
            }

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
                    height: 500
                },
                plotOptions: {
                    boxPlot: {
                        colors: {
                            upper: chartColors.purple,
                            lower: chartColors.warning
                        }
                    }
                },
                xaxis: {
                    title: {
                        text: 'SBM'
                    },
                    labels: {
                        rotate: -90,
                        trim: false,
                        maxHeight: 120
                    }
                },
                yaxis: {
                    title: {
                        text: 'Biaya (Ribu Rupiah)'
                    },
                    labels: {
                        formatter: function(value) {
                            return new Intl.NumberFormat('id-ID').format(value);
                        }
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

        function renderCompareChart(data) {
            if (!elements.provinceChartContainer) {
                console.warn('Compare chart container element not found');
                return;
            }

            if (!data || data.length === 0) {
                // Update chart with no data
                if (charts.compare) {
                    charts.compare.updateOptions({
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

            const provinces = data.map(item => item.name);
            const valuesPrevious = data.map(item => item.biaya_previous / 1000);
            const valuesCurrent = data.map(item => item.biaya_current / 1000);
            const percentageChanges = data.map(item => item.percentage_change);

            const options = {
                series: [{
                    name: `Biaya ${selections.year}`,
                    data: valuesCurrent,
                    type: 'line'
                }, {
                    name: `Biaya ${selections.year - 1}`,
                    data: valuesPrevious,
                    type: 'line'
                }, {
                    name: '% Perubahan',
                    data: data.map(item => item.percentage_change),
                    type: 'line'
                }],
                chart: {
                    ...commonChartSettings,
                    type: 'line',
                    height: 500,
                    stacked: false
                },
                stroke: {
                    width: [3, 3, 0],
                    curve: 'smooth',
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
                        rotate: -90
                    },
                    title: {
                        text: 'Provinsi'
                    }
                },
                yaxis: [{
                        seriesName: `Biaya ${selections.year}`,
                        title: {
                            text: 'Biaya (Rupiah)'
                        },
                        labels: {
                            formatter: function(value) {
                                return new Intl.NumberFormat('id-ID').format(value);
                            }
                        },
                    },
                    {
                        opposite: true,
                        labels: {
                            show: false
                        }
                    },
                    {
                        opposite: true,
                        title: {
                            text: 'Persentase Perubahan (%)'
                        },
                        labels: {
                            formatter: function(value) {
                                return value + '%';
                            }
                        }
                    }
                ],
                tooltip: {
                    ...commonChartSettings.tooltip,
                    y: {
                        formatter: function(value, {
                            seriesIndex
                        }) {
                            if (seriesIndex === 2) {
                                return value + '%';
                            }
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value * 1000);
                        }
                    }
                },
                colors: [
                    chartColors.purple,
                    chartColors.warning,
                    chartColors.dark
                ],
                legend: {
                    position: 'top'
                },
                dataLabels: {
                    enabled: true,
                    enabledOnSeries: [2],
                    formatter: function(val) {
                        return val.toFixed(2) + '%';
                    },
                    style: {
                        fontSize: '10px',
                        colors: [chartColors.dark]
                    },
                    offsetY: -5
                },
                noData: noDataSettings
            };

            if (charts.compare) {
                charts.compare.updateOptions(options);
            } else {
                charts.compare = new ApexCharts(elements.provinceChartContainer, options);
                charts.compare.render();
            }

            // Update the table data
            updateCompareTable(data);
        }

        // Call this function to completely refresh the UI with the current sort preference
        function refreshSortUI() {
            if (!elements.selectedSortOrder) return;

            // Get display text for current sort order
            const sortOrderDisplayTexts = {
                'normal': 'Urutan Normal',
                'asc': 'Nilai Terendah',
                'desc': 'Nilai Tertinggi'
            };

            // Update the UI element
            elements.selectedSortOrder.textContent =
                sortOrderDisplayTexts[selections.sortOrder] || 'Urutan Normal';

            // Update the active class on menu items
            if (elements.sortOrderMenu) {
                const items = elements.sortOrderMenu.querySelectorAll('.dropdown-item');
                items.forEach(item => {
                    if (!item) return;
                    const itemValue = item.getAttribute('data-value');
                    if (itemValue === selections.sortOrder) {
                        item.classList.add('active');
                    } else {
                        item.classList.remove('active');
                    }
                });
            }
        }

        // Pagination variables
        let currentPage = 1;
        const itemsPerPage = 5;
        let totalPages = 0;

        function updateCompareTable(data) {
            if (!elements.provinceTableBody) return;

            // Update table headers first
            const tableHeaders = document.querySelector('#province-table thead tr');
            if (tableHeaders) {
                tableHeaders.innerHTML = `
                    <th>Provinsi</th>
                    <th>Biaya ${selections.year - 1} (Rp)</th>
                    <th>Biaya ${selections.year} (Rp)</th>
                    <th>Selisih (Rp)</th>
                    <th>Perubahan (%)</th>
                `;
            }

            // Calculate pagination
            totalPages = Math.ceil(data.length / itemsPerPage);
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = Math.min(startIndex + itemsPerPage, data.length);
            const currentData = data.slice(startIndex, endIndex);

            // Clear existing table content
            elements.provinceTableBody.innerHTML = '';

            // Create document fragment for better performance
            const fragment = document.createDocumentFragment();

            // Add data for current page
            currentData.forEach(item => {
                const row = document.createElement('tr');
                const changeClass = item.percentage_change > 0 ?
                    'text-success' : (item.percentage_change < 0 ? 'text-danger' : '');

                row.innerHTML = `
                    <td>${item.name}</td>
                    <td>${new Intl.NumberFormat('id-ID').format(item.biaya_previous)}</td>
                    <td>${new Intl.NumberFormat('id-ID').format(item.biaya_current)}</td>
                    <td class="${item.difference > 0 ? 'text-success' : (item.difference < 0 ? 'text-danger' : '')}">${new Intl.NumberFormat('id-ID').format(item.difference)}</td>
                    <td class="${changeClass}">${item.percentage_change.toFixed(2)}%</td>
                `;

                fragment.appendChild(row);
            });

            elements.provinceTableBody.appendChild(fragment);

            // Update pagination UI
            updatePagination(data.length);

            // Update showing entries text
            const showingElement = document.getElementById('showing-entries');
            const totalElement = document.getElementById('total-entries');
            if (showingElement && totalElement) {
                showingElement.textContent = `${startIndex + 1}-${endIndex}`;
                totalElement.textContent = data.length;
            }
        }

        function updatePagination(totalItems) {
            const paginationElement = document.getElementById('province-pagination');
            if (!paginationElement) return;

            // Calculate total pages properly
            totalPages = Math.max(1, Math.ceil(totalItems / itemsPerPage));

            // Ensure current page is valid
            if (currentPage > totalPages) {
                currentPage = totalPages;
            }

            paginationElement.innerHTML = '';

            // Previous button
            const prevLi = document.createElement('li');
            prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
            prevLi.innerHTML = `
                <a class="page-link" href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            `;
            prevLi.onclick = (e) => {
                e.preventDefault();
                if (currentPage > 1) {
                    currentPage--;
                    // Get filtered data based on current selections
                    const filteredData = getFilteredData();
                    updateCompareTable(filteredData);
                }
            };
            paginationElement.appendChild(prevLi);

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                const li = document.createElement('li');
                li.className = `page-item ${currentPage === i ? 'active' : ''}`;
                li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                li.onclick = (e) => {
                    e.preventDefault();
                    currentPage = i;
                    // Get filtered data based on current selections
                    const filteredData = getFilteredData();
                    updateCompareTable(filteredData);
                };
                paginationElement.appendChild(li);
            }

            // Next button
            const nextLi = document.createElement('li');
            nextLi.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
            nextLi.innerHTML = `
                <a class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            `;
            nextLi.onclick = (e) => {
                e.preventDefault();
                if (currentPage < totalPages) {
                    currentPage++;
                    // Get filtered data based on current selections
                    const filteredData = getFilteredData();
                    updateCompareTable(filteredData);
                }
            };
            paginationElement.appendChild(nextLi);
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

        function getFilteredData() {
            let filteredData = chartData.compare;

            if (selections.subtitle) {
                filteredData = filteredData.filter(item =>
                    item.subtitle === selections.subtitle ||
                    (!item.subtitle && !selections.subtitle)
                );
            }

            if (selections.subSubtitle) {
                filteredData = filteredData.filter(item =>
                    item.sub_subtitle === selections.subSubtitle
                );
            }

            return filteredData;
        }

        // Initialize sort order dropdown
        function initSortOrderDropdown() {
            if (!elements.sortOrderDropdown || !elements.selectedSortOrder || !elements.sortOrderMenu) return;

            // Add event listeners to dropdown items
            const sortItems = elements.sortOrderMenu.querySelectorAll('.dropdown-item');

            sortItems.forEach(item => {
                item.addEventListener('click', async function(e) {
                    e.preventDefault();

                    // Get the selected sort order
                    const sortOrder = this.getAttribute('data-value');

                    // Update UI
                    elements.selectedSortOrder.textContent = this.textContent;

                    // Apply new sort order and fetch fresh data
                    await applySortOrder(sortOrder);
                });
            });
        }

        // Get current sort order selection
        function getCurrentSortOrder() {
            return selections.sortOrder || 'normal';
        }

        // Apply sort order to all charts with fresh data fetch
        async function applySortOrder(sortOrder) {
            try {
                showLoader();

                // Update selection
                selections.sortOrder = sortOrder;

                // Only fetch province data with new sort order
                const compareData = await fetchCompareData(selections.title);
                chartData.compare = compareData;

                // Apply current subtitle/sub-subtitle filtering
                if (selections.subtitle) {
                    let filteredData = compareData.filter(item =>
                        item.subtitle === selections.subtitle ||
                        (!item.subtitle && !selections.subtitle)
                    );

                    // Apply sub-subtitle filter if available
                    if (selections.subSubtitle) {
                        filteredData = filteredData.filter(item =>
                            item.sub_subtitle === selections.subSubtitle
                        );
                        renderCompareChart(filteredData);
                    } else {
                        processSubSubtitles(filteredData);
                    }
                } else {
                    processSubSubtitles(compareData);
                }

                // Update UI for sort order
                refreshSortUI();

            } catch (error) {
                console.error('Error applying sort order:', error);
            } finally {
                hideLoader();
            }
        }

        // Process sub-subtitles
        function processSubSubtitles(data) {
            if (!data || data.length === 0) {
                // Hide dropdowns
                if (elements.subSubtitleDropdown) {
                    elements.subSubtitleDropdown.style.display = 'none';
                }
                if (elements.subtitleDropdown) {
                    elements.subtitleDropdown.style.display = 'none';
                }
                return;
            }

            // Check if we have any sub-subtitles
            const subSubtitles = getSubSubtitles(data);

            if (subSubtitles.length > 0) {
                // Update sub-subtitle dropdown
                updateSubSubtitleDropdown(subSubtitles);

                // Auto-select first sub-subtitle
                if (subSubtitles.length > 0 && elements.selectedSubSubtitle) {
                    const firstSubSubtitle = subSubtitles[0];
                    elements.selectedSubSubtitle.textContent = firstSubSubtitle;
                    handleSubSubtitleChange(firstSubSubtitle);
                }
            } else {
                // No sub-subtitles, hide dropdown
                if (elements.subSubtitleDropdown) {
                    elements.subSubtitleDropdown.style.display = 'none';
                }

                // Render province chart with all data
                renderCompareChart(data);
            }

            // Always hide subtitle dropdown (legacy UI element)
            if (elements.subtitleDropdown) {
                elements.subtitleDropdown.style.display = 'none';
            }
        }

        function handleSubSubtitleChange(subSubtitle) {
            // Skip if sub-subtitle hasn't actually changed
            if (selections.subSubtitle === subSubtitle) {
                return;
            }

            // Update selection
            selections.subSubtitle = subSubtitle;

            // Filter data by both subtitle and sub-subtitle
            const filteredProvinceData = chartData.compare.filter(item =>
                (item.subtitle === selections.subtitle || !selections.subtitle) &&
                item.sub_subtitle === subSubtitle
            );

            // Update province chart
            renderCompareChart(filteredProvinceData);
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

        async function loadCompareData(titleCode) {
            try {
                showLoader();

                // Use fetchCompareData instead
                const data = await fetchCompareData(titleCode);
                chartData.compare = data || [];

                // Reset selections
                selections.title = titleCode;
                selections.subSubtitle = null;

                // Get subtitle from boxplot if selected
                if (selections.subtitle) {
                    const filteredData = chartData.compare.filter(item =>
                        item.subtitle === selections.subtitle ||
                        (!item.subtitle && !selections.subtitle)
                    );
                    processSubSubtitles(filteredData);
                } else {
                    // Get first subtitle from data if available
                    const subtitles = [...new Set(chartData.compare.map(item => item.subtitle).filter(Boolean))];
                    if (subtitles.length > 0) {
                        selections.subtitle = subtitles[0];
                        const filteredData = chartData.compare.filter(item => item.subtitle === subtitles[0]);
                        processSubSubtitles(filteredData);
                    } else {
                        // No subtitles, use all data
                        processSubSubtitles(chartData.compare);
                    }
                }
            } catch (error) {
                console.error('Error loading comparison data:', error);

                // Hide dropdowns
                if (elements.subSubtitleDropdown) {
                    elements.subSubtitleDropdown.style.display = 'none';
                }
                if (elements.subtitleDropdown) {
                    elements.subtitleDropdown.style.display = 'none';
                }

                // Show empty province chart
                renderCompareChart([]);
            } finally {
                hideLoader();
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
                await loadCompareData(sbmCode);

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
            loadCompareData(titleCode);
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

            // Filter existing province data by subtitle
            const filteredData = chartData.compare.filter(item =>
                item.subtitle === subtitleValue || (!item.subtitle && !subtitleValue)
            );

            // Process sub-subtitles for the selected subtitle
            processSubSubtitles(filteredData);

            // Update boxplot chart with current subtitle
            const boxplotData = chartData.boxplot.filter(item =>
                item.subtitle === subtitleValue || (!item.subtitle && !subtitleValue)
            );
            renderBoxplotChart(boxplotData, subtitleValue);
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

        // Handle sort order change
        async function handleSortOrderChange(sortOrder) {
            await applySortOrder(sortOrder);
        }

        // Export public API
        return {
            // Initialize the module
            init: async function() {
                try {
                    initEventListeners();
                    initSortOrderDropdown();

                    await initYearDropdown();

                    const titles = await fetchTitlesFromHierarchy();

                    if (titles && titles.length > 0) {
                        updateTitleSelect(titles);
                    }

                    // Initial data load
                    const sbmCode = elements.sbmSelect ? elements.sbmSelect.value : '127';
                    await loadBoxplotData(sbmCode);
                    await loadCompareData(sbmCode);
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

                // Remove event listeners
                if (elements.sbmSelect) {
                    elements.sbmSelect.removeEventListener('change', handleTitleChange);
                }
                if (elements.subtitleSelect) {
                    elements.subtitleSelect.removeEventListener('change', handleSubtitleChange);
                }
                if (elements.refreshButton) {
                    elements.refreshButton.removeEventListener('click', refreshAllCharts);
                }
                document.removeEventListener('visibilitychange', visibilityChangeHandler);

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
