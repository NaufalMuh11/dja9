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
                     <a class="dropdown-toggle text-muted text-decoration-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> </a>
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
                           <div class="d-flex gap-3">
                              <select class="form-select w-50">
                                 <option value="127">I.27. Honorarium Satpam, Pengemudi, Petugas Kebersihan, Dan Pramubakti</option>
                                 <option value="128">I.28. Satuan Biaya Uang Harian dan Uang Representasi Perjalanan Dinas Dalam Negeri</option>
                                 <option value="130">I.30. Satuan Biaya Penginapan Perjalanan Dinas Dalam Negeri</option>
                                 <option value="134">I.34. Satuan Biaya Makanan Penambah Daya Tahan Tubuh</option>
                                 <!-- <option value="135">Satuan Biaya Sewa Kendaraan</option> -->
                                 <!-- <option value="136">Satuan Biaya Pengadaan Kendaraan Dinas</option> -->
                                 <option value="137">I.37. Satuan Biaya Pengadaan Pakaian Dinas</option>
                                 <option value="139">I.39. Satuan Biaya Konsumsi Kegiatan Pendidikan dan Pelatihan (Diklat)</option>
                                 <!-- <option value="209">Satuan Biaya Pengadaan Bahan Makanan</option> -->
                                 <option value="210">II.10. Satuan Biaya Konsumsi Tahanan/Deteni/ABK nonjustisia</option>
                                 <option value="211">II.11. Satuan Biaya Kebutuhan Dasar Perkantoran di dalam Negeri</option>
                                 <option value="212">II.12. Satuan Biaya Penggantian Inventaris Lama dan/atau Pembelian Inventaris Untuk Pegawai Baru</option>
                                 <option value="214">II.14. Satuan Biaya Pemeliharaan Gedung/Bangunan dalam Negeri</option>
                                 <option value="215">II.15. Satuan Biaya Sewa Gedung Pertemuan</option>
                                 <option value="216">II.16. Satuan Biaya Transportasi dari dan/atau ke Terminal Bus/Stasiun/Bandara/Pelabuhan dalam Rangka Perjalanan Dinas Dalam Negeri</option>
                              </select>
                              <select class="form-select w-50" id="sbm-subtitle-select"></select>
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
                           Komposisi SBM dalam Nilai Maksimum, Nilai Minimum, Nilai Rata-Rata, dan Interkuartil
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
                        <h3 class="mb-1 text-center">Rincian</h3>

                        <div id="bar-chart-dropdowns" class="d-flex gap-3 align-items-center mt-2">
                           <div class="dropdown" id="subtitle-dropdown">
                              <a class="dropdown-toggle text-dark text-decoration-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 <span id="selected-subtitle"></span>
                              </a>
                              <div class="dropdown-menu" id="subtitle-dropdown-menu"></div>
                           </div>

                           <div class="dropdown" id="sub-subtitle-dropdown" style="display: none;">
                              <a class="dropdown-toggle text-dark text-decoration-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 <span id="selected-sub-subtitle">Pilih Sub-subjudul</span>
                              </a>
                              <div class="dropdown-menu" id="sub-subtitle-dropdown-menu"></div>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col">
                           <div id="chart-bar-detail"></div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Perbandingan -->
            <div class="col-12">
               <div class="card">
                  <div class="card-body">
                     PERBANDINGAN SBM
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- Refresh Status Indicator -->
<div id="refresh-indicator" class="position-fixed bottom-0 end-0 m-3 p-2 bg-light rounded shadow-sm" style="display: none; z-index: 1050;">
   <div class="d-flex align-items-center">
      <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
         <span class="visually-hidden">Loading...</span>
      </div>
      <small>Memperbarui data...</small>
   </div>
</div>

<!-- Scripts -->
<?php $this->load->view('perbandingansbm/sbm_script'); ?>
