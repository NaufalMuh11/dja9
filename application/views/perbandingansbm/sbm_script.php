<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Store common values
        const baseUrl = '<?= base_url(); ?>';
        const refreshInterval = 5 * 60 * 1000;
        let lastRefreshTime = new Date();
        let refreshTimer;
        let charts = {};
        let boxplotChart;
        let barDetailChart;
        let barData = [];
        let boxplotData = [];
        let currentSelections = {
            title: '127',
            subtitle: null,
            subSubtitle: null
        };
        let currentBoxplotSubtitle = null;

        // Display last update
        updateLastUpdateTime();

        // Common chart colors
        const chartColors = {
            primary: "#648FFF",
            secondary: tabler.getColor("secondary"),
            success: tabler.getColor("green"),
            warning: "#ffb000",
            danger: tabler.getColor("red"),
            info: tabler.getColor("cyan"),
            dark: tabler.getColor("dark"),
            purple: "#785EF0",
            orange: "#FE6100",
            lime: tabler.getColor("lime"),
            indigo: tabler.getColor("indigo"),
            teal: tabler.getColor("teal"),
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

        // Initialize subtitle select with disabled state
        const subtitleSelect = document.getElementById('sbm-subtitle-select');
        subtitleSelect.disabled = true;
        subtitleSelect.innerHTML = '<option disabled selected>Subjudul</option>';

        // Add event listener for SBM dropdown
        document.querySelector('.form-select').addEventListener('change', function() {
            const selectedSbmCode = this.value;

            // Reset boxplot subtitle selection
            currentBoxplotSubtitle = null;

            // Reset and disable subtitle select
            subtitleSelect.disabled = true;
            subtitleSelect.innerHTML = '<option disabled selected>Subjudul</option>';

            // Reset bar chart selections
            currentSelections = {
                title: selectedSbmCode,
                subtitle: null,
                subSubtitle: null
            };

            // Update both charts with the selected SBM code
            updateBoxplotChart(selectedSbmCode);
            loadBarData(selectedSbmCode);
        });

        // Initialize with default SBM code (127)
        const defaultSbmCode = '127';
        document.querySelector('.form-select').value = defaultSbmCode;

        // Function for updating boxplot chart
        async function updateBoxplotChart(titleCode) {
            try {
                showRefreshIndicator();
                const selectedYear = document.getElementById('selected_thang').value;
                const response = await fetch(`${baseUrl}perbandingan/get_boxplot_data/${titleCode}/${selectedYear}`);
                if (!response.ok) throw new Error('Network response was not ok');

                const data = await response.json();
                if (!data || data.length === 0) {
                    console.warn('No boxplot data received');
                    if (boxplotChart) {
                        boxplotChart.updateOptions({
                            series: [],
                            noData: noDataSettings
                        });
                    }
                    // Disable subtitle select
                    subtitleSelect.disabled = true;
                    subtitleSelect.innerHTML = '<option disabled selected>Subjudul</option>';
                    return;
                }

                // Store the original data
                boxplotData = data;

                // Check if data has subtitle property
                const hasSubtitles = data.some(item => item.hasOwnProperty('subtitle'));

                if (hasSubtitles) {
                    // Get unique subtitles
                    const subtitles = [...new Set(data.map(item => item.subtitle))];

                    // Populate subtitle select and enable it
                    populateSubtitleSelect(subtitles);

                    // Set initial subtitle if not already set
                    if (!currentBoxplotSubtitle && subtitles.length > 0) {
                        currentBoxplotSubtitle = subtitles[0];
                        subtitleSelect.value = currentBoxplotSubtitle;
                    }

                    // Filter data by current subtitle
                    const filteredData = data.filter(item =>
                        !currentBoxplotSubtitle || item.subtitle === currentBoxplotSubtitle
                    );

                    // Update chart with filtered data
                    updateBoxplotChartWithData(filteredData, currentBoxplotSubtitle);
                } else {
                    // No subtitles, use all data
                    subtitleSelect.disabled = true;
                    subtitleSelect.innerHTML = '<option disabled selected>Subjudul</option>';
                    currentBoxplotSubtitle = null;
                    updateBoxplotChartWithData(data, null);
                }
            } catch (error) {
                console.error('Error updating boxplot chart:', error);
                // Show error in chart area
                if (boxplotChart) {
                    boxplotChart.updateOptions({
                        series: [],
                        noData: {
                            text: 'Error memuat data. Silakan coba lagi.',
                            align: 'center',
                            verticalAlign: 'middle',
                            style: {
                                color: '#ff0000',
                                fontSize: '14px'
                            }
                        }
                    });
                }
                // Disable subtitle select on error
                subtitleSelect.disabled = true;
                subtitleSelect.innerHTML = '<option disabled selected>Subjudul</option>';
            } finally {
                hideRefreshIndicator();
            }
        }

        // Function to populate subtitle select
        function populateSubtitleSelect(subtitles) {
            subtitleSelect.innerHTML = '';

            // First option - disabled
            const defaultOption = document.createElement('option');
            defaultOption.value = "";
            defaultOption.textContent = "";
            defaultOption.disabled = true;
            defaultOption.selected = !currentBoxplotSubtitle;
            subtitleSelect.appendChild(defaultOption);

            // Add subtitle options
            subtitles.forEach(subtitle => {
                const option = document.createElement('option');
                option.value = subtitle;
                option.textContent = subtitle;
                if (subtitle === currentBoxplotSubtitle) {
                    option.selected = true;
                }
                subtitleSelect.appendChild(option);
            });

            // Enable the select
            subtitleSelect.disabled = false;

            // Add change event listener
            if (!subtitleSelect.hasEventListener) {
                subtitleSelect.addEventListener('change', (e) => {
                    const selectedSubtitle = e.target.value;
                    if (selectedSubtitle) {
                        // Update current subtitle
                        currentBoxplotSubtitle = selectedSubtitle;

                        // Filter and update boxplot chart
                        const filteredBoxplotData = boxplotData.filter(item => item.subtitle === selectedSubtitle);
                        updateBoxplotChartWithData(filteredBoxplotData, selectedSubtitle);

                        // Also update bar chart subtitle selection
                        // Instead of updating the subtitle dropdown, we should update the title and subtitle
                        // of the bar chart based on the selected subtitle from boxplot
                        currentSelections.subtitle = selectedSubtitle;
                        currentSelections.subSubtitle = null;

                        // Filter bar data with the selected subtitle
                        const filteredBarData = barData.filter(item =>
                            (item.subtitle === selectedSubtitle) || (!item.subtitle && !selectedSubtitle)
                        );

                        // Update sub-subtitle dropdown if needed
                        handleTitleAndSubtitleSelection(filteredBarData);
                    }
                });
                subtitleSelect.hasEventListener = true;
            }
        }

        // Function to update boxplot chart with filtered data
        function updateBoxplotChartWithData(data, subtitle) {
            if (!data || data.length === 0) {
                console.warn('No boxplot data to display');
                if (boxplotChart) {
                    boxplotChart.updateOptions({
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

            // Get the selected SBM text
            const mainTitle = document.querySelector('.form-select option:checked').text;

            // Update the custom title elements
            document.getElementById('boxplot-main-title').textContent = mainTitle;

            // Update subtitle if available
            const subtitleEl = document.getElementById('boxplot-subtitle');
            if (subtitle) {
                subtitleEl.textContent = subtitle;
                subtitleEl.style.display = 'block';
            } else {
                subtitleEl.style.display = 'none';
            }

            // Make sure note is visible
            document.getElementById('boxplot-note').style.display = 'block';

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
                        rotate: -45,
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

            if (boxplotChart) {
                boxplotChart.updateOptions(options);
            } else {
                boxplotChart = new ApexCharts(document.querySelector("#chart-boxplot"), options);
                boxplotChart.render();
            }
        }

        // Function to load bar data
        async function loadBarData(titleCode) {
            try {
                showRefreshIndicator();
                const selectedYear = document.getElementById('selected_thang').value;
                const response = await fetch(`${baseUrl}perbandingan/get_bar_data/${titleCode}/${selectedYear}`);
                if (!response.ok) {
                    throw new Error(`Network response was not ok: ${response.status}`);
                }

                const data = await response.json();
                if (!Array.isArray(data) || data.length === 0) {
                    console.warn('Bar data is empty or not an array:', data);
                    barData = [];

                    // Update bar chart to show no data
                    updateBarChartWithNoData();
                    return;
                }

                // Store the data
                barData = data;
                console.log('Loaded bar data:', barData.length, 'items');

                // Reset selections
                currentSelections = {
                    title: titleCode,
                    subtitle: null,
                    subSubtitle: null
                };

                // Process the data for the bar chart
                handleTitleAndSubtitleSelection(barData);
            } catch (error) {
                console.error('Error loading bar data:', error);
                // Show error message
                if (barDetailChart) {
                    barDetailChart.updateOptions({
                        series: [{
                            name: 'Biaya',
                            data: []
                        }],
                        xaxis: {
                            categories: []
                        },
                        noData: {
                            text: 'Error memuat data. Silakan coba lagi.',
                            align: 'center',
                            verticalAlign: 'middle',
                            style: {
                                color: '#ff0000',
                                fontSize: '14px'
                            }
                        }
                    });
                }
                // Hide dropdowns
                document.getElementById('subtitle-dropdown').style.display = 'none';
                document.getElementById('sub-subtitle-dropdown').style.display = 'none';
            } finally {
                hideRefreshIndicator();
            }
        }

        // Function to handle title and subtitle selection for the bar chart
        function handleTitleAndSubtitleSelection(data) {
            if (!data || data.length === 0) {
                updateBarChartWithNoData();
                return;
            }

            // Check if we have any subtitles in the data
            const hasSubtitles = data.some(item => item.hasOwnProperty('subtitle') && item.subtitle);

            if (hasSubtitles) {
                // Get unique subtitles
                const subtitles = [...new Set(data.map(item => item.subtitle).filter(Boolean))];

                // Update subtitle dropdown
                updateSubtitleDropdown(subtitles);

                // If we have a selected subtitle, filter by it
                if (currentSelections.subtitle && subtitles.includes(currentSelections.subtitle)) {
                    document.getElementById('selected-subtitle').textContent = currentSelections.subtitle;
                    handleSubtitleChange(currentSelections.subtitle);
                } else if (subtitles.length > 0) {
                    // Auto-select first subtitle
                    document.getElementById('selected-subtitle').textContent = subtitles[0];
                    handleSubtitleChange(subtitles[0]);
                }
            } else {
                // No subtitles, hide dropdowns
                document.getElementById('subtitle-dropdown').style.display = 'none';
                document.getElementById('sub-subtitle-dropdown').style.display = 'none';

                // Update chart with all data
                updateBarChartWithData(data);
            }
        }

        // Function to update subtitle dropdown
        function updateSubtitleDropdown(subtitles) {
            const subtitleDropdown = document.getElementById('subtitle-dropdown');
            const subtitleMenu = document.getElementById('subtitle-dropdown-menu');

            // Clear existing menu items
            subtitleMenu.innerHTML = '';

            if (subtitles.length === 0) {
                // Hide dropdown if no subtitles
                subtitleDropdown.style.display = 'none';
                return;
            }

            // Add subtitle options
            subtitles.forEach(subtitle => {
                const item = document.createElement('a');
                item.className = 'dropdown-item';
                item.href = '#';
                item.textContent = subtitle;
                item.setAttribute('data-value', subtitle);
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const selectedValue = this.getAttribute('data-value');
                    document.getElementById('selected-subtitle').textContent = selectedValue;
                    handleSubtitleChange(selectedValue);

                    // Also update boxplot subtitle if it exists in boxplot data
                    updateBoxplotSubtitleIfExists(selectedValue);
                });
                subtitleMenu.appendChild(item);
            });

            // Show dropdown
            subtitleDropdown.style.display = 'block';
        }

        // Function to update boxplot subtitle if the selected subtitle exists in boxplot data
        function updateBoxplotSubtitleIfExists(subtitle) {
            if (boxplotData && boxplotData.some(item => item.subtitle === subtitle)) {
                currentBoxplotSubtitle = subtitle;
                subtitleSelect.value = subtitle;
                const filteredBoxplotData = boxplotData.filter(item => item.subtitle === subtitle);
                updateBoxplotChartWithData(filteredBoxplotData, subtitle);
            }
        }

        // Function to handle subtitle change
        function handleSubtitleChange(subtitle) {
            // Update selection
            currentSelections.subtitle = subtitle;
            currentSelections.subSubtitle = null;

            // Filter data by subtitle
            const filteredData = barData.filter(item => item.subtitle === subtitle);

            // Check if the filtered data has sub-subtitles
            const subSubtitles = getSubSubtitlesFromData(filteredData);

            if (subSubtitles.length > 0) {
                // Update sub-subtitle dropdown
                updateSubSubtitleDropdown(subSubtitles);

                // Auto-select first sub-subtitle
                if (subSubtitles.length > 0) {
                    document.getElementById('selected-sub-subtitle').textContent = subSubtitles[0];
                    handleSubSubtitleChange(subSubtitles[0]);
                }
            } else {
                // No sub-subtitles, hide dropdown
                document.getElementById('sub-subtitle-dropdown').style.display = 'none';

                // Update chart with all data for this subtitle
                updateBarChartWithData(filteredData);
            }
        }

        // Function to extract sub-subtitles from data
        function getSubSubtitlesFromData(data) {
            // Extract unique sub_subtitle values
            return [...new Set(data
                .filter(item => item.hasOwnProperty('sub_subtitle') && item.sub_subtitle)
                .map(item => item.sub_subtitle))];
        }

        // Function to update sub-subtitle dropdown
        function updateSubSubtitleDropdown(subSubtitles) {
            const subSubtitleDropdown = document.getElementById('sub-subtitle-dropdown');
            const subSubtitleMenu = document.getElementById('sub-subtitle-dropdown-menu');
            const selectedSubSubtitle = document.getElementById('selected-sub-subtitle');

            // Clear existing menu items
            subSubtitleMenu.innerHTML = '';

            if (subSubtitles.length === 0) {
                // Hide dropdown if no sub-subtitles
                subSubtitleDropdown.style.display = 'none';
                selectedSubSubtitle.textContent = 'Pilih Sub-subjudul';
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
                    selectedSubSubtitle.textContent = selectedValue;
                    handleSubSubtitleChange(selectedValue);
                });
                subSubtitleMenu.appendChild(item);
            });

            // Show dropdown
            subSubtitleDropdown.style.display = 'block';

            // Auto-select first option
            if (subSubtitles.length > 0) {
                selectedSubSubtitle.textContent = subSubtitles[0];
                handleSubSubtitleChange(subSubtitles[0]);
            }
        }

        // Function to handle sub-subtitle change
        function handleSubSubtitleChange(subSubtitle) {
            // Update selection
            currentSelections.subSubtitle = subSubtitle;

            // Filter data by both subtitle and sub-subtitle
            const filteredData = barData.filter(item =>
                item.subtitle === currentSelections.subtitle &&
                item.sub_subtitle === subSubtitle
            );

            // Update chart with filtered data
            updateBarChartWithData(filteredData);
        }

        // Function to update bar chart with data
        function updateBarChartWithData(data) {
            if (!data || data.length === 0) {
                updateBarChartWithNoData();
                return;
            }

            // Prepare data for chart
            const categories = data.map(item => item.name);
            const values = data.map(item => parseFloat(item.data) / 1000); // Convert to thousands

            // Update the custom title elements
            document.getElementById('bar-chart-main-title').textContent = 'Detail';

            // Get subtitle text if selected
            let subtitleText = "";
            if (currentSelections.subtitle) {
                subtitleText = currentSelections.subtitle;

                // Add sub-subtitle if selected
                if (currentSelections.subSubtitle) {
                    subtitleText += " - " + currentSelections.subSubtitle;
                }
            }

            // Update subtitle element
            const subtitleEl = document.getElementById('bar-chart-subtitle');
            if (subtitleEl) {
                if (subtitleText) {
                    subtitleEl.textContent = subtitleText;
                    subtitleEl.style.display = 'block';
                } else {
                    subtitleEl.style.display = 'none';
                }
            }

            // Update bar chart
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
                        rotate: -45,
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

            if (barDetailChart) {
                barDetailChart.updateOptions(options);
            } else {
                barDetailChart = new ApexCharts(document.querySelector("#chart-bar-detail"), options);
                barDetailChart.render();
            }
        }

        // Helper function to update bar chart with no data
        function updateBarChartWithNoData() {
            // Update title using the selected SBM text
            const mainTitle = document.querySelector('.form-select option:checked').text;
            document.getElementById('bar-chart-main-title').textContent = mainTitle;

            // Hide subtitle if exists
            const subtitleEl = document.getElementById('bar-chart-subtitle');
            if (subtitleEl) {
                subtitleEl.style.display = 'none';
            }

            // Hide dropdowns
            document.getElementById('subtitle-dropdown').style.display = 'none';
            document.getElementById('sub-subtitle-dropdown').style.display = 'none';

            if (barDetailChart) {
                barDetailChart.updateOptions({
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
                barDetailChart = new ApexCharts(document.querySelector("#chart-bar-detail"), {
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
                barDetailChart.render();
            }
        }

        // Function to update all charts
        async function refreshAllCharts() {
            showRefreshIndicator();

            try {
                // Update the last update time first
                await updateLastUpdateTime();

                // Get current selected SBM code
                const sbmCode = document.querySelector('.form-select').value;

                // Update charts with current selection
                await updateBoxplotChart(sbmCode);
                await loadBarData(sbmCode);

                // Update lastRefreshTime after successful refresh
                lastRefreshTime = new Date();
            } catch (error) {
                console.error('Error refreshing charts:', error);
            } finally {
                hideRefreshIndicator();
            }
        }

        // Function to update the last update time
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

            document.getElementById('lastUpdate').innerHTML = `
        <div class="text-end">${dateStr}</div>
        <div class="mt-1 small text-muted text-end">
            Update terakhir: <b>${timeStr}</b> WIB
        </div>
    `;
        }

        // Add event listener for refresh button
        document.getElementById('refreshButton').addEventListener('click', function() {
            this.disabled = true;
            refreshAllCharts().finally(() => {
                this.disabled = false;
            });
        });

        // Show refresh indicator
        function showRefreshIndicator() {
            document.getElementById('refresh-indicator').style.display = 'block';
        }

        // Hide refresh indicator
        function hideRefreshIndicator() {
            document.getElementById('refresh-indicator').style.display = 'none';
        }

        // Initialize auto-refresh
        function startAutoRefresh() {
            // Clear any existing timer
            if (refreshTimer) {
                clearInterval(refreshTimer);
            }

            // Set up new timer
            refreshTimer = setInterval(refreshAllCharts, refreshInterval);
        }

        // Initialize Thang dropdown
        async function initThangDropdown() {
            const dropdownMenu = document.querySelector('#selectThang .dropdown-menu');
            const dropdownToggle = document.querySelector('#selectThang .dropdown-toggle');
            const selectedThangInput = document.getElementById('selected_thang');

            // Clear dropdown menu
            dropdownMenu.innerHTML = '';

            try {
                const response = await fetch(`${baseUrl}dashboard?q=rangeThang`);
                if (!response.ok) throw new Error('Network response was not ok');

                const years = await response.json();

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
                        dropdownToggle.textContent = ` T.A. ${year}`;
                        selectedThangInput.value = year;

                        // Refresh data for the selected year
                        refreshAllCharts();
                    });
                    dropdownMenu.appendChild(item);
                });

                // Set default value to current year
                const defaultYear = years[0] || '2025';
                dropdownToggle.textContent = ` T.A. ${defaultYear}`;
                selectedThangInput.value = defaultYear;

                // Set active class on default item
                const defaultItem = Array.from(document.querySelectorAll('#selectThang .dropdown-item'))
                    .find(item => item.textContent.includes(defaultYear));
                if (defaultItem) {
                    defaultItem.classList.add('active');
                }
            } catch (error) {
                console.error('Error fetching years:', error);
                // Set fallback years if fetch fails
                const fallbackYears = ['2025'];
                dropdownToggle.textContent = ` T.A. ${fallbackYears[0]}`;
                selectedThangInput.value = fallbackYears[0];
            }
        }

        // Initialize everything
        async function init() {
            await initThangDropdown();

            updateBoxplotChart('127');
            loadBarData('127');
            startAutoRefresh();
        }

        // Start the initialization
        init();
    });
</script>
