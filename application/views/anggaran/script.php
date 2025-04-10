<script>
	document.addEventListener("DOMContentLoaded", function() {
		// Last update
		document.getElementById('lastUpdate').innerHTML = `<?php echo get_last_update(); ?>`;

		// Access Chart
		var options = {
			series: [{
				name: 'Pengunjung',
				data: [5, 10, 8, 12, 11, 14, 18, 16, 20, 22, 28, 25, 20, 18, 15, 12, 10, 8, 6, 5, 4, 3, 2, 1]
			}, {
				name: 'Rata-rata',
				data: [1, 2, 1, 3, 2, 4, 5, 6, 8, 10, 12, 15, 18, 14, 12, 10, 8, 6, 5, 4, 3, 2, 1, 1]
			}],
			chart: {
				height: 240,
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
				width: 2
			},
			grid: {
				borderColor: '#e0e0e0',
				row: {
					colors: ['transparent'],
					opacity: 0.5
				}
			},
			xaxis: {
				categories: ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12',
					'13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'
				],
				title: {
					text: 'Jam',
					offsetY: 10,
					style: {
						fontSize: '14px',
						fontWeight: 500
					}
				}
			},
			yaxis: {
				title: {
					text: 'Jumlah',
					style: {
						fontSize: '14px',
						fontWeight: 500
					}
				},
				min: 0,
				max: 30,
				tickAmount: 6
			},
			annotations: {
				yaxis: [{
					y: 25,
					borderColor: '#4caf50',
					label: {
						borderColor: '#4caf50',
						style: {
							color: '#fff',
							background: '#4caf50'
						},
						text: 'Rata-rata pertahun'
					}
				}]
			},
			legend: {
				position: 'top',
				horizontalAlign: 'right',
				floating: true,
				offsetY: -25,
				offsetX: -5
			}
		};

		var chart = new ApexCharts(document.querySelector("#accessChart"), options);
		chart.render();

		var chartModul = new ApexCharts(
			document.getElementById("chart-akses-modul"), {
				chart: {
					type: "donut",
					height: 240,
					sparkline: {
						enabled: true
					}
				},
				series: [35, 25, 22, 18],
				labels: ['Anggaran', 'Kepegawaian', 'Keuangan', 'Perencanaan'],
				colors: ['#5D3FD3', '#dc3545', '#ffc107', '#198754'],
				plotOptions: {
					pie: {
						donut: {
							size: '70%',
							labels: {
								show: true,
								name: {
									show: true
								},
								value: {
									show: true,
									formatter: function(val) {
										return val + '%';
									}
								}
							}
						}
					}
				},
				tooltip: {
					y: {
						formatter: function(val) {
							return val + '%';
						}
					}
				},
				legend: {
					show: true,
					position: "right",
					offsetY: 16,
					markers: {
						width: 10,
						height: 10,
						radius: 100
					},
					formatter: function(seriesName, opts) {
						return seriesName;
					}
				}
			});
		chartModul.render();

		// Module Activity Chart
		var moduleActivityOptions = {
			series: [{
				name: 'SIPKD',
				data: [10, 15, 12, 18, 20, 22, 15, 17, 21, 23, 19, 14, 16, 18, 20, 15, 13, 17, 19, 21, 16, 14, 18, 20, 22, 19, 17, 15, 18, 20]
			}, {
				name: 'SIMDA',
				data: [5, 7, 6, 8, 9, 11, 8, 7, 10, 12, 9, 7, 8, 9, 11, 8, 6, 9, 10, 12, 8, 7, 9, 11, 13, 10, 8, 7, 9, 11]
			}, {
				name: 'E-Planning',
				data: [3, 4, 5, 6, 7, 8, 6, 5, 7, 8, 6, 4, 5, 6, 7, 5, 4, 6, 7, 8, 6, 5, 7, 8, 9, 7, 6, 5, 7, 8]
			}, {
				name: 'SIMPEG',
				data: [2, 3, 4, 5, 6, 7, 5, 4, 6, 7, 5, 3, 4, 5, 6, 4, 3, 5, 6, 7, 5, 4, 6, 7, 8, 6, 5, 4, 6, 7]
			}],
			chart: {
				height: 350,
				type: 'line',
				toolbar: {
					show: false
				}
			},
			colors: ['#7a36b1', '#dc3545', '#ffc107', '#198754'],
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
				categories: Array.from({
					length: 30
				}, (_, i) => {
					const d = new Date();
					d.setDate(d.getDate() - (29 - i));
					return d.toLocaleDateString('id-ID', {
						day: '2-digit',
						month: 'short'
					});
				}),
				labels: {
					rotate: -45,
					rotateAlways: false
				}
			},
			yaxis: {
				title: {
					text: 'Jumlah Aktivitas'
				}
			},
			legend: {
				position: 'top',
				horizontalAlign: 'right',
				onItemClick: {
					toggleDataSeries: true
				},
				onItemHover: {
					highlightDataSeries: true
				}
			},
			tooltip: {
				shared: true,
				intersect: false
			},
			markers: {
				size: false,
				hover: {
					size: 7
				}
			}
		};

		// Add one more series (instead of two)
		moduleActivityOptions.series.push({
			name: 'E-Budgeting',
			data: [4, 6, 5, 7, 8, 9, 7, 6, 8, 9, 7, 5, 6, 7, 8, 6, 5, 7, 8, 9, 7, 6, 8, 9, 10, 8, 7, 6, 8, 9]
		});

		// Update colors array for 5 series
		moduleActivityOptions.colors = ['#7a36b1', '#dc3545', '#ffc107', '#198754', '#ff9800'];

		var moduleActivityChart = new ApexCharts(document.querySelector("#chart-aktivitas-modul"), moduleActivityOptions);
		moduleActivityChart.render();
	});

	document.addEventListener('DOMContentLoaded', function() {
		// Get current year and month from PHP data
		const currentYear = <?php echo $current_year ?? 'null'; ?> || new Date().getFullYear();
		const currentMonth = <?php echo $current_month ?? 'null'; ?> || new Date().getMonth() + 1;

		// Populate year dropdown
		const yearSelect = document.getElementById('yearFilter');

		// Add "All Years" option
		const allYearsOption = document.createElement('option');
		allYearsOption.value = 'all';
		allYearsOption.textContent = 'Semua Tahun';
		if (currentYear === 'all') {
			allYearsOption.selected = true;
		}
		yearSelect.appendChild(allYearsOption);

		// Add years (current year and 4 years back)
		const thisYear = new Date().getFullYear();
		for (let year = thisYear; year >= thisYear - 4; year--) {
			const option = document.createElement('option');
			option.value = year;
			option.textContent = year;
			// Select current year by default
			if (year.toString() === currentYear.toString() && currentYear !== 'all') {
				option.selected = true;
			}
			yearSelect.appendChild(option);
		}

		// Populate month dropdown
		const monthSelect = document.getElementById('monthFilter');

		// Add "All Months" option
		const allMonthsOption = document.createElement('option');
		allMonthsOption.value = 'all';
		allMonthsOption.textContent = 'Semua Bulan';
		if (currentMonth === 'all') {
			allMonthsOption.selected = true;
		}
		monthSelect.appendChild(allMonthsOption);

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
			if ((month + 1).toString() === currentMonth.toString() && currentMonth !== 'all') {
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

		users.forEach(user => {
			const profilePath = `files/profiles/${user.profilepic}`;
			const row = `<tr>
            <td class="py-3">
                <div class="d-flex align-items-center">
                    <img src="${profilePath}" onerror="this.onerror=null; this.src='files/profiles/000.png';" class="rounded-circle me-2" width="40" height="40" alt="${user.nmuser}">
                    <span>${user.nmuser}</span>
                </div>
            </td>
            <td class="py-3">${user.iduser}</td>
            <td class="py-3 text-end">${user.click_count}</td>
        </tr>`;
			tableBody.innerHTML += row;
		});
	}

	// Submit form when filter changes
	function submitFilterForm() {
		document.getElementById('filterForm').submit();
	}
</script>
