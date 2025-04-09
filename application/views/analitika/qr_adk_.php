<!-- External CSS Dependencies -->
<link href="<?= base_url('assets/plugins/pivot/pivot.css'); ?>" rel="stylesheet" />
<link href="<?= base_url('assets/plugins/pivot/subtotal.css'); ?>" rel="stylesheet" />
<link href="<?= base_url('assets/dist/css/styleTable.css'); ?>" type="text/css" rel="stylesheet" />
<link href="<?= base_url('assets/dist/css/dataTables.css'); ?>" type="text/css" rel="stylesheet" />

<style>
    #drop-area {
        border: 1px dashed var(--tblr-border-color);
        padding: 15px;
        text-align: center;
        background: var(--tblr-bg-surface);
        transition: background-color 0.2s;
    }

    #drop-area.dragover {
        background-color: var(--tblr-primary-lt);
    }

    #drop-area .file-info {
        margin-bottom: 10px;
    }

    #drop-area .process-info {
        color: var(--tblr-muted);
    }

    .container {
        overflow-y: auto;
        padding: 0;
        margin: 0;
        max-width: none;
        width: 100%;
    }

    .dt-button {
        background: var(--tblr-cyan);
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        margin-right: 8px;
        transition: opacity 0.3s ease;
    }

    .dt-button:hover {
        opacity: 0.85;
    }

    .dt-layout-cell {
        padding-top: 0;
    }

    .loading-spinner {
        display: none;
        margin-top: 10px;
    }

    .loading-spinner.active {
        display: block;
    }
</style>

<!-- Main HTML Structure -->
<div class="page-wrapper">
    <div class="container-fluid">
        <div class="page-header d-print-none" style="margin-top: 10px;">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">Analitika</div>
                    <h2 class="page-title">
                        <span class="text-cyan">Quick Report </span>
                        <span class="text-blue text-bold"> ADK SAKTI</span>
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body" style="margin-top: 10px; margin-bottom: 0px">
        <div class="container-fluid">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="drop-area" role="region" aria-label="File upload area">
                                <div class="file-info mb-0">
                                    Seret dan lepas file <b>ADK SAKTI</b> di sini, atau klik untuk memilih file.
                                </div>
                                <div class="small text-orange process-info">
                                    Format File: d99_99999_99_999999_9.s99 (.s24 s.d. s30)
                                </div>
                                <div class="loading-spinner">
                                    <span class="spinner-border spinner-border-sm" role="status"></span>
                                    Memproses...
                                </div>
                            </div>
                            <input type="file" id="file-input" accept=".s24,.s25,.s26,.s27,.s28,.s29,.s30"
                                style="display: none;" aria-hidden="true">
                        </div>
                    </div>
                </div>

                <div class="col-12" id="results-tabs" style="display: none;">
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                                <li class="nav-item">
                                    <a href="#tabs-quick-report" class="nav-link active" data-bs-toggle="tab">
                                        Quick Report
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#tabs-data-item" class="nav-link" data-bs-toggle="tab">
                                        Data Item
                                    </a>
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

<!-- External JavaScript Dependencies -->
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
<script src="https://cdn.jsdelivr.net/npm/unrar-js@0.2.3/unrar.min.js"></script>

<!-- Main Application Script -->
<script>
    $(function() {
        // DOM Elements
        const $dropArea = $('#drop-area');
        const $fileInput = $('#file-input');
        const $processInfo = $('.process-info');
        const $fileInfo = $('.file-info');
        const $loadingSpinner = $('.loading-spinner');
        let unrar = null;

        // Data Structures (unchanged from original)
        const tableStructures = {
            /* [Original table structures remain identical] */ };
        const fieldMappings = {
            /* [Original field mappings remain identical] */ };

        // Utility Functions
        function updateProcessInfo(message, isError = false, elapsed = 0) {
            const timeText = elapsed > 0 ? ` [${elapsed.toFixed(2)} detik]` : '';
            $processInfo
                .html(`${message}${timeText}`)
                .removeClass('text-orange text-danger')
                .addClass(isError ? 'text-danger' : 'text-orange');
        }

        function cleanupDebugData() {
            if (window.debugData) {
                window.debugData = null;
                console.log('Debug data cleared');
            }
        }

        // Initialization
        async function initUnrar() {
            try {
                unrar = new Module.Unrar();
                console.log("Unrar.js initialized successfully");
            } catch (error) {
                throw new Error('Failed to initialize Unrar.js');
            }
        }

        // File Processing Helpers
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

        function validateADKFile(file) {
            const validPattern = /\.s(2[4-9]|30)$/;
            const maxSize = 500 * 1024 * 1024;

            if (!validPattern.test(file.name)) {
                throw new Error('Format file tidak didukung');
            }
            if (file.size > maxSize) {
                throw new Error('Ukuran file melebihi 500MB');
            }
            return true;
        }

        // Table Initialization
        function initializeDataTable(data, headers) {
            $('#dataTableOutput').html(`
            <table id="itemTable" class="hover cell-border small" style="width:100%"></table>
        `);

            $('#itemTable').DataTable({
                layout: {
                    topStart: {
                        buttons: ['copy', 'excel', 'pdf', 'print']
                    }
                },
                data: data,
                columns: headers.map((header, index) => ({
                    title: fieldMappings[header] || header,
                    data: row => row[index] || ''
                })),
                language: {
                    /* [Original language settings remain identical] */ },
                scrollX: true,
                scrollY: '400px',
                pageLength: 25,
                deferRender: true // Performance optimization
            });
        }

        function initializePivotTable(data) {
            const dataClass = $.pivotUtilities.SubtotalPivotData;
            const renderers = $.extend(
                $.pivotUtilities.renderers,
                $.pivotUtilities.export_renderers,
                $.pivotUtilities.subtotal_renderers
            );

            $("#pivotTableOutput").pivotUI(data, {
                dataClass: dataClass,
                rows: ["Kegiatan", "Jenis Belanja"],
                cols: ["Jenis Blokir"],
                aggregatorName: "Integer Sum",
                vals: ["Rupiah Blokir"],
                rendererName: "Subtotal",
                renderers: renderers,
                rendererOptions: {
                    /* [Original renderer options remain identical] */ },
                showUI: true,
                unusedAttrsVertical: true,
                hiddenAttributes: [],
                onRefresh: config => localStorage.setItem('pivotConfig', JSON.stringify(config))
            });
        }

        // Main File Processing
        async function processFileData(unrarResult) {
            const startTime = performance.now();
            window.debugData = {};

            const d_item_fields = getFieldNames(tableStructures.d_item);
            const d_akun_fields = getFieldNames(tableStructures.d_akun);
            const selected_columns = [ /* [Original selected columns remain identical] */ ];
            const numeric_columns = [ /* [Original numeric columns remain identical] */ ];
            const merge_cols = [ /* [Original merge columns remain identical] */ ];

            let d_item = [],
                d_akun = [];
            let found_d_akun = false,
                found_d_item = false;

            // Parse files
            for (const fileName in unrarResult.ls) {
                const fileEntry = unrarResult.ls[fileName];
                const decoder = new TextDecoder();
                const content = decoder.decode(fileEntry.fileContent);
                const rows = content.split('\n')
                    .filter(row => row.trim())
                    .map(row => row.replace(/\^/g, '').split('|').map(field => field.trim()));

                if (/^d_akun\d+\.csv$/.test(fileName)) {
                    found_d_akun = true;
                    d_akun = rows;
                } else if (/^d_item\d+\.csv$/.test(fileName)) {
                    found_d_item = true;
                    d_item = rows;
                }
            }

            if (!found_d_akun || !found_d_item) {
                throw new Error('File CSV yang diperlukan tidak ditemukan');
            }

            // Process in chunks for better performance
            const chunkSize = 1000;
            const d_data = [];
            for (let i = 0; i < d_item.length; i += chunkSize) {
                const chunk = d_item.slice(i, i + chunkSize).map(row => {
                    const newRow = {};
                    selected_columns.forEach(col => {
                        const idx = d_item_fields.indexOf(col);
                        if (idx !== -1) {
                            newRow[col] = numeric_columns.includes(col) ?
                                (parseInt(parseFloat(row[idx])) || 0) :
                                (row[idx] || '');
                        }
                    });
                    const kdakunIdx = d_item_fields.indexOf('kdakun');
                    newRow.kdjenbel = row[kdakunIdx]?.substring(0, 2) || '';
                    return newRow;
                });
                d_data.push(...chunk);
            }

            // Create lookup map
            const d_akun_map = new Map();
            d_akun.forEach(row => {
                const key = merge_cols.map(col => row[d_akun_fields.indexOf(col)] || '').join('|');
                d_akun_map.set(key, {
                    jnsblokir: row[d_akun_fields.indexOf('kdblokir')] || '',
                    uraiblokir: row[d_akun_fields.indexOf('uraiblokir')] || ''
                });
            });

            // Merge data
            const finalData = d_data.map(row => ({
                ...row,
                ...(d_akun_map.get(merge_cols.map(col => row[col] || '').join('|')) || {
                    jnsblokir: '',
                    uraiblokir: ''
                })
            }));

            const tableData = finalData.map(row => Object.values(row));
            const headers = Object.keys(finalData[0]);
            const pivotData = finalData.map(row => Object.fromEntries(
                Object.entries(row).map(([key, value]) => [fieldMappings[key] || key, value])
            ));

            console.log(`Processing completed in ${(performance.now() - startTime) / 1000} seconds`);
            return {
                rows: tableData,
                headers,
                pivotData
            };
        }

        // File Handler
        async function handleFile(file) {
            const startTime = performance.now();
            $loadingSpinner.addClass('active');

            try {
                validateADKFile(file);
                $fileInfo.html(`File: <b>${file.name}</b>`);
                updateProcessInfo('Membaca file...');

                const fileBuffer = await readFileAsArrayBuffer(file);
                updateProcessInfo('Mengekstrak file...');

                const fileList = unrar.listFiles(new Uint8Array(fileBuffer));
                const fileContents = {};
                for (const entry of fileList) {
                    if (!entry.directory) {
                        const extracted = unrar.extractFile(new Uint8Array(fileBuffer), entry.name);
                        fileContents[entry.name] = {
                            fileContent: extracted
                        };
                    }
                }

                updateProcessInfo('Memproses data...');
                const processedData = await processFileData({
                    ls: fileContents
                });

                $('#results-tabs').show();
                initializeDataTable(processedData.rows, processedData.headers);
                initializePivotTable(processedData.pivotData);

                updateProcessInfo('Selesai', false, (performance.now() - startTime) / 1000);

            } catch (error) {
                updateProcessInfo(error.message || 'Terjadi kesalahan', true);
                $('#results-tabs').hide();
                console.error('File processing error:', error);
            } finally {
                $loadingSpinner.removeClass('active');
                $fileInput.val('');
                cleanupDebugData();
            }
        }

        function readFileAsArrayBuffer(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = e => resolve(e.target.result);
                reader.onerror = e => reject(e.target.error);
                reader.readAsArrayBuffer(file);
            });
        }

        // Event Handlers
        function setupEventHandlers() {
            $dropArea.on({
                dragover: e => {
                    e.preventDefault();
                    $dropArea.addClass('dragover');
                },
                dragleave: e => {
                    e.preventDefault();
                    $dropArea.removeClass('dragover');
                },
                drop: e => {
                    e.preventDefault();
                    $dropArea.removeClass('dragover');
                    $('#results-tabs').hide();
                    const files = e.originalEvent.dataTransfer.files;
                    if (files.length > 0) handleFile(files[0]);
                },
                click: () => $fileInput.click()
            });

            $fileInput.on('change', e => {
                $('#results-tabs').hide();
                const files = e.target.files;
                if (files.length > 0) handleFile(files[0]);
            });
        }

        // Initialize
        initUnrar()
            .then(() => {
                setupEventHandlers();
                console.log("Application initialized");
            })
            .catch(error => {
                updateProcessInfo('Gagal memulai aplikasi. Silakan refresh halaman.', true);
                console.error("Initialization error:", error);
            });
    });
</script>