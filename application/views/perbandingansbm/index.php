<div class="page-wrapper">
   <div class="container-fluid">
      <div class="page-header d-print-none" style="margin-top: 10px;">
         <div class="row align-items-center">

            <div class="col">
               <div class="page-pretitle">
                  Anggaran
               </div>
               <h2 class="page-title">
                  <span class="text-cyan">Grafik&nbsp;</span>SBM&nbsp;
                  <input type="hidden" id="selected_thang">
                  <div class="dropdown" id="selectThang">
                     <a class="dropdown-toggle text-muted text-decoration-none" href="" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> </a>
                     <div class="dropdown-menu dropdown-menu-end">
                     </div>
                  </div>
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
   <div class="page-body">
      <div class="container-fluid">
         <div class="row row-deck row-cards">
            <div class="col-12">
               <div class="card">
                  <div class="card-body">
                     <form id="filterForm" class="row g-3 align-items-end">
                        <div class="mb-3">
                           <div class="form-label">Pilih SBM</div>
                           <div class="row g-2">
                              <div class="col-lg-6">
                                 <select class="form-select">
                                    <option value="127">Honorarium Satpam, Pengemudi, Petugas Kebersihan, Dan Pramubakti</option>
                                    <option value="128">Satuan Biaya Uang Harian dan Uang Representasi Perjalanan Dinas Dalam Negeri</option>
                                    <option value="130">Satuan Biaya Penginapan Perjalanan Dinas Dalam Negeri</option>
                                    <option value="134">Satuan Biaya Makanan Penambah Daya Tahan Tubuh</option>
                                    <option value="135">Satuan Biaya Sewa Kendaraan</option>
                                    <option value="137">Satuan Biaya Pengadaan Pakaian Dinas</option>
                                    <option value="139">Satuan Biaya Konsumsi Kegiatan Pendidikan dan Pelatihan (Diklat)</option>
                                    <!-- <option value="209">Satuan Biaya Pengadaan Bahan Makanan</option> -->
                                    <option value="210">Satuan Biaya Konsumsi Tahanan/Deteni/ABK nonjustisia</option>
                                    <option value="211">Satuan Biaya Kebutuhan Dasar Perkantoran di dalam Negeri</option>
                                    <option value="212">Satuan Biaya Penggantian Inventaris Lama dan/atau Pembelian Inventaris Untuk Pegawai Baru</option>
                                    <option value="214">Satuan Biaya Pemeliharaan Gedung/Bangunan dalam Negeri</option>
                                    <option value="215">Satuan Biaya Sewa Gedung Pertemuan</option>
                                    <option value="216">Satuan Biaya Transportasi dari dan/atau ke Terminal Bus/Stasiun/Bandara/Pelabuhan dalam Rangka Perjalanan Dinas Dalam Negeri</option>
                                 </select>
                              </div>
                              <div class="col-lg-6">
                                 <select class="form-select d-none" id="sbm-subtitle-select"></select>
                              </div>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>

            <div class="col-12">
               <div class="card">
                  <div class="card-body">
                     <div class="d-flex flex-column align-items-center">
                        <h3 class="mb-1 text-center">
                           Komposisi SBM dalam Nilai Maksimum, Nilai Minimum, Nilai Median, dan Interkuartil
                        </h3>
                        <div class="d-flex w-100 justify-content-end mt-2">
                           <small id="boxplot-note" class="text-muted">* Biaya dalam ribuan rupiah</small>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col">
                           <div id="chart-boxplot"></div>
                        </div>
                     </div>
                  </div>

                  <div class="card-body">
                     <div class="d-flex flex-column align-items-center">
                        <div class="position-relative w-full">
                           <h3 class="mb-1 text-center">Perbandingan Standar Biaya Masukan per Provinsi</h3>
                           <div class="dropdown position-absolute top-0 end-0" id="sort-order-dropdown">
                              <a class="dropdown-toggle text-reset text-decoration-none fs-5" href="" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 Urutkan:<span id="selected-sort-order" class="ms-1">Nilai Normal</span>
                              </a>
                              <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-1">
                                 <li><a class="dropdown-item fs-5 py-1" href="" data-value="normal">Nilai Normal</a></li>
                                 <li><a class="dropdown-item fs-5 py-1 d-flex align-items-center" href="" data-value="asc">
                                       Nilai Terendah
                                    </a></li>
                                 <li><a class="dropdown-item fs-5 py-1" href="" data-value="desc">Nilai Tertinggi</a></li>
                              </ul>
                           </div>
                        </div>

                        <div id="bar-chart-dropdowns" class="d-flex gap-3 align-items-center mt-2">
                           <div class="dropdown" id="subtitle-dropdown" style="display: none;">
                              <a class="dropdown-toggle text-reset text-decoration-none" href="" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 <span id="selected-subtitle"></span>
                              </a>
                              <div class="dropdown-menu" id="subtitle-dropdown-menu"></div>
                           </div>

                           <!-- Only show Sub-subtitle dropdown -->
                           <div class="dropdown" id="sub-subtitle-dropdown">
                              <a class="dropdown-toggle text-reset text-decoration-none" href="" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 <span id="selected-sub-subtitle">Pilih Kategori</span>
                              </a>
                              <div class="dropdown-menu" id="sub-subtitle-dropdown-menu"></div>
                           </div>
                        </div>
                     </div>
                     <div class="row mt-2">
                        <div class="col">
                           <!-- Chart container -->
                           <div id="province-chart-container"></div>

                           <!-- Table container -->
                           <div class="table-responsive mt-4">
                              <h3 class="mb-2 text-center">
                                 Rincian Selisih Standar Biaya Masukan per Provinsi
                              </h3>
                              <table class="table table-bordered table-striped" id="province-table">
                                 <thead>
                                    <tr></tr>
                                 </thead>
                                 <tbody id="province-table-body"></tbody>
                              </table>

                              <!-- Pagination container -->
                              <div class="d-flex justify-content-between align-items-center mt-3">
                                 <div class="text-muted small">
                                    Menampilkan <span id="showing-entries"></span> dari <span id="total-entries"></span> provinsi
                                 </div>
                                 <div class="pagination-container">
                                    <ul class="pagination" id="province-pagination"></ul>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <!-- Refresh Status Indicator -->
   <div id="refresh-indicator" class="position-fixed bottom-0 end-0 m-3 p-2 rounded shadow-sm" style="display: none; z-index: 1050;">
      <div class="d-flex align-items-center">
         <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
            <span class="visually-hidden">Loading...</span>
         </div>
         <small>Memperbarui data...</small>
      </div>
   </div>

   <!-- Scripts -->
   <?php $this->load->view('perbandingansbm/sbm_script'); ?>
   <style>
      .pagination-container {
         margin-top: 1rem;
      }

      .pagination .page-link {
         color: #206bc4;
         cursor: pointer;
      }

      .pagination .page-item.active .page-link {
         background-color: #206bc4;
         border-color: #206bc4;
         color: rgb(249, 249, 249);
      }

      .pagination .page-item.disabled .page-link {
         color: #6c757d;
         pointer-events: none;
      }
   </style>
