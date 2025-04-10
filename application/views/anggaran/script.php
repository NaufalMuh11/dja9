<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('lastUpdate').innerHTML = `<?php echo get_last_update(); ?>`;
    });

    document.addEventListener("DOMContentLoaded", function() {
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

        const userData = [{
                name: "Budiman",
                avatar: "https://cdn-icons-png.flaticon.com/512/3135/3135715.png",
                unit: "Admin DJA",
                aktivitas: 201
            },
            {
                name: "Budiman",
                avatar: "https://cdn-icons-png.flaticon.com/512/3135/3135715.png",
                unit: "Admin DJA",
                aktivitas: 201
            },
            {
                name: "Budiman",
                avatar: "https://cdn-icons-png.flaticon.com/512/3135/3135715.png",
                unit: "Admin DJA",
                aktivitas: 201
            }
        ];

        const tableBody = document.querySelector("#userTable tbody");
        userData.forEach(user => {
            const row = `<tr>
                            <td class="py-3">
                            <div class="d-flex align-items-center">
                                <img src="${user.avatar}" class="rounded-circle me-2" width="40" height="40" alt="${user.name}">
                                <span>${user.name}</span>
                            </div>
                            </td>
                            <td class="py-3">${user.unit}</td>
                            <td class="py-3 text-end">${user.aktivitas}</td>
                        </tr>
                        `;
            tableBody.innerHTML += row;
        });
    });
</script>