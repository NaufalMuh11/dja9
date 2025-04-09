<div class="page-wrapper">
	<div class="container-fluid">
		<div class="page-header d-print-none" style="margin-top: 10px;">
			<div class="row align-items-center">

				<div class="col">
					<div class="page-pretitle">
						Anggaran
					</div>
					<h2 class="page-title">
						<span class="text-cyan">Monitoring&nbsp;</span> MyTask
					</h2>
				</div>

				<!-- last update  -->
				<div class="col-auto ms-auto d-print-none">
					<div class="btn-list">
						<div class="d-none d-sm-block ps-2">
							<div id="lastUpdate"></div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>

	<div class="page-body" style="margin: 10px 0">
		<div class="container-fluid">
			<!-- Baris untuk Kartu Statistik -->
			<div class="col-sm-12 mb-4">
				<div class="row g-3 w-100">
					<!-- Total Pengguna Card -->
					<div class="col-sm-6 col-lg-3">
						<div class="card" style="border-radius: 10px; overflow: hidden;">
							<div class="card-body d-flex align-items-center p-3">
								<div class="bg-purple p-3 rounded me-3" style="background-color: #5D3FD3;">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
										<circle cx="9" cy="7" r="4"></circle>
										<path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
										<path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
									</svg>
								</div>
								<div>
									<div class="text-muted small">Total Pengguna</div>
									<div class="h3 m-0">1,231</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Pengguna Aktif Card -->
					<div class="col-sm-6 col-lg-3">
						<div class="card" style="border-radius: 10px; overflow: hidden;">
							<div class="card-body d-flex align-items-center p-3">
								<div class="bg-danger p-3 rounded me-3">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
										<circle cx="12" cy="7" r="4"></circle>
									</svg>
								</div>
								<div>
									<div class="text-muted small">Pengguna Aktif</div>
									<div class="h3 m-0">23</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Jumlah Laporan Card -->
					<div class="col-sm-6 col-lg-3">
						<div class="card" style="border-radius: 10px; overflow: hidden;">
							<div class="card-body d-flex align-items-center p-3">
								<div class="bg-warning p-3 rounded me-3">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
									</svg>
								</div>
								<div>
									<div class="text-muted small">Jumlah Laporan</div>
									<div class="h3 m-0">4</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Jumlah Modul Card -->
					<div class="col-sm-6 col-lg-3">
						<div class="card" style="border-radius: 10px; overflow: hidden;">
							<div class="card-body d-flex align-items-center p-3">
								<div class="bg-success p-3 rounded me-3">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
									</svg>
								</div>
								<div>
									<div class="text-muted small">Jumlah Modul</div>
									<div class="h3 m-0">4</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Baris untuk Grafik Akses dan Tabel Pengguna Aktif -->
			<div class="row mb-4">
				<!-- Grafik Rata-rata Akses Website -->
				<div class="col-xl-8 col-lg-7 mb-3">
					<div class="card">
						<div class="card-body">
							<div class="row mb-3">
								<div class="col">
									<h5 class="card-title">Jumlah pengguna yang mengkases website</h5>
								</div>
								<div class="col-auto">
									<!-- Filter Tahun dan Bulan -->
								</div>
							</div>
							<!-- Grafik Akses Website -->
						</div>
					</div>
				</div>

				<!-- Tabel Pengguna dengan Aktivitas Task Terbanyak -->
				<div class="col-xl-4 col-lg-5 mb-3">
					<div class="card">
						<div class="card-body">
							<div class="row mb-3">
								<div class="col">
									<h5 class="card-title">3 Pengguna Dengan Aktivitas Terbanyak</h5>
								</div>
								<div class="col-auto">
									<!-- Filter -->
								</div>
							</div>
							<!-- Tabel Pengguna -->
						</div>
					</div>
				</div>
			</div>

			<!-- Baris untuk Distribusi Akses Modul dan Aktivitas Pengguna Modul -->
			<div class="row mb-4">
				<!-- Distribusi Akses Modul (Pie Chart) -->
				<div class="col-xl-4 col-lg-5 mb-3">
					<div class="card">
						<div class="card-body">
							<h5 class="card-title">Distribusi Akses Modul</h5>
							<!-- Pie Chart Distribusi Akses Modul -->
						</div>
					</div>
				</div>

				<!-- Aktivitas Pengguna Modul (Line Chart) -->
				<div class="col-xl-8 col-lg-7 mb-3">
					<div class="card">
						<div class="card-body">
							<h5 class="card-title">Aktivitas Pengguna Modul</h5>
							<!-- Line Chart Aktivitas Pengguna Modul -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Event Listener -->
<script>
	document.addEventListener("DOMContentLoaded", function() {
		document.getElementById('lastUpdate').innerHTML = `<?php echo get_last_update(); ?>`; // get last update
	});
</script>
