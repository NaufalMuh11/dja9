<link href="<?= base_url('assets/plugins/pivot/pivot.css'); ?>" rel="stylesheet" />
<link href="<?= base_url('assets/plugins/pivot/subtotal.css'); ?>" rel="stylesheet" />
<link href="<?= base_url('assets/dist/css/styleTable.css'); ?>" type="text/css" rel="stylesheet" />
<link href="<?= base_url('assets/dist/css/dataTables.css'); ?>" type="text/css" rel="stylesheet" />

<style>
    /* Base styles */
    #drop-area {
        border: 1px dashed var(--tblr-border-color);
        padding: 15px;
        text-align: center;
        background: var(--tblr-bg-surface);
    }

    #drop-area .file-info {
        margin-bottom: 10px;
    }

    #drop-area .process-info {
        color: var(--tblr-muted);
    }

    .container {
        overflow-y: auto;
        padding: 0 !important;
        margin: 0 !important;
        max-width: none !important;
        width: 100% !important;
    }

    /* DataTables Custom Styles */
    .dt-button {
        background: var(--tblr-cyan) !important;
        color: #fff !important;
        border: none !important;
        padding: 8px 16px !important;
        border-radius: 4px !important;
        margin-right: 8px !important;
        transition: opacity 0.3s ease !important;
    }

    .dt-button:hover {
        opacity: 0.85 !important;
        background: var(--tblr-cyan) !important;
        color: #fff !important;
    }

    .dt-layout-cell {
        padding-top: 0;
    }
</style>

<!-- Main HTML Structure -->
<div class="page-wrapper">
    <div class="container-fluid">
        <div class="page-header d-print-none" style="margin-top: 10px;">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Analitika
                    </div>
                    <h2 class="page-title">
                        <span class="text-cyan">Quick Report </span> &nbsp;<span class="text-blue text-bold"> ADK SAKTI</span>
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body" style="margin-top: 10px; margin-bottom: 0px">
        <div class="container-fluid">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <!-- Drop Area Card -->
                    <div class="card">
                        <div class="card-body">
                            <div id="drop-area">
                                <div class="file-info mb-0">
                                    Seret dan lepas file <b>ADK SAKTI</b> di sini, atau klik untuk memilih file.
                                </div>
                                <div class="small text-orange process-info">Format File: d99_99999_99_999999_9.s99 (.s24 s.d. s30)</div>
                            </div>
                            <input type="file" id="file-input" accept=".s24,.s25,.s26,.s27,.s28,.s29,.s30" style="display: none;">
                        </div>
                    </div>
                </div>

                <div class="col-12" id="results-tabs" style="display: none;">
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                                <li class="nav-item">
                                    <a href="#tabs-quick-report" class="nav-link active" data-bs-toggle="tab">Quick Report</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#tabs-data-item" class="nav-link" data-bs-toggle="tab">Data Item</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active show" id="tabs-quick-report">
                                    <div class="container">
                                        <div id="pivotTableOutput"></div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tabs-data-item">
                                    <div id="dataTableOutput"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="<?= base_url('./assets/dist/js/excellentexport.js'); ?>"></script>
<script src="<?= base_url('./assets/dist/js/dataTables.js'); ?>"></script>
<script src="<?= base_url('./assets/dist/js/axios.js') ?>"></script>
<script src="<?= base_url('./assets/dist/js/dataTables.buttons.js') ?>"></script>
<script src="<?= base_url('./assets/dist/js/buttons.dataTables.js') ?>"></script>
<script src="<?= base_url('./assets/dist/js/jszip.min.js') ?>"></script>
<script src="<?= base_url('./assets/dist/js/pdfmake.min.js') ?>"></script>
<script src="<?= base_url('./assets/dist/js/vfs_fonts.js') ?>"></script>
<script src="<?= base_url('./assets/dist/js/buttons.html5.min.js') ?>"></script>
<script src="<?= base_url('./assets/dist/js/buttons.print.min.js') ?>"></script>
<script src="<?= base_url('./assets/plugins/pivot/pivot.js'); ?>"></script>
<script src="<?= base_url('./assets/plugins/pivot/subtotal.js'); ?>"></script>
<script src="<?= base_url('./assets/plugins/pivot/export_renderers.js'); ?>"></script>
<script src="<?= base_url('./assets/plugins/jQueryUI/jquery-ui.1.11.4.min.js'); ?>"></script>


<!-- Main Application Script -->
<script>
    $(function() {
        let dropArea = $('#drop-area');
        let fileInput = $('#file-input');

        // Data Structures
        const tableStructures = {
            d_item: [{
                    thang: "CHAR(4) DEFAULT NULL"
                },
                {
                    kdjendok: "CHAR(2) DEFAULT NULL"
                },
                {
                    kdsatker: "CHAR(6) DEFAULT NULL"
                },
                {
                    kddept: "CHAR(3) DEFAULT NULL"
                },
                {
                    kdunit: "CHAR(2) DEFAULT NULL"
                },
                {
                    kdprogram: "CHAR(2) DEFAULT NULL"
                },
                {
                    kdgiat: "CHAR(4) DEFAULT NULL"
                },
                {
                    kdoutput: "CHAR(3) DEFAULT NULL"
                },
                {
                    kdlokasi: "CHAR(2) DEFAULT NULL"
                },
                {
                    kdkabkota: "CHAR(2) DEFAULT NULL"
                },
                {
                    kddekon: "CHAR(1) DEFAULT NULL"
                },
                {
                    kdsoutput: "CHAR(3) DEFAULT NULL"
                },
                {
                    kdkmpnen: "CHAR(3) DEFAULT NULL"
                },
                {
                    kdskmpnen: "CHAR(2) DEFAULT NULL"
                },
                {
                    kdakun: "CHAR(6) DEFAULT NULL"
                },
                {
                    kdkppn: "CHAR(3) DEFAULT NULL"
                },
                {
                    kdbeban: "CHAR(1) DEFAULT NULL"
                },
                {
                    kdjnsban: "CHAR(1) DEFAULT NULL"
                },
                {
                    kdctarik: "CHAR(1) DEFAULT NULL"
                },
                {
                    register: "CHAR(8) DEFAULT NULL"
                },
                {
                    carahitung: "CHAR(1) DEFAULT NULL"
                },
                {
                    header1: "CHAR(2) DEFAULT NULL"
                },
                {
                    header2: "CHAR(2) DEFAULT NULL"
                },
                {
                    kdheader: "CHAR(1) DEFAULT NULL"
                },
                {
                    noitem: "DECIMAL(4,0) DEFAULT NULL"
                },
                {
                    nmitem: "CHAR(255) DEFAULT NULL"
                },
                {
                    vol1: "DECIMAL(8,0) DEFAULT NULL"
                },
                {
                    sat1: "CHAR(5) DEFAULT NULL"
                },
                {
                    vol2: "DECIMAL(8,0) DEFAULT NULL"
                },
                {
                    sat2: "CHAR(5) DEFAULT NULL"
                },
                {
                    vol3: "DECIMAL(8,0) DEFAULT NULL"
                },
                {
                    sat3: "CHAR(5) DEFAULT NULL"
                },
                {
                    vol4: "DECIMAL(8,0) DEFAULT NULL"
                },
                {
                    sat4: "CHAR(5) DEFAULT NULL"
                },
                {
                    volkeg: "DECIMAL(10,2) DEFAULT NULL"
                },
                {
                    satkeg: "CHAR(5) DEFAULT NULL"
                },
                {
                    hargasat: "DECIMAL(15,2) DEFAULT NULL"
                },
                {
                    jumlah: "DECIMAL(15,0) DEFAULT NULL"
                },
                {
                    jumlah2: "DECIMAL(15,0) DEFAULT NULL"
                },
                {
                    paguphln: "DECIMAL(15,0) DEFAULT NULL"
                },
                {
                    pagurmp: "DECIMAL(15,0) DEFAULT NULL"
                },
                {
                    pagurkp: "DECIMAL(15,0) DEFAULT NULL"
                },
                {
                    kdblokir: "CHAR(1) DEFAULT NULL"
                },
                {
                    blokirphln: "DECIMAL(15,0) DEFAULT NULL"
                },
                {
                    blokirrmp: "DECIMAL(15,0) DEFAULT NULL"
                },
                {
                    blokirrkp: "DECIMAL(15,0) DEFAULT NULL"
                },
                {
                    rphblokir: "DECIMAL(15,0) DEFAULT NULL"
                },
                {
                    kdcopy: "CHAR(2) DEFAULT NULL"
                },
                {
                    kdabt: "CHAR(1) DEFAULT NULL"
                },
                {
                    kdsbu: "CHAR(8) DEFAULT NULL"
                },
                {
                    volsbk: "DECIMAL(7,0) DEFAULT NULL"
                },
                {
                    volrkakl: "DECIMAL(7,0) DEFAULT NULL"
                },
                {
                    blnkontrak: "CHAR(2) DEFAULT NULL"
                },
                {
                    nokontrak: "CHAR(30) DEFAULT NULL"
                },
                {
                    tgkontrak: "DATE DEFAULT NULL"
                },
                {
                    nilkontrak: "DECIMAL(15,0) DEFAULT NULL"
                },
                {
                    januari: "DECIMAL(15,0) DEFAULT NULL"
                },
                {
                    pebruari: "DECIMAL(15,0) DEFAULT NULL"
                },
                {
                    maret: "DECIMAL(15,0) DEFAULT NULL"
                },
                {
                    april: "DECIMAL(15,0) DEFAULT NULL"
                },
                {
                    mei: "DECIMAL(15,0) DEFAULT NULL"
                },
                {
                    juni: "DECIMAL(15,0) DEFAULT NULL"
                },
                {
                    juli: "DECIMAL(15,0) DEFAULT NULL"
                },
                {
                    agustus: "DECIMAL(15,0) DEFAULT NULL"
                },
                {
                    september: "DECIMAL(15,0) DEFAULT NULL"
                },
                {
                    oktober: "DECIMAL(15,0) DEFAULT NULL"
                },
                {
                    nopember: "DECIMAL(15,0) DEFAULT NULL"
                },
                {
                    desember: "DECIMAL(15,0) DEFAULT NULL"
                },
                {
                    jmltunda: "DECIMAL(15,0) DEFAULT NULL"
                },
                {
                    kdluncuran: "CHAR(1) DEFAULT NULL"
                },
                {
                    jmlabt: "DECIMAL(18,0) DEFAULT NULL"
                },
                {
                    norev: "CHAR(2) DEFAULT NULL"
                },
                {
                    kdubah: "CHAR(1) DEFAULT NULL"
                },
                {
                    kurs: "DECIMAL(5,3) DEFAULT NULL"
                },
                {
                    indexkpjm: "DECIMAL(8,4) DEFAULT NULL"
                },
                {
                    kdib: "CHAR(2) DEFAULT NULL"
                },
                {
                    kdstatus: "CHAR(1) DEFAULT NULL"
                },
            ],
            d_akun: [{
                    thang: "CHAR(4) NOT NULL"
                },
                {
                    kdjendok: "CHAR(2) NOT NULL"
                },
                {
                    kdsatker: "CHAR(6) NOT NULL"
                },
                {
                    kddept: "CHAR(3) NOT NULL"
                },
                {
                    kdunit: "CHAR(2) NOT NULL"
                },
                {
                    kdprogram: "CHAR(2) NOT NULL"
                },
                {
                    kdgiat: "CHAR(4) NOT NULL"
                },
                {
                    kdoutput: "CHAR(3) NOT NULL"
                },
                {
                    kdlokasi: "CHAR(2) NOT NULL"
                },
                {
                    kdkabkota: "CHAR(2) NOT NULL"
                },
                {
                    kddekon: "CHAR(1) NOT NULL"
                },
                {
                    kdsoutput: "CHAR(3) NOT NULL"
                },
                {
                    kdkmpnen: "CHAR(3) NOT NULL"
                },
                {
                    kdskmpnen: "CHAR(2) NOT NULL"
                },
                {
                    kdakun: "CHAR(6) NOT NULL"
                },
                {
                    kdkppn: "CHAR(3) NOT NULL"
                },
                {
                    kdbeban: "CHAR(1) NOT NULL"
                },
                {
                    kdjnsban: "CHAR(1) NOT NULL"
                },
                {
                    kdctarik: "CHAR(1) NOT NULL"
                },
                {
                    register: "CHAR(8) NOT NULL"
                },
                {
                    carahitung: "CHAR(1)"
                },
                {
                    prosenphln: "DECIMAL(3,0)"
                },
                {
                    prosenrkp: "DECIMAL(3,0)"
                },
                {
                    prosenrmp: "DECIMAL(3,0)"
                },
                {
                    kppnrkp: "CHAR(3)"
                },
                {
                    kppnrmp: "CHAR(3)"
                },
                {
                    kppnphln: "CHAR(3)"
                },
                {
                    regdam: "CHAR(8)"
                },
                {
                    kdluncuran: "CHAR(1)"
                },
                {
                    kdblokir: "CHAR(1)"
                },
                {
                    uraiblokir: "VARCHAR(255)"
                },
                {
                    kdib: "CHAR(2)"
                },
            ],
            d_output: [
                // ... (data stucture)
            ],
            d_soutput: [
                // ... (data stucture)
            ],
            d_kmpnen: [
                // ... (data stucture)
            ],
            d_skmpnen: [
                // ... (data stucture)
            ],
        };

        // Field mappings for improved display
        const fieldMappings = {
            thang: 'Tahun',
            kdjendok: 'Jenis Dokumen',
            kdsatker: 'Satker',
            kddept: 'Kementerian',
            kdunit: 'Unit',
            kdprogram: 'Program',
            kdgiat: 'Kegiatan',
            kdoutput: 'KRO',
            kdlokasi: 'Lokasi',
            kdkabkota: 'Kab/Kota',
            kddekon: 'Dekon',
            kdsoutput: 'RO',
            kdkmpnen: 'Komponen',
            kdskmpnen: 'Sub Komponen',
            kdjenbel: 'Jenis Belanja',
            kdakun: 'Akun',
            kdkppn: 'KPPN',
            kdbeban: 'Beban',
            kdjnsban: 'Jenis Bantuan',
            kdctarik: 'Cara Penarikan',
            register: 'Register',
            volkeg: 'Volume',
            satkeg: 'Satuan',
            hargasat: 'Harga Satuan',
            jumlah: 'Jumlah',
            rphblokir: 'Rupiah Blokir',
            jnsblokir: 'Jenis Blokir',
            uraiblokir: 'Uraian Blokir',
            carahitung: 'Cara Hitung',
            header1: 'Header 1',
            header2: 'Header 2',
            kdheader: 'Kode Header',
            noitem: 'Nomor Item',
            nmitem: 'Nama Item',
            vol1: 'Volume 1',
            sat1: 'Satuan 1',
            vol2: 'Volume 2',
            sat2: 'Satuan 2',
            vol3: 'Volume 3',
            sat3: 'Satuan 3',
            vol4: 'Volume 4',
            sat4: 'Satuan 4',
            jumlah2: 'Jumlah 2',
            paguphln: 'Pagu PHLN',
            pagurmp: 'Pagu RMP',
            pagurkp: 'Pagu RKP',
            kdblokir: 'Kode Blokir',
            blokirphln: 'Blokir PHLN',
            blokirrmp: 'Blokir RMP',
            blokirrkp: 'Blokir RKP',
            kdcopy: 'Kode Copy',
            kdabt: 'Kode ABT',
            kdsbu: 'Kode SBU',
            volsbk: 'Volume SBK',
            volrkakl: 'Volume RKAKL',
            blnkontrak: 'Bulan Kontrak',
            nokontrak: 'Nomor Kontrak',
            tgkontrak: 'Tanggal Kontrak',
            nilkontrak: 'Nilai Kontrak',
            januari: 'Januari',
            pebruari: 'Februari',
            maret: 'Maret',
            april: 'April',
            mei: 'Mei',
            juni: 'Juni',
            juli: 'Juli',
            agustus: 'Agustus',
            september: 'September',
            oktober: 'Oktober',
            nopember: 'November',
            desember: 'Desember',
            jmltunda: 'Jumlah Tunda',
            kdluncuran: 'Kode Luncuran',
            jmlabt: 'Jumlah ABT',
            norev: 'Nomor Revisi',
            kdubah: 'Kode Ubah',
            kurs: 'Kurs',
            indexkpjm: 'Index KPJM',
            kdib: 'Kode IB',
            kdstatus: 'Kode Status'
        };

        // Helper Functions
        function getFieldNames(tableStructure) {
            return tableStructure.map(item => Object.keys(item)[0]);
        }

        function makeKey(row, fields) {
            const keyFields = [
                'kdsatker', 'kdprogram', 'kdgiat', 'kdoutput',
                'kdsoutput', 'kdkmpnen', 'kdskmpnen', 'kdlokasi',
                'kdbeban', 'kdjnsban', 'kdctarik', 'register', 'kdakun'
            ];
            return keyFields.map(field => {
                const fieldIndex = fields.indexOf(field);
                return fieldIndex !== -1 ? row[fieldIndex] : '';
            }).join('');
        }

        // File Validation
        function validateADKFile(file) {
            if (!/\.s(2[4-9]|30)$/.test(file.name)) {
                $('.file-info').html('File ADK tidak valid.')
                    .addClass('text-danger');
                $('.process-info').html('Gunakan ADK SAKTI dengan format .s24 hingga .s30');
                return false;
            }

            return true;
        }

        // Initialize Tables
        function initializeDataTable(data, headers) {
            let exportFormatter = {
                format: {
                    body: function(data, row, column, node) {
                        // Strip $ from salary column to make it numeric
                        return column === 3 ? data.replace(/[.]/g, '') : data;
                    }
                }
            };

            vTitle = "Data SBKU";
            $('#dataTableOutput').html(`
            <table id="itemTable" class="hover cell-border small"></table>
            `);

            $('#itemTable').DataTable({
                layout: {
                    topStart: {
                        buttons: [
                            'copy', 'excel', 'pdf', 'print'
                        ]
                    }
                },
                scrollX: true,
                scrollY: '400px',
                pageLength: 25,
                columnDefs: [{
                    targets: '_all',
                    className: 'text-nowrap' // Prevents text wrapping
                }],
                data: data,
                columns: headers.map((header, index) => ({
                    title: fieldMappings[header] || header,
                    data: function(row) {
                        return row[index] || '';
                    },
                    // Add width calculation based on header text length
                    width: Math.max(header.length * 10, 100) + 'px'
                })),
                autoWidth: true,
                columnDefs: [{
                    targets: '_all',
                    render: function(data, type, row) {
                        if (type === 'display') {
                            return data;
                        }
                        return data;
                    }
                }],
                language: {
                    info: 'Halaman _PAGE_ dari _PAGES_',
                    infoEmpty: 'Tidak ada data yang ditemukan',
                    infoFiltered: '(filter dari _MAX_ data)',
                    lengthMenu: '_MENU_  &nbsp; data per halaman',
                    emptyTable: 'Data tidak ada.',
                    zeroRecords: 'Tidak ada data yang ditemukan.',
                    search: 'Cari : '
                },
                drawCallback: function() {
                    // Adjust columns after table is drawn
                    $(this).DataTable().columns.adjust();
                },
                initComplete: function() {
                    // Adjust columns after initial draw
                    const table = this;

                    // Using setTimeout to ensure DOM is fully rendered
                    setTimeout(function() {
                        $(table).DataTable().columns.adjust();

                        // Force redraw of header elements
                        $('.dataTables_scrollHead').css('visibility', 'hidden');
                        $('.dataTables_scrollHead').css('visibility', 'visible');
                    }, 100);
                }
            });
        }

        function initializePivotTable(data) {
            // Log debug information about the arrays
            console.log('============= DEBUG DATA ARRAYS =============');
            console.log('d_item array:', window.debugData.d_item);
            console.log('d_item length:', window.debugData.d_item.length);
            console.log('Sample d_item row:', window.debugData.d_item[0]);
            console.log('\nd_akun array:', window.debugData.d_akun);
            console.log('d_akun length:', window.debugData.d_akun.length);
            console.log('Sample d_akun row:', window.debugData.d_akun[0]);
            console.log('\nd_data array:', window.debugData.d_data);
            console.log('d_data length:', window.debugData.d_data.length);
            console.log('Sample d_data row:', window.debugData.d_data[0]);
            console.log('=========================================');

            var dataClass = $.pivotUtilities.SubtotalPivotData;
            var renderers = $.extend(
                $.pivotUtilities.renderers,
                $.pivotUtilities.export_renderers,
                $.pivotUtilities.subtotal_renderers
            );

            $("#pivotTableOutput").pivotUI(
                data, {
                    dataClass: dataClass,
                    rows: ["Kegiatan", "Jenis Belanja"],
                    cols: ["Jenis Blokir"],
                    aggregatorName: "Integer Sum",
                    vals: ["Rupiah Blokir"],
                    rendererName: "Subtotal",
                    renderers: renderers,
                    rendererOptions: {
                        arrowCollapsed: "[+] ",
                        arrowExpanded: "[-] ",
                        rowSubtotalDisplay: {
                            collapseAt: 0
                        },
                        colSubtotalDisplay: {
                            collapseAt: 0
                        }
                    },
                    showUI: true,
                    unusedAttrsVertical: true,
                    hiddenAttributes: [],
                    onRefresh: function(config) {
                        localStorage.setItem('pivotConfig', JSON.stringify(config));
                    }
                }
            );
        }

        // File Processing
        async function processFileData(unrarResult) {
            window.debugData = {};
            const d_item_fields = getFieldNames(tableStructures.d_item);
            const d_akun_fields = getFieldNames(tableStructures.d_akun);

            // Selected columns for d_data (equivalent to Python's selected_columns)
            const selected_columns = [
                'kdsatker', 'kdprogram', 'kdgiat', 'kdoutput', 'kdsoutput', 'kdlokasi', 'kdkabkota', 'kddekon', 'kdkmpnen', 'kdskmpnen', 'kdakun', 'kdkppn', 'kdbeban', 'kdjnsban', 'kdctarik', 'register', 'volkeg', 'satkeg', 'hargasat', 'paguphln', 'pagurmp', 'pagurkp', 'jumlah', 'kdblokir', 'blokirphln', 'blokirrmp', 'blokirrkp', 'rphblokir', 'nmitem'
            ];

            // Numeric columns that need type conversion
            const numeric_columns = [
                'volkeg', 'hargasat', 'jumlah', 'paguphln', 'pagurmp', 'pagurkp', 'blokirphln', 'blokirrmp', 'blokirrkp', 'rphblokir'
            ];

            // Merge columns for joining d_item and d_akun
            const merge_cols = [
                'kdsatker', 'kdprogram', 'kdgiat', 'kdoutput', 'kdlokasi', 'kdkabkota', 'kddekon', 'kdsoutput', 'kdkmpnen', 'kdskmpnen', 'kdakun'
            ];

            let d_item = [];
            let d_akun = [];
            let found_d_akun = false;
            let found_d_item = false;

            // First pass: Read and parse the files
            for (const fileName in unrarResult.ls) {
                const fileEntry = unrarResult.ls[fileName];
                const decoder = new TextDecoder();
                const content = decoder.decode(fileEntry.fileContent);
                const rows = content.split('\n')
                    .filter(row => row.trim() !== '')
                    .map(row => row.replace(/\^/g, '').split('|').map(field => field.trim()));

                if (/^d_akun\d+\.csv$/.test(fileName)) {
                    found_d_akun = true;
                    d_akun = rows;
                } else if (/^d_item\d+\.csv$/.test(fileName)) {
                    found_d_item = true;
                    d_item = rows;
                }
            }

            // Validate file presence
            if (!found_d_akun) throw new Error("File d_akun{n}.csv tidak ditemukan dalam file ADK!");
            if (!found_d_item) throw new Error("File d_item{n}.csv tidak ditemukan dalam file ADK!");

            // Store arrays for debugging
            window.debugData.d_item = d_item;
            window.debugData.d_akun = d_akun;

            // Create d_data array (equivalent to df_gab)
            let d_data = d_item.map(row => {
                const newRow = {};
                selected_columns.forEach(col => {
                    const idx = d_item_fields.indexOf(col);
                    if (idx !== -1) {
                        // Convert numeric values
                        if (numeric_columns.includes(col)) {
                            newRow[col] = parseInt(parseFloat(row[idx])) || 0;
                        } else {
                            newRow[col] = row[idx] || '';
                        }
                    }
                });
                // Add kdjenbel from kdakun
                const kdakunIdx = d_item_fields.indexOf('kdakun');
                newRow.kdjenbel = row[kdakunIdx] ? row[kdakunIdx].substring(0, 2) : '';
                return newRow;
            });

            // Create lookup map for d_akun
            const d_akun_map = new Map();
            d_akun.forEach(row => {
                const key = merge_cols.map(col => {
                    const idx = d_akun_fields.indexOf(col);
                    return idx !== -1 ? row[idx] : '';
                }).join('|');

                const kdblokir_idx = d_akun_fields.indexOf('kdblokir');
                const uraiblokir_idx = d_akun_fields.indexOf('uraiblokir');

                d_akun_map.set(key, {
                    jnsblokir: kdblokir_idx !== -1 ? row[kdblokir_idx] : '',
                    uraiblokir: uraiblokir_idx !== -1 ? row[uraiblokir_idx] : ''
                });
            });

            // Merge d_data with d_akun information
            d_data = d_data.map(row => {
                const key = merge_cols.map(col => row[col] || '').join('|');
                const akun_data = d_akun_map.get(key) || {
                    jnsblokir: '',
                    uraiblokir: ''
                };
                return {
                    ...row,
                    jnsblokir: akun_data.jnsblokir,
                    uraiblokir: akun_data.uraiblokir
                };
            });

            // Convert d_data for table display
            const tableData = d_data.map(row => {
                return Object.keys(row).map(key => row[key]);
            });

            // Prepare headers for display
            const headers = [...Object.keys(d_data[0])];

            // Create pivot data with mapped headers
            const pivotData = d_data.map(row => {
                const mappedRow = {};
                Object.entries(row).forEach(([key, value]) => {
                    mappedRow[fieldMappings[key] || key] = value;
                });
                return mappedRow;
            });

            // Store d_data for debugging
            window.debugData.d_data = d_data;

            return {
                rows: tableData,
                headers: headers,
                pivotData: pivotData
            };
        }

        async function handleFile(file) {
            const startTime = performance.now();

            function updateProcessInfo(message, isError = false) {
                const elapsed = ((performance.now() - startTime) / 1000).toFixed(2);
                $('.process-info')
                    .html(`${message} [ ${elapsed} detik ]`)
                    .removeClass('text-orange text-danger')
                    .addClass(isError ? 'text-danger' : 'text-orange');
            }

            if (!validateADKFile(file)) {
                return;
            }

            $('.file-info').html(`File: <b>${file.name}</b>`);
            updateProcessInfo('Reading file...');

            try {
                let fileData = await readFileAsArrayBuffer(file);
                updateProcessInfo('Decompressing...');

                let unrarResult = await rpc.unrar([{
                    name: file.name,
                    content: fileData
                }], null);

                if (!unrarResult || !unrarResult.ls) {
                    throw new Error("Invalid RAR structure");
                }

                updateProcessInfo('Processing data...');

                const processedData = await processFileData(unrarResult);

                // Show results tabs
                $('#results-tabs').show();

                // Initialize tables
                initializeDataTable(processedData.rows, processedData.headers);
                initializePivotTable(processedData.pivotData);

                updateProcessInfo('Processing complete');

            } catch (error) {
                console.error("Error processing file:", error);
                updateProcessInfo(error.message || 'Error processing file', true);
                $('#results-tabs').hide();
            }
        }

        // Helper function to read file as ArrayBuffer
        function readFileAsArrayBuffer(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = (e) => resolve(e.target.result);
                reader.onerror = (e) => reject(e.target.error);
                reader.readAsArrayBuffer(file);
            });
        }

        // Setup Event Handlers
        function setupEventHandlers() {
            dropArea.on('dragover', function(e) {
                e.preventDefault();
                dropArea.css('background-color', 'var(--tblr-primary-lt)');
            });

            dropArea.on('dragleave', function(e) {
                e.preventDefault();
                dropArea.css('background-color', '');
            });

            dropArea.on('drop', function(e) {
                e.preventDefault();
                dropArea.css('background-color', '');
                $('#results-tabs').hide();
                let files = e.originalEvent.dataTransfer.files;
                if (files.length > 0) {
                    handleFile(files[0]);
                }
            });

            dropArea.on('click', function() {
                fileInput.click();
            });

            fileInput.on('change', function(e) {
                let files = e.target.files;
                $('#results-tabs').hide();
                if (files.length > 0) {
                    handleFile(files[0]);
                }
            });
        }

        // Initialize libunrar
        let rpc;

        function initLibunrar() {
            return RPC.new("<?= base_url('./assets/plugins/libunrar-js/worker.js'); ?>", {
                locateFile: (path) => path,
                loaded: () => {
                    console.log("libunrar worker loaded");
                },
                progressShow: (fileName, fileSize, progress) => {
                    $('.process-info').text(`Decompressing ${fileName}: ${(100 * progress / fileSize).toFixed(2)}%`);
                }
            }, {
                // WebAssembly memory configuration
                initialMemory: 512, // 512MB
                maximumMemory: 1024, // 1GB
                noExitRuntime: true,
                ENVIRONMENT: 'WORKER',
                ALLOW_MEMORY_GROWTH: true
            }).then(r => {
                rpc = r;
                console.log("libunrar initialized with extended memory");
                return r;
            }).catch(error => {
                console.error("Failed to initialize libunrar:", error);
                $('.process-info')
                    .html("Error initializing unrar. Please try with a smaller file.")
                    .addClass('text-danger');
                throw error;
            });
        }

        // Start application
        initLibunrar().then(() => {
            console.log("libunrar initialized");
            setupEventHandlers();
        }).catch(error => {
            console.error("Error initializing libunrar:", error);
            alert("Error initializing application. Please refresh the page.");
        });
    });

    // Adjust columns when window resizes
    $(window).on('resize', function() {
        if ($.fn.dataTable.tables().length > 0) {
            $.fn.dataTable.tables({
                visible: true,
                api: true
            }).columns.adjust();
        }
    });

    // Adjust columns when switching tabs (karena Anda memiliki struktur tab)
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
        if ($.fn.dataTable.tables().length > 0) {
            $.fn.dataTable.tables({
                visible: true,
                api: true
            }).columns.adjust();
        }
    });

    // Make sure the DataTable columns are adjusted when browser window changes size
    // This is helpful especially on mobile devices
    $(document).ready(function() {
        if ($.fn.dataTable.tables().length > 0) {
            $.fn.dataTable.tables({
                visible: true,
                api: true
            }).columns.adjust();
        }
    });
</script>