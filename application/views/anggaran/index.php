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
         <div class="row mb-4">
            <!-- Total Pengguna -->
            <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-stats">
               <!-- Isi kartu Total Pengguna -->
            </div>
            </div>
            
            <!-- Pengguna Aktif -->
            <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-stats">
               <!-- Isi kartu Pengguna Aktif -->
            </div>
            </div>
            
            <!-- Jumlah Layanan -->
            <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-stats">
               <!-- Isi kartu Jumlah Layanan -->
            </div>
            </div>
            
            <!-- Jumlah Modul -->
            <div class="col-xl-3 col-md-6 mb-3">
            <div class="card card-stats">
               <!-- Isi kartu Jumlah Modul -->
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