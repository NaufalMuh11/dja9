<div class="content-wrapper">
  <div class="container-fluid">
    <div class="page-header d-print-none" style="margin-top: 10px;">
      <div class="row align-items-center">

        <div class="col">
          <div class="page-pretitle">
            Referensi
          </div>
          <h2 class="page-title">
            <span class="text-cyan">SBM&nbsp;</span>
            <input type="hidden" id="selected_thang">
            <div class="dropdown" id="selectThang" disabled>
              <a class="dropdown-toggle text-muted text-decoration-none" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                T.A. <?php echo $tahun_aktif; ?>
              </a>
              <div class="dropdown-menu">
                <a class="dropdown-item <?php echo $tahun_aktif == '2025' ? 'active' : ''; ?>" 
                   href="<?php echo base_url('referensi?q=RefSBM&tahun=2025'); ?>">
                   T.A. 2025
                </a>
                <a class="dropdown-item <?php echo $tahun_aktif == '2026' ? 'active' : ''; ?>" 
                   href="<?php echo base_url('referensi?q=RefSBM&tahun=2026'); ?>">
                   T.A. 2026
                </a>
            </div>
          </h2>
        </div>

      </div>
    </div>
  </div>

  <div class="page-body" style="margin: 10px 0">
    <div class="container-fluid">
      <div class="card">
        <div class="card-body" style="padding: 16px;">

          <div class="mb-3 d-flex justify-content-between align-items-center">
            <div>
              <a href="#" id="backButton" class="btn btn-icon" title="Kembali">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-cyan"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
              </a>
            </div>
            <div class="position-relative" style="max-width: 300px;">
              <input type="text" id="searchInput" class="form-control" placeholder="Cari data...">
              <i class="fa fa-times position-absolute" id="clearSearch" style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; display: none;"></i>
            </div>
          </div>
          <table class="table table-bordered">
            
            <thead>
              <tr>
                <th class="text-center" width="70%"> Uraian </th>
                <th class="text-center" width="10%"> Satuan </th>
                <th class="text-center" width="10%"> Biaya </th>
              </tr>
            </thead>

            <tbody>
              <?php foreach ($tabel as $row) {
                  if ($row['biaya'] == 0) { ?>
                  <tr id="id_<?= $row['kdsbu'] ?>" class="Sub" style="margin: 0px">
                    <td colspan="3" class="text-left"><b> <?= $row['nmsbu'] ?> </b></td>
                  </tr>
                <?php } else { ?>
                  <tr id="id_<?= $row['kdsbu'] ?>" class="All" style="margin: 0px">
                    <td> <?= $row['nmsbu'] ?></td>
                    <td><?= $row['satuan'] ?></td>
                    <td class="text-end"> <?= number_format($row['biaya'], 0, ',', '.') ?></td>
                  </tr>
              <?php }
                } ?>

            </tbody>
          </table>

          <script>
            document.addEventListener('DOMContentLoaded', function() {
              const searchInput = document.getElementById('searchInput');
              const clearSearch = document.getElementById('clearSearch');
              const tableRows = document.querySelectorAll('tbody tr');

              function filterTable(searchTerm) {
                searchTerm = searchTerm.toLowerCase();
                tableRows.forEach(row => {
                  const text = row.textContent.toLowerCase();
                  row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
                clearSearch.style.display = searchTerm ? 'block' : 'none';
              }

              searchInput.addEventListener('input', (e) => filterTable(e.target.value));
              
              clearSearch.addEventListener('click', () => {
                searchInput.value = '';
                filterTable('');
              });

              // Handle back button click
              document.getElementById('backButton').addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = '<?php echo base_url("referensi?q=RefSBM&tahun=" . $tahun_aktif); ?>';
              });
            });

            document.addEventListener('DOMContentLoaded', function() {
    // Update dropdown display
    const dropdownToggle = document.querySelector('#selectThang .dropdown-toggle');
    const currentThang = '<?php echo $tahun_aktif; ?>';
    dropdownToggle.textContent = ` T.A. ${currentThang}`;

    // Handle dropdown selection
    const dropdownItems = document.querySelectorAll('#selectThang .dropdown-item');
    dropdownItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const year = this.textContent.trim().replace('T.A. ', '');
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('tahun', year);
            window.location.href = currentUrl.toString();
        });

        // Set active state
        if (item.textContent.includes(currentThang)) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
    });
});
          </script>
        </div>
      </div>
    </div>
  </div>
</div>