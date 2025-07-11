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
						<button id="refreshButton" class="btn btn-icon" title="Refresh data">
							<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-refresh" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
								<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
								<path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4"></path>
								<path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4"></path>
							</svg>
						</button>
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
			<!-- Rows for Statistics Card -->
			<div class="row g-3 mb-3">
				<!-- Total Number of Users Card -->
				<div class="col-sm-6 col-lg-4">
					<div class="card rounded-3 h-100">
						<div class="card-body d-flex align-items-center p-3">
							<div class="p-3 rounded me-3" style="background-color: #6f42c1;">
								<svg width=" 40" height="40" viewBox="0 0 71 71" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M13.625 50.0833H12.1667C11.3931 50.0833 10.6513 49.776 10.1043 49.2291C9.55729 48.6821 9.25 47.9402 9.25 47.1667C9.25 44.846 10.1719 42.6204 11.8128 40.9795C13.4538 39.3385 15.6794 38.4167 18 38.4167H20.9167M20.9167 29.5208C19.8457 29.3018 18.8377 28.8448 17.9672 28.1836C17.0967 27.5224 16.3861 26.6739 15.8878 25.701C15.3895 24.7281 15.1163 23.6555 15.0884 22.5628C15.0606 21.47 15.2788 20.385 15.7269 19.3879C16.175 18.3909 16.8416 17.5073 17.6772 16.8026C18.5129 16.098 19.4963 15.5902 20.5548 15.3169C21.6132 15.0436 22.7195 15.0118 23.7918 15.2238C24.8642 15.4358 25.8752 15.8862 26.75 16.5417M57.375 50.0833H58.8333C59.6069 50.0833 60.3488 49.776 60.8957 49.2291C61.4427 48.6821 61.75 47.9402 61.75 47.1667C61.75 44.846 60.8281 42.6204 59.1872 40.9795C57.5462 39.3385 55.3206 38.4167 53 38.4167H50.0833M50.0833 29.5208C51.1543 29.3018 52.1623 28.8448 53.0328 28.1836C53.9033 27.5224 54.6139 26.6739 55.1122 25.701C55.6106 24.7281 55.8837 23.6555 55.9116 22.5628C55.9394 21.47 55.7212 20.385 55.2731 19.3879C54.825 18.3909 54.1584 17.5073 53.3228 16.8026C52.4871 16.098 51.5037 15.5902 50.4452 15.3169C49.3868 15.0436 48.2805 15.0118 47.2082 15.2238C46.1358 15.4358 45.1248 15.8862 44.25 16.5417M45.7083 55.9167H25.2917C24.5181 55.9167 23.7763 55.6094 23.2293 55.0624C22.6823 54.5154 22.375 53.7735 22.375 53C22.375 50.6794 23.2969 48.4538 24.9378 46.8128C26.5788 45.1719 28.8044 44.25 31.125 44.25H39.875C42.1956 44.25 44.4212 45.1719 46.0622 46.8128C47.7031 48.4538 48.625 50.6794 48.625 53C48.625 53.7735 48.3177 54.5154 47.7707 55.0624C47.2238 55.6094 46.4819 55.9167 45.7083 55.9167ZM42.7917 28.2083C42.7917 30.1422 42.0234 31.9969 40.656 33.3643C39.2885 34.7318 37.4339 35.5 35.5 35.5C33.5661 35.5 31.7115 34.7318 30.344 33.3643C28.9766 31.9969 28.2083 30.1422 28.2083 28.2083C28.2083 26.2745 28.9766 24.4198 30.344 23.0523C31.7115 21.6849 33.5661 20.9167 35.5 20.9167C37.4339 20.9167 39.2885 21.6849 40.656 23.0523C42.0234 24.4198 42.7917 26.2745 42.7917 28.2083Z" stroke="white" stroke-width="2" stroke-linecap="round" />
								</svg>
							</div>
							<div class="flex-grow-1">
								<div class="d-flex justify-content-between align-items-center">
									<div class="fw-bold fs-5">Total Pengguna</div>
									<a href="javascript:void(0)" class="text-body" data-bs-toggle="modal" data-bs-target="#dataModal" data-title="Total Pengguna" data-type="total_users">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-external-link">
											<path stroke="none" d="M0 0h24v24H0z" fill="none" />
											<path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6" />
											<path d="M11 13l9 -9" />
											<path d="M15 4h5v5" />
										</svg>
									</a>
								</div>
								<div class="d-flex justify-content-between align-items-center mt-auto pt-2">
									<div id="activeUsers">
										<button type="button">
											<span>
												<strong class="ms-1">0</strong>
												Pengguna Aktif
											</span>
										</button>
									</div>
									<div id="totalUsers" class="fw-bold" style="font-size: 35px;"></div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Total Number of Reports Cards -->
				<div class="col-sm-6 col-lg-4">
					<div class="card rounded-3 h-100">
						<div class="card-body d-flex align-items-center p-3">
							<div class="p-3 rounded me-3" style="background-color: #FFE11E;">
								<svg width="40" height="40" viewBox="0 0 54 54" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M45.5625 21.6C47.2654 22.5806 48.6462 24.0355 49.5364 25.7873C50.4266 27.5391 50.7878 29.5121 50.576 31.4657C50.3642 33.4192 49.5885 35.2689 48.3435 36.7892C47.0985 38.3095 45.438 39.4347 43.5645 40.0275C42.9024 43.0323 41.2339 45.7209 38.8354 47.6481C36.4368 49.5753 33.4519 50.6256 30.375 50.625H25.3125C24.865 50.625 24.4358 50.4472 24.1193 50.1308C23.8028 49.8143 23.625 49.3851 23.625 48.9375C23.625 48.49 23.8028 48.0607 24.1193 47.7443C24.4358 47.4278 24.865 47.25 25.3125 47.25H30.375C32.4696 47.2506 34.5129 46.6019 36.2234 45.393C37.934 44.1841 39.2277 42.4747 39.9263 40.5H38.8125C38.365 40.5 37.9358 40.3222 37.6193 40.0058C37.3028 39.6893 37.125 39.2601 37.125 38.8125V21.9375C37.125 21.49 37.3028 21.0607 37.6193 20.7443C37.9358 20.4278 38.365 20.25 38.8125 20.25H40.5C41.0468 20.25 41.5834 20.2939 42.1099 20.3783C41.73 16.635 39.9742 13.1661 37.1826 10.6435C34.391 8.12091 30.7625 6.72432 27 6.72432C23.2376 6.72432 19.609 8.12091 16.8175 10.6435C14.0259 13.1661 12.2701 16.635 11.8902 20.3783C12.4226 20.2929 12.9609 20.25 13.5 20.25H15.1875C15.6351 20.25 16.0643 20.4278 16.3808 20.7443C16.6973 21.0607 16.875 21.49 16.875 21.9375V38.8125C16.875 39.2601 16.6973 39.6893 16.3808 40.0058C16.0643 40.3222 15.6351 40.5 15.1875 40.5H13.5C11.2691 40.5025 9.09981 39.7681 7.32919 38.4109C5.55857 37.0537 4.28577 35.1497 3.70855 32.9947C3.13132 30.8397 3.282 28.5545 4.13715 26.4939C4.99231 24.4334 6.50407 22.713 8.43755 21.6C8.43755 16.6769 10.3932 11.9555 13.8744 8.47434C17.3555 4.99319 22.077 3.03751 27 3.03751C31.9231 3.03751 36.6446 4.99319 40.1257 8.47434C43.6069 11.9555 45.5625 16.6769 45.5625 21.6ZM13.5 23.625C11.7098 23.625 9.99295 24.3362 8.72708 25.602C7.46121 26.8679 6.75005 28.5848 6.75005 30.375C6.75005 32.1652 7.46121 33.8821 8.72708 35.148C9.99295 36.4138 11.7098 37.125 13.5 37.125V23.625ZM47.25 30.375C47.25 28.5848 46.5389 26.8679 45.273 25.602C44.0071 24.3362 42.2903 23.625 40.5 23.625V37.125C42.2903 37.125 44.0071 36.4138 45.273 35.148C46.5389 33.8821 47.25 32.1652 47.25 30.375Z" fill="white" />
								</svg>
							</div>
							<div class="flex-grow-1">
								<div class="d-flex justify-content-between align-items-center">
									<div class="fw-bold fs-5">Jumlah Layanan</div>
									<a href="javascript:void(0)" class="text-body" data-bs-toggle="modal" data-bs-target="#dataModal" data-title="Jumlah Layanan" data-type="total_services">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icon-tabler-external-link">
											<path stroke="none" d="M0 0h24v24H0z" fill="none" />
											<path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6" />
											<path d="M11 13l9 -9" />
											<path d="M15 4h5v5" />
										</svg>
									</a>
								</div>
								<div id="totalServices" class="m-0 fw-bold d-flex justify-content-end" style="font-size: 35px;"></div>
							</div>
						</div>
					</div>
				</div>

				<!-- Total Number of Modules Card -->
				<div class="col-sm-6 col-lg-4">
					<div class="card rounded-3 h-100">
						<div class="card-body d-flex align-items-center p-3">
							<div class="p-3 rounded me-3" style="background-color: #50c878;">
								<svg width="40" height="40" viewBox="0 0 67 66" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M33.2499 8.25L54.6834 20.625V21.3593L51.1112 23.2897V22.6875L33.2499 12.375L15.3887 22.6875V43.3125L33.2499 53.625L51.1112 43.3125V23.8012L54.6834 21.846V45.375L33.2499 57.75L11.8164 45.375V20.625L33.2499 8.25Z" fill="white" />
									<path d="M21.8775 18.2696L39.8377 29.04L33.2501 32.703L15.69 22.9474C15.2119 22.6815 14.6478 22.6165 14.1217 22.7666C13.5957 22.9166 13.1508 23.2695 12.885 23.7476C12.6191 24.2257 12.5541 24.7898 12.7041 25.3159C12.8542 25.8419 13.2071 26.2868 13.6852 26.5526L31.1876 36.2752V55.6875H35.3126V36.2752L52.815 26.5526L53.0625 26.3876L54.6795 25.5544V20.8189L50.8968 22.902L50.8143 22.9433L50.3771 23.1908L49.5892 23.6239L43.9998 26.73L23.9977 14.7304L21.8775 18.2696Z" fill="white" />
								</svg>
							</div>
							<div class="flex-grow-1">
								<div class="d-flex justify-content-between align-items-center">
									<div class="fw-bold fs-5">Jumlah Modul</div>
									<a href="javascript:void(0)" class="text-body" data-bs-toggle="modal" data-bs-target="#dataModal" data-title="Jumlah Modul" data-type="total_modules">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-external-link">
											<path stroke="none" d="M0 0h24v24H0z" fill="none" />
											<path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6" />
											<path d="M11 13l9 -9" />
											<path d="M15 4h5v5" />
										</svg>
									</a>
								</div>
								<div id="totalModules" class="m-0 fw-bold d-flex justify-content-end" style="font-size: 35px;"></div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Average Website Access Chart -->
			<div class="row mb-2">
				<div class="col-xl-8 col-lg-7 mb-3">
					<div class="card h-100">
						<div class="card-body d-flex flex-column">
							<div class="row">
								<div class="col">
									<h5 class="card-title text-center">
										Pola Penggunaan Modul <span class="fw-bold" style="color: #8979FF;">MyTask</span> Per Jam <br> Pada Bulan
										<span class="fw-bold" style="color: #8979FF;"><?php echo $month_name; ?></span>
										tahun
										<span class="fw-bold" style="color: #8979FF;"><?php echo $year; ?></span>
									</h5>
								</div>
							</div>
							<!-- Website Access Chart -->
							<div id="accessChart" class="flex-grow-1 mt-2"></div>
						</div>
					</div>
				</div>

				<!-- Table of Users with the Most Task Activity -->
				<div class="col-xl-4 col-lg-5 mb-3">

					<!-- Filter Section -->
					<div class="p-3" style="background-color: #6f42c1; border-radius: 5px;">
						<form id="filterForm" method="GET">
							<input type="hidden" name="q" value="MyTask">
							<div class="d-flex justify-content-around align-items-center">
								<div class="d-flex align-items-center">
									<span class="text-white me-2">Tahun</span>
									<div class="dropdown me-2">
										<select name="year" class="form-select" id="yearFilter">
											<!-- Data In Script -->
										</select>
									</div>
								</div>
								<div class="d-flex align-items-center">
									<span class="text-white me-2">Bulan</span>
									<div class="dropdown">
										<select name="month" class="form-select" id="monthFilter">
											<!-- Data In Script -->
										</select>
									</div>
								</div>
							</div>
						</form>
					</div>

					<!-- Gap -->
					<div class="my-2"></div>

					<!-- Table Section -->
					<div class="card flex-grow-1">
						<div class="card-body">
							<h5 class="card-title text-center mb-3">3 Pengguna Dengan Aktivitas <span class="fw-bold" style="color:#8979FF;">MyTask</span> Terbanyak</h5>
							<div class="table-responsive" style="height: 260px;">
								<table class="table" id="userTable">
									<thead>
										<tr>
											<th>Nama</th>
											<th>Unit Kerja</th>
											<th class="text-end">Jumlah Aktivitas</th>
										</tr>
									</thead>
									<tbody class="position-relative">
										<!-- Data In Script -->
									</tbody>
								</table>
							</div>
						</div>
					</div>

				</div>
			</div>

			<!-- Module Access Distribution and Module User Activities -->
			<div class="row mb-4">
				<!-- Module Access Distribution (Pie Chart) -->
				<div class="col-xl-4 col-lg-5 mb-3">
					<div class="card h-100 rounded-3">
						<div class="card-body">
							<h5 class="card-title">Distribusi Penggunaan Modul <span class="fw-bold" style="color: #8979FF;">MyTask</span></h5>
							<div id="chart-akses-modul"></div>
						</div>
					</div>
				</div>

				<!-- Module User Activity (Line Chart) -->
				<div class="col-xl-8 col-lg-7 mb-3">
					<div class="card h-100 rounded-3">
						<div class="card-body">
							<h5 class="card-title">Aktivitas Harian Per Modul <span class="fw-bold" style="color: #8979FF;">MyTask</span></h5>
							<div id="chart-aktivitas-modul"></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal Card -->
		<div class="modal fade modal-blur" id="dataModal" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-xl">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="dataModalLabel">Data</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<div class="row mb-3">
							<div class="col">
								<div class="btn-group d-flex gap-3" role="group">
									<button type="button" class="btn btn-sm btn-secondary rounded shadow-none" id="btn-copy">Copy</button>
									<button type="button" class="btn btn-sm btn-secondary rounded shadow-none" id="btn-csv">CSV</button>
									<button type="button" class="btn btn-sm btn-secondary rounded shadow-none" id="btn-pdf">PDF</button>
									<button type="button" class="btn btn-sm btn-secondary rounded shadow-none" id="btn-print">Print</button>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table id="dataTable" class="table table-striped table-bordered" style="width:100%">
								<thead>
									<tr>
										<!-- Data In Script -->
									</tr>
								</thead>
								<tbody>
									<!-- Data In Script -->
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	const usersData = {
		/* Card */
		total_users: <?php echo $total_users ?? '[]'; ?>,
		active_users: <?php echo $active_users ?? '[]'; ?>,
		total_modules: <?php echo $total_modules ?? '[]'; ?>,
		total_services: <?php echo $total_services ?? '[]'; ?>,
		/* Table */
		top_users: <?php echo $top_users_by_month ?? '[]'; ?>
	};

	// console.log(usersData.total_users);
	// console.log(usersData.active_users);
	// console.log(usersData.total_modules)
	// console.log(usersData.total_services)

	// console.log(usersData.top_users);
</script>

<!-- Event Listener -->
<?php $this->load->view('anggaran/script'); ?>
