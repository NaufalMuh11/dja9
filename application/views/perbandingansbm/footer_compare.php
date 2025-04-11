<!-- ApexCharts JS -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.41.0/dist/apexcharts.min.js"></script>

<!-- Chart JS and Functionality -->
<script>
    // Add this at the end of your existing script
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize variables
        let provinceChart;
        let provinceData = [];

        // Function to fetch data from the server
        function fetchProvinceData() {
            const costType = document.getElementById('cost-type').value;
            const searchTerm = document.getElementById('province-search').value;

            return $.ajax({
                url: '<?= base_url('GrafikSBMCompare/get_province_comparison_data_ajax') ?>',
                method: 'GET',
                data: {
                    cost_type: costType,
                    search: searchTerm
                },
                dataType: 'json'
            });
        }

        // Function to sort data
        function sortProvinceData(data, sortOrder) {
            if (sortOrder === 'normal') {
                return data;
            } else if (sortOrder === 'asc') {
                return [...data].sort((a, b) => a.biaya_2026 - b.biaya_2026);
            } else if (sortOrder === 'desc') {
                return [...data].sort((a, b) => b.biaya_2026 - a.biaya_2026);
            } else if (sortOrder === 'change_asc') {
                return [...data].sort((a, b) => a.percentage_change - b.percentage_change);
            } else if (sortOrder === 'change_desc') {
                return [...data].sort((a, b) => b.percentage_change - a.percentage_change);
            }
            return data;
        }

        // Function to update the province chart
        function updateProvinceChart() {
            const sortOrder = document.getElementById('province-sort').value;
            const data = sortProvinceData(provinceData, sortOrder);

            // Limit to top 15 provinces for better visibility if there are more than 15
            const displayData = data.length > 15 ? data.slice(0, 15) : data;

            const provinces = displayData.map(item => item.province);
            const values2025 = displayData.map(item => item.biaya_2025);
            const values2026 = displayData.map(item => item.biaya_2026);
            const percentageChanges = displayData.map(item => item.percentage_change);

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
                        // type: 'bar',
                        yAxisIndex: 1 // Use the right y-axis
                    }
                ],
                chart: {
                    height: 500,
                    type: 'line',
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            selection: true,
                            zoom: true,
                            zoomin: true,
                            zoomout: true,
                            pan: true,
                            reset: true
                        }
                    }
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
                        rotate: -45,
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
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: function(value, {
                            seriesIndex
                        }) {
                            if (seriesIndex === 2) { // Percentage change series
                                return value.toFixed(2) + '%';
                            }
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                },
                colors: ['#4169E1', '#32CD32'],
                title: {
                    text: 'Perbandingan Satuan Biaya per Provinsi Tahun 2025 vs 2026',
                    align: 'center',
                    style: {
                        fontSize: '16px',
                        fontWeight: 'bold'
                    }
                },
                legend: {
                    position: 'top'
                },
                grid: {
                    borderColor: '#e7e7e7',
                    row: {
                        colors: ['#f3f3f3', 'transparent'],
                        opacity: 0.5
                    }
                },
                dataLabels: {
                    enabled: true,
                    enabledOnSeries: [2], // Only enable for percentage bars
                    formatter: function(val) {
                        return val.toFixed(2) + '%';
                    },
                    style: {
                        fontSize: '10px',
                        colors: ['#333']
                    },
                    offsetY: -5
                }
            };

            if (provinceChart) {
                provinceChart.updateOptions(options);
            } else {
                provinceChart = new ApexCharts(document.getElementById('province-chart-container'), options);
                provinceChart.render();
            }

            // Update the table data
            updateProvinceTable(displayData);
        }

        // Function to update the province table
        function updateProvinceTable(data) {
            const tableBody = document.getElementById('province-table-body');
            tableBody.innerHTML = '';

            data.forEach(item => {
                const row = document.createElement('tr');

                // Add CSS class for positive/negative values
                const changeClass = item.percentage_change > 0 ? 'text-success' : (item.percentage_change < 0 ? 'text-danger' : '');

                row.innerHTML = `
                <td>${item.province}</td>
                <td>${new Intl.NumberFormat('id-ID').format(item.biaya_2025)}</td>
                <td>${new Intl.NumberFormat('id-ID').format(item.biaya_2026)}</td>
                <td class="${item.difference > 0 ? 'text-success' : (item.difference < 0 ? 'text-danger' : '')}">${new Intl.NumberFormat('id-ID').format(item.difference)}</td>
                <td class="${changeClass}">${item.percentage_change.toFixed(2)}%</td>
            `;

                tableBody.appendChild(row);
            });
        }

        // Function to load initial data
        function loadProvinceData() {
            fetchProvinceData()
                .done(function(response) {
                    provinceData = response;
                    updateProvinceChart();
                })
                .fail(function(xhr, status, error) {
                    console.error('Error fetching province data:', error);
                });
        }

        // Add event listeners for filtering and sorting
        if (document.getElementById('cost-type')) {
            document.getElementById('cost-type').addEventListener('change', loadProvinceData);
        }

        if (document.getElementById('province-search')) {
            document.getElementById('province-search').addEventListener('input', function() {
                // Add debounce to avoid too many requests
                clearTimeout(this.timer);
                this.timer = setTimeout(loadProvinceData, 500);
            });
        }

        if (document.getElementById('province-sort')) {
            document.getElementById('province-sort').addEventListener('change', updateProvinceChart);
        }

        // Initial data load for province comparison
        if (document.getElementById('province-chart-container')) {
            loadProvinceData();
        }
    });
</script>