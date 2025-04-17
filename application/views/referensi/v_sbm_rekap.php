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
                        <div class="dropdown" id="selectThang">
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
                    <div class="mb-3 d-flex justify-content-end">
                        <div class="position-relative" style="max-width: 300px;">
                            <input type="text" id="searchInput" class="form-control" placeholder="Cari data...">
                            <i class="fa fa-times position-absolute" id="clearSearch" style="right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; display: none;"></i>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="20px" class="text-center">Kode</th>
                                    <th>Uraian</th>
                                    <th width="50px" class="text-center">Ket</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($tabel)) : ?>
                                    <?php foreach ($tabel as $row) : ?>
                                        <tr onclick="window.location='<?php echo base_url('referensi?q=DtlSBM&kode=' . $row['kode']); ?>'" style="cursor: pointer;">
                                            <td><?php echo $row['kode']; ?></td>
                                            <td><?php echo $row['uraian']; ?></td>
                                            <td><i class=""></i></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada data</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<>
const searchInput = document.getElementById('searchInput');
const clearSearch = document.getElementById('clearSearch');

searchInput.addEventListener('keyup', function() {
    let searchText = this.value.toLowerCase();
    let tableRows = document.querySelectorAll('table tbody tr');
    
    clearSearch.style.display = searchText.length > 0 ? 'block' : 'none';
    
    tableRows.forEach(row => {
        if (!row.querySelector('td[colspan]')) {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchText) ? '' : 'none';
        }
    });
});

clearSearch.addEventListener('click', function() {
    searchInput.value = '';
    searchInput.dispatchEvent(new Event('keyup'));
    this.style.display = 'none';
});
</
scpt>
