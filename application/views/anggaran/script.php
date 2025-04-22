<script>
	document.addEventListener("DOMContentLoaded", function() {
    // Fungsi untuk mendapatkan warna berdasarkan tema
    function getThemeColors() {
        const isDarkMode = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        
        return {
            textColor: isDarkMode ? '#e0e0e0' : '#333333',
            gridColor: isDarkMode ? '#444444' : '#e0e0e0',
            borderColor: isDarkMode ? '#555555' : '#e0e0e0',
            chartColors: isDarkMode ? 
                ['#6f42c1', '#ffd54f', '#ff5252', '#4caf50', '#ff9800'] : 
                ['#6f42c1', '#ffc107', '#dc3545', '#198754', '#ff9800'],
            backgroundColor: isDarkMode ? 'transparent' : 'transparent',
            tooltipBackground: isDarkMode ? '#424242' : '#fff',
            tooltipTextColor: isDarkMode ? '#e0e0e0' : '#333333'
        };
    }
    
    // Fungsi untuk memperbarui chart ketika tema berubah
    function updateChartsTheme() {
        const colors = getThemeColors();
        
        // Update Access Chart
        if (window.chart) {
            window.chart.updateOptions({
                chart: {
                    background: colors.backgroundColor
                },
                grid: {
                    borderColor: colors.gridColor,
                },
                xaxis: {
                    labels: {
                        style: {
                            colors: colors.textColor
                        }
                    },
                    title: {
                        style: {
                            color: colors.textColor
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: colors.textColor
                        }
                    },
                    title: {
                        style: {
                            color: colors.textColor
                        }
                    }
                },
                legend: {
                    labels: {
                        colors: colors.textColor
                    }
                },
                colors: [colors.chartColors[0], colors.chartColors[1]],
                tooltip: {
                    theme: colors.tooltipBackground === '#424242' ? 'dark' : 'light',
                    style: {
                        fontSize: '12px',
                        fontFamily: undefined
                    }
                }
            });
        }
        
        // Update Module Access Chart
        if (window.chartModul) {
            window.chartModul.updateOptions({
                chart: {
                    background: colors.backgroundColor
                },
                colors: colors.chartColors,
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                total: {
                                    color: colors.textColor
                                },
                                value: {
                                    color: colors.textColor
                                }
                            }
                        }
                    }
                },
                legend: {
                    labels: {
                        colors: colors.textColor
                    }
                },
                tooltip: {
                    theme: colors.tooltipBackground === '#424242' ? 'dark' : 'light',
                    style: {
                        fontSize: '12px',
                        fontFamily: undefined
                    }
                }
            });
        }
        
        // Update Module Activity Chart
        if (window.moduleActivityChart) {
            window.moduleActivityChart.updateOptions({
                chart: {
                    background: colors.backgroundColor
                },
                colors: colors.chartColors,
                grid: {
                    borderColor: colors.gridColor
                },
                xaxis: {
                    labels: {
                        style: {
                            colors: colors.textColor
                        }
                    },
                    title: {
                        style: {
                            color: colors.textColor
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: colors.textColor
                        }
                    },
                    title: {
                        style: {
                            color: colors.textColor
                        }
                    }
                },
                legend: {
                    labels: {
                        colors: colors.textColor
                    }
                },
                tooltip: {
                    theme: colors.tooltipBackground === '#424242' ? 'dark' : 'light',
                    style: {
                        fontSize: '12px',
                        fontFamily: undefined
                    }
                }
            });
        }
    }
    
    // Last update
    document.getElementById('lastUpdate').innerHTML = `<?php echo get_last_update(); ?>`;

    // Access Chart
    var hourlyData = <?php echo $hourly_users; ?>;
    const colors = getThemeColors();
    
    var options = {
        series: [{
            name: 'Tahun Ini',
            data: hourlyData.current_year
        }, {
            name: 'Tahun Lalu',
            data: hourlyData.previous_year
        }],
        chart: {
            height: 270,
            type: 'line',
            toolbar: {
                show: false
            },
            zoom: {
                enabled: false
            },
            background: colors.backgroundColor
        },
        colors: [colors.chartColors[0], colors.chartColors[1]],
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        grid: {
            show: true,
            borderColor: colors.gridColor,
            xaxis: {
                lines: {
                    show: false
                }
            },
            yaxis: {
                lines: {
                    show: true
                }
            },
            padding: {
                top: 0,
                right: 20,
                bottom: 0,
                left: 10
            }
        },
        xaxis: {
            categories: ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12',
                '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'
            ],
            axisBorder: {
                show: true,
                color: colors.borderColor
            },
            axisTicks: {
                show: true,
                color: colors.borderColor
            },
            title: {
                text: 'Jam',
                offsetY: 0,
                offsetX: 0,
                style: {
                    fontSize: '12px',
                    color: colors.textColor
                }
            },
            labels: {
                offsetY: 5,
                style: {
                    colors: colors.textColor
                }
            }
        },
        yaxis: {
            min: 0,
            max: Math.max(...hourlyData.current_year, ...hourlyData.previous_year) + 5,
            tickAmount: 6,
            labels: {
                formatter: function(val) {
                    return val.toFixed(0);
                },
                style: {
                    colors: colors.textColor
                }
            },
            title: {
                text: 'Jumlah',
                style: {
                    fontSize: '12px',
                    color: colors.textColor
                }
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'center',
            offsetX: 0,
            offsetY: 0,
            markers: {
                width: 8,
                height: 8,
                radius: 12
            },
            itemMargin: {
                horizontal: 15
            },
            labels: {
                colors: colors.textColor
            }
        },
        tooltip: {
            theme: colors.tooltipBackground === '#424242' ? 'dark' : 'light',
            style: {
                fontSize: '12px',
                fontFamily: undefined
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#accessChart"), options);
    chart.render();
    window.chart = chart; // Simpan referensi chart

    // Module Access Chart
    const moduleDistribution = <?php echo $module_distribution ?? '[]'; ?>;
    const totalModules = moduleDistribution.reduce((sum, item) => sum + parseInt(item.access_count), 0);

    var chartModul;

    // Check if data exists
    if (!moduleDistribution || moduleDistribution.length === 0 || !totalModules) {
        // Create a div to replace the chart with a message
        const chartContainer = document.getElementById("chart-akses-modul");

        // Clear any existing content
        chartContainer.innerHTML = '';

        // Create and style the no data message container
        const noDataDiv = document.createElement('div');
        noDataDiv.style.display = 'flex';
        noDataDiv.style.flexDirection = 'column';
        noDataDiv.style.alignItems = 'center';
        noDataDiv.style.justifyContent = 'center';
        noDataDiv.style.height = '350px';
        noDataDiv.style.width = '100%';

        // Add an icon (using a simple SVG)
        const iconDiv = document.createElement('div');
        iconDiv.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="${colors.textColor}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <circle cx="12" cy="12" r="10"></circle>
              <line x1="12" y1="8" x2="12" y2="12"></line>
              <line x1="12" y1="16" x2="12.01" y2="16"></line>
          </svg>
      `;

        // Add text message
        const messageDiv = document.createElement('div');
        messageDiv.textContent = 'Tidak ada aktivitas';
        messageDiv.style.marginTop = '16px';
        messageDiv.style.fontSize = '16px';
        messageDiv.style.color = colors.textColor;
        messageDiv.style.fontWeight = '500';

        // Append elements to container
        noDataDiv.appendChild(iconDiv);
        noDataDiv.appendChild(messageDiv);
        chartContainer.appendChild(noDataDiv);
    } else {
        // Initialize chart with data
        chartModul = new ApexCharts(
            document.getElementById("chart-akses-modul"), {
                chart: {
                    type: "donut",
                    height: 350,
                    background: colors.backgroundColor,
                    offsetY: 30,
                    offsetX: -10,
                    events: {
                        dataPointSelection: function(event, chartContext, config) {
                            setTimeout(() => {
                                const totalLabel = document.querySelector('.apexcharts-datalabels-group .apexcharts-datalabel-label');
                                const totalValue = document.querySelector('.apexcharts-datalabels-group .apexcharts-datalabel-value');

                                if (totalLabel) {
                                    totalLabel.style.fill = colors.textColor;
                                }

                                if (totalValue) {
                                    totalValue.style.fill = colors.textColor;
                                }
                            }, 50);
                        }
                    },
                    noData: {
                        text: 'Tidak ada aktivitas',
                        align: 'center',
                        verticalAlign: 'middle',
                        offsetX: 0,
                        offsetY: 0,
                        style: {
                            color: colors.textColor,
                            fontSize: '16px',
                            fontFamily: undefined
                        }
                    }
                },
                series: moduleDistribution.map(item => parseInt(item.access_count)),
                labels: moduleDistribution.map(item => item.keterangan),
                colors: colors.chartColors,
                plotOptions: {
                    pie: {
                        offsetY: 10,
                        startAngle: 0,
                        endAngle: 360,
                        donut: {
                            size: '75%',
                            labels: {
                                show: true,
                                name: {
                                    offsetY: -10
                                },
                                value: {
                                    offsetY: 0
                                },
                                total: {
                                    show: true,
                                    showAlways: true,
                                    label: 'Jumlah Modul Yang Diakses',
                                    fontSize: '12px',
                                    fontWeight: 400,
                                    color: colors.textColor,
                                    offsetY: -10,
                                    formatter: function() {
                                        return totalModules.toLocaleString('id-ID') + ' Modul';
                                    }
                                },
                                value: {
                                    show: true,
                                    fontSize: '24px',
                                    fontWeight: 700,
                                    color: colors.textColor,
                                    offsetY: 5,
                                    formatter: function(val) {
                                        return parseInt(val).toLocaleString('id-ID') + ' Aktivitas';
                                    }
                                }
                            }
                        }
                    }
                },
                stroke: {
                    width: 0
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val, opts) {
                        return Math.round(val) + '%';
                    },
                    dropShadow: {
                        enabled: false
                    },
                    style: {
                        colors: ['#fff']
                    }
                },
                tooltip: {
                    custom: function({
                        series,
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) {
                        const label = w.config.labels[seriesIndex];
                        const value = series[seriesIndex];
                        const bgColor = w.config.colors[seriesIndex];
                        const textColor = '#ffffff'; // Tetap putih untuk kontras
                        
                        return `<div style="background: ${bgColor}; font-size: 12px;font-weight: 600;color: ${textColor}; padding: 6px 12px; border-radius: 4px;">
                            <span>${label}: ${new Intl.NumberFormat('id-ID').format(value)} Aktivitas</span>
                        </div>`;
                    },
                    theme: colors.tooltipBackground === '#424242' ? 'dark' : 'light',
                    fillSeriesColor: true
                },
                legend: {
                    show: true,
                    position: "right",
                    offsetY: 40,
                    height: 230,
                    markers: {
                        width: 12,
                        height: 12,
                        radius: 2
                    },
                    itemMargin: {
                        horizontal: 0,
                        vertical: 8
                    },
                    labels: {
                        colors: colors.textColor
                    }
                }
            });

        // Render the chart
        chartModul.render();
        window.chartModul = chartModul; // Simpan referensi chart
    }

    // Module Activity Chart
    const moduleActivityData = <?php echo $module_activity_data ?? '[]'; ?>;

    // Check if data exists
    if (!moduleActivityData || moduleActivityData.length === 0) {
        // Create a div to replace the chart with a message
        const chartContainer = document.querySelector("#chart-aktivitas-modul");

        // Clear any existing content
        chartContainer.innerHTML = '';

        // Create and style the no data message container
        const noDataDiv = document.createElement('div');
        noDataDiv.style.display = 'flex';
        noDataDiv.style.flexDirection = 'column';
        noDataDiv.style.alignItems = 'center';
        noDataDiv.style.justifyContent = 'center';
        noDataDiv.style.height = '350px';
        noDataDiv.style.width = '100%';

        // Add an icon (using a simple SVG)
        const iconDiv = document.createElement('div');
        iconDiv.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="${colors.textColor}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        `;

        // Add text message
        const messageDiv = document.createElement('div');
        messageDiv.textContent = 'Tidak ada aktivitas';
        messageDiv.style.marginTop = '16px';
        messageDiv.style.fontSize = '16px';
        messageDiv.style.color = colors.textColor;
        messageDiv.style.fontWeight = '500';

        // Append elements to container
        noDataDiv.appendChild(iconDiv);
        noDataDiv.appendChild(messageDiv);
        chartContainer.appendChild(noDataDiv);
    } else {
        var moduleActivityOptions = {
            series: moduleActivityData,
            chart: {
                height: 350,
                type: 'line',
                toolbar: {
                    show: false
                },
                background: colors.backgroundColor
            },
            colors: colors.chartColors,
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            grid: {
                borderColor: colors.gridColor,
                padding: {
                    top: 10,
                    right: 10,
                    bottom: 10,
                    left: 10
                }
            },
            xaxis: {
                categories: Array.from({
                    length: 31
                }, (_, i) => {
                    return (i + 1).toString().padStart(2, '0');
                }),
                labels: {
                    rotate: -45,
                    rotateAlways: false,
                    style: {
                        colors: colors.textColor
                    }
                },
                title: {
                    text: 'Tanggal',
                    style: {
                        color: colors.textColor
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Jumlah Aktivitas',
                    style: {
                        color: colors.textColor
                    }
                },
                labels: {
                    style: {
                        colors: colors.textColor
                    }
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                labels: {
                    colors: colors.textColor
                }
            },
            tooltip: {
                shared: true,
                intersect: false,
                theme: colors.tooltipBackground === '#424242' ? 'dark' : 'light',
                style: {
                    fontSize: '12px',
                    fontFamily: undefined
                },
                x: {
                    show: true,
                    formatter: function(val) {
                        return 'Aktivitas Tanggal ' + val;
                    }
                },
                y: {
                    formatter: function(val) {
                        return new Intl.NumberFormat('id-ID').format(val) + ' Aktivitas';
                    }
                }
            }
        };

        var moduleActivityChart = new ApexCharts(document.querySelector("#chart-aktivitas-modul"), moduleActivityOptions);
        moduleActivityChart.render();
        window.moduleActivityChart = moduleActivityChart; // Simpan referensi chart
    }
    
    // Deteksi perubahan tema
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'data-bs-theme') {
                updateChartsTheme();
            }
        });
    });
    
    // Mulai observasi perubahan atribut data-bs-theme
    observer.observe(document.documentElement, { attributes: true });
});

	document.addEventListener('DOMContentLoaded', function() {
		// Get current year and month from PHP data
		const currentYear = <?php echo $current_year ?? 'null'; ?> || new Date().getFullYear();
		const currentMonth = <?php echo $current_month ?? 'null'; ?> || new Date().getMonth() + 1;

		// Populate year dropdown
		const yearSelect = document.getElementById('yearFilter');
		yearSelect.innerHTML = ''; // Clear any existing options

		// Add years (current year and 4 years back)
		const thisYear = new Date().getFullYear();
		for (let year = thisYear; year >= thisYear - 4; year--) {
			const option = document.createElement('option');
			option.value = year;
			option.textContent = year;
			// Select current year by default
			if (year.toString() === currentYear.toString()) {
				option.selected = true;
			}
			yearSelect.appendChild(option);
		}

		// Populate month dropdown
		const monthSelect = document.getElementById('monthFilter');
		monthSelect.innerHTML = ''; // Clear any existing options

		// Month names in Indonesian
		const monthNames = [
			'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
			'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
		];

		// Add all months
		for (let month = 0; month < 12; month++) {
			const option = document.createElement('option');
			option.value = month + 1; // Month values: 1-12
			option.textContent = monthNames[month];
			// Select current month by default
			if ((month + 1).toString() === currentMonth.toString()) {
				option.selected = true;
			}
			monthSelect.appendChild(option);
		}

		// Add event listeners to dropdowns for form submission
		yearSelect.addEventListener('change', submitFilterForm);
		monthSelect.addEventListener('change', submitFilterForm);

		// Display the initial data
		displayUserData(usersData.top_users);
	});

	function displayUserData(users) {
		const tableBody = document.querySelector("#userTable tbody");
		tableBody.innerHTML = ''; // Clear table

		if (!users || users.length === 0) {
			// Create a full-height centered no-data message
			tableBody.innerHTML = `
            <tr style="height: 180px;">
                <td colspan="3" class="text-center align-middle">
                    Tidak ada data untuk periode ini
                </td>
            </tr>`;
			return;
		}

		// Filter data yang lengkap saja
		const filteredUsers = users.filter(user => user.nmuser && user.nmusergroup);

		// Display valid data
		filteredUsers.forEach(user => {
			const profilePath = `files/profiles/${user.profilepic}`;
			const row = `<tr class="fs-6">
            <td class="py-3 align-middle">
                <div class="d-flex align-items-center">
                    <img src="${profilePath}" onerror="this.onerror=null; this.src='files/profiles/000.png';" class="rounded-circle me-2" width="40" height="40" alt="${user.nmuser}">
                    <span>${user.nmuser}</span>
                </div>
            </td>
            <td class="py-3 align-middle">${user.nmusergroup}</td>
            <td class="py-3 align-middle text-end">${user.click_count}</td>
        </tr>`;
			tableBody.innerHTML += row;
		});

		// If we have fewer than 3 rows, add empty rows to maintain height
		const rowCount = filteredUsers.length;
		if (rowCount < 3) {
			const emptyRowsNeeded = 3 - rowCount;
			for (let i = 0; i < emptyRowsNeeded; i++) {
				tableBody.innerHTML += `
                <tr style="height: 60px;">
                    <td colspan="3"></td>
                </tr>`;
			}
		}
	}

	// Submit form when filter changes
	function submitFilterForm() {
		document.getElementById('filterForm').submit();
	}

	// Card
	document.getElementById('totalUsers').textContent = usersData.total_users.length || 0;
	document.getElementById('activeUsers').innerHTML = `
	<span class="badge py-2 px-3 text-white fs-6 cursor-pointer"
		style="background-color: #6f42c1; cursor: pointer; transition: background-color 0.2s ease-in-out;"
		onmouseover="this.style.backgroundColor='#996bed'"
		onmouseout="this.style.backgroundColor='#6f42c1'"
		data-bs-toggle="modal" 
		data-bs-target="#dataModal"
		title="Klik untuk melihat pengguna aktif"
		data-title="Pengguna Aktif" 
		data-type="active_users">
		<strong>${usersData.active_users.length || ''}</strong>
		Pengguna Aktif        
	</span>`;
	document.getElementById('totalModules').textContent = usersData.total_modules.length || 0;
	document.getElementById('totalServices').textContent = usersData.total_services.length || 0;

	const tableConfigs = {
		total_users: {
			columns: [{
					title: "No",
					data: null,
					width: "50px",
					class: "text-center",
					render: function(data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},
				{
					title: "Profil",
					data: "profilepic",
					width: "80px",
					class: "text-center",
					render: function(data, type, row) {
						const profilePath = `files/profiles/${data || '000.png'}`;
						return `<img src="${profilePath}" class="avatar avatar-sm float-left" 
								onerror="this.onerror=null; this.src='files/profiles/000.png';" 
								alt="${row.nmuser || 'User'}" width="40" height="40">`;
					}
				},
				{
					title: "Nama User",
					data: "nmuser",
					render: function(data) {
						return data || "Unknown";
					}
				},
				{
					title: "Unit Kerja",
					data: "nmusergroup",
					render: function(data) {
						return data || "Unknown";
					}
				}
			]
		},
		active_users: {
			columns: [{
					title: "No",
					data: null,
					width: "50px",
					class: "text-center",
					render: function(data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},
				{
					title: "Nama User",
					data: "nmuser",
					render: function(data) {
						return data || "Unknown";
					}
				},
				{
					title: "Unit Kerja",
					data: "nmusergroup",
					render: function(data) {
						return data || "Unknown";
					}
				},
				{
					title: "Aktivitas Terakhir",
					data: "last_activity",
					render: function(data) {
						if (!data) return "Unknown";
						return new Date(data).toLocaleString();
					}
				}
			]
		},
		total_modules: {
			columns: [{
					title: "No",
					data: null,
					width: "50px",
					class: "text-center",
					render: function(data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},
				{
					title: "Nama Modul",
					data: "keterangan",
					render: function(data) {
						return data || "Unknown";
					}
				}
			]
		},
		total_services: {
			columns: [{
					title: "No",
					data: null,
					width: "50px",
					class: "text-center",
					render: function(data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				}, {
					title: "Nama Layanan",
					data: "namaproduk",
					render: function(data) {
						return data || "Unknown";
					}
				},
				{
					title: "Jumlah Modul Pada Layanan",
					class: "text-left",
					data: "module_count",
					render: function(data, type, row) {
						const icon = `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ms-2"><path d="M6 9l6 6l6 -6"/></svg>`;
						const value = data || "Unknown";

						const uniqueId = `dropdown-${row.namaproduk.replace(/\s+/g, '-').toLowerCase()}`;

						let formattedList = '';
						if (row.unique_keterangan) {
							const items = row.unique_keterangan.split(',').map(item => item.trim()).filter(Boolean);
							formattedList = `
											<ul class="list-unstyled mb-0">
												${items.map(item => `<li>• ${item}</li>`).join('')}
											</ul>
										`;
						}

						const dropdown = row.unique_keterangan ? `
						<div class="dropdown-content border rounded p-2 bg-light mt-2" id="${uniqueId}" style="display:none;">
							${formattedList}
						</div>` : '';

						return `<div class="dropdown-container"> 
									<div class="dropdown-trigger d-flex justify-content-between align-items-center" style="cursor: pointer;" onclick="toggleDropdown('${uniqueId}')">
										<span>${value}</span> 
										<span>${icon}</span> 
									</div> 
									${dropdown} 
								</div>`;
					}
				}
			]
		}
	};

	function toggleDropdown(id) {
		const dropdown = document.getElementById(id);
		if (dropdown) {
			const isShown = dropdown.style.display === 'block';
			dropdown.style.display = isShown ? 'none' : 'block';

			const icon = dropdown.previousElementSibling.querySelector('svg');
			if (icon) {
				icon.style.transform = isShown ? 'rotate(0deg)' : 'rotate(180deg)';
				icon.style.transition = 'transform 0.3s ease';
			}
		}
	}

	// DataTable instance
	let dataTable;
	let currentDataType = '';


	document.addEventListener('DOMContentLoaded', function() {
		const dataModal = document.getElementById('dataModal');

		// Only proceed if the dataModal exists
		if (dataModal) {
			// Set up modal event handlers
			dataModal.addEventListener('show.bs.modal', function(event) {
				const button = event.relatedTarget;
				const title = button.getAttribute('data-title');
				const dataType = button.getAttribute('data-type');

				// Update modal title
				document.getElementById('dataModalLabel').textContent = title;

				// Store the current data type
				currentDataType = dataType;

				// Destroy existing DataTable if it exists
				if (dataTable) {
					dataTable.destroy();
				}

				// Clear the table header and body
				document.querySelector('#dataTable thead').innerHTML = '<tr></tr>';
				document.querySelector('#dataTable tbody').innerHTML = '';

				// Create table headers
				const headerRow = document.querySelector('#dataTable thead tr');
				headerRow.innerHTML = '';
				tableConfigs[dataType].columns.forEach(column => {
					const th = document.createElement('th');
					th.textContent = column.title;
					if (column.width) {
						th.style.width = column.width;
					}
					headerRow.appendChild(th);
				});

				// Filter out rows where all data fields are empty
				const filteredData = usersData[dataType].filter(row => {
					let hasData = false;
					// Check each column that's not the "No" column (index 0)
					for (let i = 1; i < tableConfigs[dataType].columns.length; i++) {
						const column = tableConfigs[dataType].columns[i];
						if (row[column.data]) {
							hasData = true;
							break;
						}
					}
					return hasData;
				});

				// Initialize DataTable with the current data type
				dataTable = new DataTable('#dataTable', {
					data: filteredData,
					columns: tableConfigs[dataType].columns,
					pageLength: 10,
					language: {
						search: "Pencarian:",
						lengthMenu: "Tampilkan _MENU_ data per halaman",
						zeroRecords: "Tidak ada data yang ditemukan",
						info: "Menampilkan halaman _PAGE_ dari _PAGES_",
						infoEmpty: "Tidak ada data tersedia",
						infoFiltered: "(difilter dari _MAX_ total data)",
						paginate: {
							first: `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-chevron-bar-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M12.854 1.646a.5.5 0 0 1 0 .708L8.207 7l4.647 4.646a.5.5 0 0 1-.708.708l-5-5a.5.5 0 0 1 0-.708l5-5a.5.5 0 0 1 .708 0z"/>
                            <path fill-rule="evenodd" d="M4 2.5a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-1 0v-10a.5.5 0 0 1 .5-.5z"/>
                            </svg>`,
							previous: `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L6.707 7l4.647 4.646a.5.5 0 0 1-.708.708l-5-5a.5.5 0 0 1 0-.708l5-5a.5.5 0 0 1 .708 0z"/>
                            </svg>`,
							next: `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l5 5a.5.5 0 0 1 0 .708l-5 5a.5.5 0 0 1 .708 0z"/>
                            </svg>`,
							last: `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-chevron-bar-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M3.146 1.646a.5.5 0 0 1 .708 0l5 5a.5.5 0 0 1 0 .708l-5 5a.5.5 0 0 1-.708-.708L8.293 7 3.146 2.354a.5.5 0 0 1 0-.708z"/>
                        <path fill-rule="evenodd" d="M12 2.5a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-1 0v-10a.5.5 0 0 1 .5-.5z"/>
                        </svg>`
						}
					}
				});
			});
		}

		// Setup search functionality - only add if element exists
		const searchInput = document.getElementById('table-search');
		if (searchInput) {
			searchInput.addEventListener('keyup', function() {
				if (dataTable) {
					dataTable.search(this.value).draw();
				}
			});
		}

		// Export button handlers - check if each button exists before adding listeners
		const btnCopy = document.getElementById('btn-copy');
		if (btnCopy) {
			btnCopy.addEventListener('click', function() {
				if (dataTable) {
					navigator.clipboard.writeText(getTableData());
					alert('Data telah disalin ke clipboard');
				}
			});
		}

		const btnCSV = document.getElementById('btn-csv');
		if (btnCSV) {
			btnCSV.addEventListener('click', function() {
				if (dataTable) {
					downloadCSV(getTableData());
				}
			});
		}

		const btnPDF = document.getElementById('btn-pdf');
		if (btnPDF) {
			btnPDF.addEventListener('click', function() {
				if (dataTable) {
					downloadPDF(getTableData());
				}
			});
		}

		const btnPrint = document.getElementById('btn-print');
		if (btnPrint) {
			btnPrint.addEventListener('click', function() {
				if (dataTable) {
					printTable();
				}
			});
		}
	});

	// Helper function to get formatted table data (without profilepic column)
	function getTableData() {
		if (!dataTable) return '';

		const config = tableConfigs[currentDataType];
		const data = dataTable.data().toArray(); // Use the filtered data from DataTable

		// Create header row - exclude profilepic column
		let headers = [];
		config.columns.forEach(col => {
			// Skip profilepic column
			if (col.data !== 'profilepic') {
				headers.push(col.title);
			}
		});
		let csvContent = headers.join(',') + '\n';

		// Add data rows
		data.forEach((row, index) => {
			let rowValues = [];

			config.columns.forEach((col, colIndex) => {
				// Skip profilepic column
				if (col.data === 'profilepic') {
					return;
				}

				// For the No column, use the current index + 1
				if (colIndex === 0) {
					rowValues.push(index + 1);
					return;
				}

				let value = row[col.data] || 'Unknown';

				// Format date if needed
				if (col.data === 'last_activity' && row[col.data]) {
					value = new Date(row[col.data]).toLocaleString();
				}

				// Escape commas in values
				if (typeof value === 'string' && value.includes(',')) {
					rowValues.push(`"${value}"`);
				} else {
					rowValues.push(value);
				}
			});

			csvContent += rowValues.join(',') + '\n';
		});

		return csvContent;
	}

	// Helper function to download CSV
	function downloadCSV(csvContent) {
		const blob = new Blob([csvContent], {
			type: 'text/csv;charset=utf-8;'
		});
		const url = URL.createObjectURL(blob);
		const link = document.createElement('a');
		link.href = url;
		link.setAttribute('download', `${currentDataType}.csv`);
		document.body.appendChild(link);
		link.click();
		document.body.removeChild(link);
	}

	function downloadPDF() {
		// Create a new jsPDF instance - using the window.jspdf object
		const {
			jsPDF
		} = window.jspdf;
		const pdf = new jsPDF();

		// Set document properties
		pdf.setProperties({
			title: `${currentDataType} Report`,
			subject: 'DataTables Export',
			author: 'System',
			keywords: 'datatable, export, pdf'
		});

		// Get table data (already filtered without profilepic)
		const tableData = getTableData();

		// Parse CSV data to create an array for the PDF
		const rows = tableData.split('\n');
		const headers = rows[0].split(',');
		const data = [];

		// Process data rows
		for (let i = 1; i < rows.length; i++) {
			if (rows[i].trim()) {
				data.push(rows[i].split(','));
			}
		}

		// Add title to the PDF
		pdf.setFontSize(16);
		pdf.text(`${currentDataType} Report`, 14, 20);

		// Add creation date
		pdf.setFontSize(10);
		pdf.text(`Generated on: ${new Date().toLocaleString()}`, 14, 30);

		// Add table to the PDF
		pdf.autoTable({
			head: [headers],
			body: data,
			startY: 35,
			margin: {
				top: 15
			},
			theme: 'grid',
			styles: {
				fontSize: 8,
				cellPadding: 2
			},
			headStyles: {
				fillColor: [66, 139, 202],
				textColor: 255
			},
			columnStyles: {
				0: {
					cellWidth: 20
				} // Set smaller width (20) for the No column in PDF
			}
		});

		// Save the PDF
		pdf.save(`${currentDataType}.pdf`);
	}

	// Helper function to print table - exclude profilepic column
	function printTable() {
		const printWindow = window.open('', '_blank');
		const title = document.getElementById('dataModalLabel').textContent;
		const data = dataTable.data().toArray(); // Use the filtered data from DataTable
		const columns = tableConfigs[currentDataType].columns.filter(col => col.data !== 'profilepic');

		let printContent = `
							<!DOCTYPE html>
							<html>
							<head>
							<title>${title}</title>
							<style>
								table { border-collapse: collapse; width: 100%; }
								th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
								th { background-color: #f2f2f2; }
								tr:nth-child(even) { background-color: #f9f9f9; }
								.number-column { width: 30px; } /* Smaller width for No column */
							</style>
							</head>
							<body>
							<h1>${title}</h1>
							<table>
								<thead>
								<tr>
							`;

		// Add headers (excluding profilepic)
		columns.forEach((col, index) => {
			const className = index === 0 ? ' class="number-column"' : '';
			printContent += `<th${className}>${col.title}</th>`;
		});

		printContent += `
							</tr>
							</thead>
							<tbody>
						`;

		// Add data rows (excluding profilepic)
		data.forEach((row, rowIndex) => {
			printContent += '<tr>';
			columns.forEach((col, colIndex) => {
				let value;

				// For the No column, use the current index + 1
				if (colIndex === 0) {
					value = rowIndex + 1;
				} else {
					value = row[col.data] || 'Unknown';

					// Format date if needed
					if (col.data === 'last_activity' && row[col.data]) {
						value = new Date(row[col.data]).toLocaleString();
					}
				}

				const className = colIndex === 0 ? ' class="number-column"' : '';
				printContent += `<td${className}>${value}</td>`;
			});
			printContent += '</tr>';
		});

		printContent += `
							</tbody>
						</table>
						</body>
						</html>
						`;

		printWindow.document.open();
		printWindow.document.write(printContent);
		printWindow.document.close();

		// Wait for content to load before printing
		printWindow.onload = function() {
			printWindow.print();
		};
	}
</script>
