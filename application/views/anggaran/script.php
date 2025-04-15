<script>
	document.addEventListener("DOMContentLoaded", function() {
		// Last update
		document.getElementById('lastUpdate').innerHTML = `<?php echo get_last_update(); ?>`;

		// Access Chart
		var hourlyData = <?php echo $hourly_users; ?>;
		var options = {
			series: [{
				name: 'Tahun Ini',
				data: hourlyData.current_year
			}, {
				name: 'Tahun Lalu',
				data: hourlyData.previous_year
			}],
			chart: {
				height: 280,
				type: 'line',
				toolbar: {
					show: false
				},
				zoom: {
					enabled: false
				}
			},
			colors: ['#7a36b1', '#ffc107'],
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: 'smooth',
				width: 3
			},
			grid: {
				show: true,
				borderColor: '#e0e0e0',
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
					show: true
				},
				axisTicks: {
					show: true
				},
				title: {
					text: 'Jam',
					offsetY: 0,  // Adjusted offsetY
					offsetX: 0,
					style: {
						fontSize: '12px'
					}
				},
				labels: {
					offsetY: 5  // Added space between labels and title
				}
			},
			yaxis: {
				min: 0,
				max: Math.max(...hourlyData.current_year, ...hourlyData.previous_year) + 5, // Dynamic max + padding
				tickAmount: 6,
				labels: {
					formatter: function(val) {
						return val.toFixed(0);
					}
				},
				title: {
					text: 'Jumlah',
					style: {
						fontSize: '12px'
					}
				}
			},
			legend: {
				position: 'top',
				horizontalAlign: 'left',
				offsetX: 0,
				offsetY: 0
			}
		};

		var chart = new ApexCharts(document.querySelector("#accessChart"), options);
		chart.render();

		// Module Access Chart
		const moduleDistribution = <?php echo $module_distribution ?? '[]'; ?>;
const totalModules = moduleDistribution.reduce((sum, item) => sum + parseInt(item.access_count), 0);

var chartModul = new ApexCharts(
    document.getElementById("chart-akses-modul"), {
        chart: {
            type: "donut",
            height: 700,
            background: 'transparent',
            offsetY: 30,
            offsetX: -10,
            events: {
                // Add this event handler to reset colors when clicking elsewhere
                dataPointSelection: function(event, chartContext, config) {
                    // Force the center label colors back to default after a short delay
                    setTimeout(() => {
                        const totalLabel = document.querySelector('.apexcharts-datalabels-group .apexcharts-datalabel-label');
                        const totalValue = document.querySelector('.apexcharts-datalabels-group .apexcharts-datalabel-value');
                        
                        if (totalLabel) {
                            totalLabel.style.fill = '#333333'; // Default color for the label
                        }
                        
                        if (totalValue) {
                            totalValue.style.fill = '#333333'; // Default color for the value
                        }
                    }, 50);
                }
            }
        },
        series: moduleDistribution.map(item => parseInt(item.access_count)),
        labels: moduleDistribution.map(item => item.keterangan),
        colors: ['#5D3FD3', '#dc3545', '#ffc107', '#198754', '#ff9800'],
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
                            color: '#333333',
                            offsetY: -10,
                            formatter: function() {
                                return totalModules.toLocaleString('id-ID') + ' Modul';
                            }
                        },
                        value: {
                            show: true,
                            fontSize: '24px',
                            fontWeight: 700,
                            color: '#333333',
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
            }
        },
        tooltip: {
            custom: function({ series, seriesIndex, dataPointIndex, w }) {
                const label = w.config.labels[seriesIndex];
                const value = series[seriesIndex];
                return `<div style="background: ${w.config.colors[seriesIndex]}; color: white; padding: 6px 12px; border-radius: 4px;">
                    <span>${label}: ${value} Aktivitas</span>
                </div>`;
            },
            style: {
                fontSize: '14px',
                fontFamily: undefined
            },
            theme: 'dark',
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
            }
        }
    });
chartModul.render();

		// Module Activity Chart
		const moduleActivityData = <?php echo $module_activity_data ?? '[]'; ?>;

		var moduleActivityOptions = {
			series: moduleActivityData,
			chart: {
				height: 350,
				type: 'line',
				toolbar: {
					show: false
				}
			},
			colors: ['#5D3FD3', '#dc3545', '#ffc107', '#198754', '#ff9800'],
			dataLabels: {
				enabled: false
			},
			stroke: {
				curve: 'smooth',
				width: 2
			},
			grid: {
				borderColor: '#e0e0e0',
				padding: {
					top: 10,
					right: 10,
					bottom: 10,
					left: 10
				}
			},
			xaxis: {
				categories: Array.from({length: 31}, (_, i) => {
					return (i + 1).toString().padStart(2, '0');
				}),
				labels: {
					rotate: -45,
					rotateAlways: false
				},
				title: {
					text: 'Tanggal'
				}
			},
			yaxis: {
				title: {
					text: 'Jumlah Aktivitas'
				}
			},
			legend: {
				position: 'top',
				horizontalAlign: 'right'
			},
			tooltip: {
				shared: true,
				intersect: false,
				y: {
					formatter: function(val) {
						return val + ' Aktivitas';
					}
				}
			}
		};

		var moduleActivityChart = new ApexCharts(document.querySelector("#chart-aktivitas-modul"), moduleActivityOptions);
		moduleActivityChart.render();
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

	// Function to display user activity data
	function displayUserData(users) {
		const tableBody = document.querySelector("#userTable tbody");
		tableBody.innerHTML = ''; // Clear table

		if (!users || users.length === 0) {
			tableBody.innerHTML = '<tr><td colspan="3" class="text-center">Tidak ada data untuk periode ini</td></tr>';
			return;
		}

		// Display the data
		users.forEach(user => {
			const profilePath = `files/profiles/${user.profilepic}`;
			const row = `<tr class="fs-6">
						<td class="py-3 align-middle">
							<div class="d-flex align-items-center">
							<img src="${profilePath}" onerror="this.onerror=null; this.src='files/profiles/000.png';" class="rounded-circle me-2" width="40" height="40" alt="${user.nmuser}">
							<span>${user.nmuser ?? 'Unknown'}</span>
							</div>
						</td>
						<td class="py-3 align-middle">${user.nmusergroup ?? 'Unknown'}</td>
						<td class="py-3 align-middle text-end">${user.click_count}</td>
						</tr>
						`;
			tableBody.innerHTML += row;
		});
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
		data-title="Pengguna Aktif" 
		data-type="active_users">
		<strong>${usersData.active_users.length || ''}</strong>
    Pengguna Aktif        
	</span>`;
	document.getElementById('totalModules').textContent = usersData.total_modules.length || 0;
	document.getElementById('totalServices').textContent = usersData.total_services.length || 0;

	// Table configurations for each data type
	const tableConfigs = {
		total_users: {
			columns: [{
					title: "Nama User",
					data: "nmuser"
				},
				{
					title: "ID User Group",
					data: "idusergroup"
				},
				{
					title: "Nama User Group",
					data: "nmusergroup"
				},
				{
					title: "Total",
					data: "total_users"
				}
			]
		},
		active_users: {
			columns: [{
					title: "ID User",
					data: "iduser"
				},
				{
					title: "Nama User",
					data: "nmuser"
				},
				{
					title: "ID User Group",
					data: "idusergroup"
				},
				{
					title: "Nama User Group",
					data: "nmusergroup"
				},
				{
					title: "Aktivitas Terakhir",
					data: "last_activity",
					render: function(data) {
						if (!data) return '';
						return new Date(data).toLocaleString();
					}
				}
			]
		},
		total_modules: {
			columns: [{
					title: "Button ID",
					data: "buttonid"
				},
				{
					title: "Keterangan",
					data: "keterangan"
				},
				{
					title: "Nama Produk",
					data: "namaproduk"
				},
				{
					title: "Jumlah Penggunaan",
					data: "usage_count"
				}
			]
		},
		total_services: {
			columns: [{
					title: "ID Produk",
					data: "idproduk"
				},
				{
					title: "Nama Produk",
					data: "namaproduk"
				},
				{
					title: "Jumlah Modul",
					data: "module_count"
				}
			]
		}
	};

	// DataTable instance
	let dataTable;
	let currentDataType = '';

	// Initialize DataTables when the modal is shown
	document.addEventListener('DOMContentLoaded', function() {
		const dataModal = document.getElementById('dataModal');

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
				document.querySelector('#dataTable thead tr').innerHTML = '';
			}

			// Create table headers
			const headerRow = document.querySelector('#dataTable thead tr');
			headerRow.innerHTML = '';
			tableConfigs[dataType].columns.forEach(column => {
				const th = document.createElement('th');
				th.textContent = column.title;
				headerRow.appendChild(th);
			});

			// Initialize DataTable with the current data type
			dataTable = new DataTable('#dataTable', {
				data: usersData[dataType],
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
									<path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l5 5a.5.5 0 0 1 0 .708l-5 5a.5.5 0 1 1-.708-.708L9.293 7 4.646 2.354a.5.5 0 0 1 0-.708z"/>
								</svg>`,
						last: `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-chevron-bar-right" viewBox="0 0 16 16">
							<path fill-rule="evenodd" d="M3.146 1.646a.5.5 0 0 1 .708 0l5 5a.5.5 0 0 1 0 .708l-5 5a.5.5 0 0 1-.708-.708L8.293 7 3.146 2.354a.5.5 0 0 1 0-.708z"/>
							<path fill-rule="evenodd" d="M12 2.5a.5.5 0 0 1 .5.5v10a.5.5 0 0 1-1 0v-10a.5.5 0 0 1 .5-.5z"/>
							</svg>`
					}
				}
			});
		});

		// Setup search functionality
		document.getElementById('table-search').addEventListener('keyup', function() {
			if (dataTable) {
				dataTable.search(this.value).draw();
			}
		});

		// Export button handlers
		document.getElementById('btn-copy').addEventListener('click', function() {
			if (dataTable) {
				navigator.clipboard.writeText(getTableData());
				alert('Data telah disalin ke clipboard');
			}
		});

		document.getElementById('btn-csv').addEventListener('click', function() {
			if (dataTable) {
				downloadCSV(getTableData());
			}
		});

		document.getElementById('btn-pdf').addEventListener('click', function() {
			if (dataTable) {
				downloadPDF(getTableData());
			}
		});

		document.getElementById('btn-print').addEventListener('click', function() {
			if (dataTable) {
				printTable();
			}
		});
	});

	// Helper function to get formatted table data
	function getTableData() {
		if (!dataTable) return '';

		const config = tableConfigs[currentDataType];
		const data = usersData[currentDataType];

		// Create header row
		let csvContent = config.columns.map(col => col.title).join(',') + '\n';

		// Add data rows
		data.forEach(row => {
			const rowValues = config.columns.map(col => {
				let value = row[col.data] || '';

				// Format date if needed
				if (col.data === 'last_activity' && value) {
					value = new Date(value).toLocaleString();
				}

				// Escape commas in values
				if (typeof value === 'string' && value.includes(',')) {
					return `"${value}"`;
				}

				return value;
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

		// Get table data
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
			}
		});

		// Save the PDF
		pdf.save(`${currentDataType}.pdf`);
	}

	// Helper function to print table
	function printTable() {
		const printWindow = window.open('', '_blank');
		const title = document.getElementById('dataModalLabel').textContent;

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
        </style>
      </head>
      <body>
        <h1>${title}</h1>
        <table>
          <thead>
            <tr>
    `;

		// Add headers
		tableConfigs[currentDataType].columns.forEach(col => {
			printContent += `<th>${col.title}</th>`;
		});

		printContent += `
            </tr>
          </thead>
          <tbody>
    `;

		// Add data rows
		usersData[currentDataType].forEach(row => {
			printContent += '<tr>';
			tableConfigs[currentDataType].columns.forEach(col => {
				let value = row[col.data] || '';

				// Format date if needed
				if (col.data === 'last_activity' && value) {
					value = new Date(value).toLocaleString();
				}

				printContent += `<td>${value}</td>`;
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
