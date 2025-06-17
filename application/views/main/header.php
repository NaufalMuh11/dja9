<header class="navbar navbar-expand-md navbar-light sticky-top d-print-none">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
      <a href=".">
        <img src="./files/images/data.png" width="110" height="32" alt="Tabler" class="navbar-brand-image">
    </h1>
    <div class="navbar-nav flex-row order-md-last">

      <!-- theme -->
      <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode" data-bs-toggle="tooltip" data-bs-placement="bottom" style="display: block;margin-top: 5px;">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" />
        </svg>
      </a>
      <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode" data-bs-toggle="tooltip" data-bs-placement="bottom" style="display: block;margin-top: 5px;">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <circle cx="12" cy="12" r="4" />
          <path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" />
        </svg>
      </a>

      <!-- Notifikasi  -->
      <div class="nav-item dropdown d-none d-md-flex me-3">
        <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show notifications">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
            <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
          </svg>
          <span class="badge bg-red"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
          <div class="card">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Notifikasi</h3>
              </div>
              <div class="list-group list-group-flush list-group-hoverable">
                <div class="list-group-item">
                  <div class="row align-items-center">
                    <div class="col-auto"><span class="status-dot status-dot-animated bg-red d-block"></span></div>
                    <div class="col text-truncate">
                      <a href="#" class="text-body d-block">Notifikasi 01</a>
                      <div class="d-block text-secondary text-truncate mt-n1">
                        Notifikasi Pertama
                      </div>
                    </div>
                    <div class="col-auto">
                      <a href="#" class="list-group-item-actions">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted icon-2">
                          <path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path>
                        </svg>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- profile  -->
      <div class="nav-item dropdown">
        <?php
        $foto_profile = "files/profiles/_noprofile.png";
        if (file_exists("files/profiles/" . $this->session->userdata('profilepic'))) {
          $foto_profile =  "files/profiles/" . $this->session->userdata('profilepic');
        }
        ?>

        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
          <span class="avatar avatar-sm avatar-rounded me-1" style="background-image: url(<?php echo $foto_profile ?>)"></span>
          <div class="d-none d-xl-block ps-2">
            <div><?php echo $this->session->userdata('nmuser') ?></div>
            <div class="mt-1 small text-muted"><?php echo $this->session->userdata('jabatan') ?></div>
          </div>
        </a>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">

          <a class="dropdown-item" href="javascript:void(0)" onclick="swal_profile()" rel="noopener">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-circle text-blue">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
              <path d="M12 10m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
              <path d="M6.168 18.849a4 4 0 0 1 3.832 -2.849h4a4 4 0 0 1 3.834 2.855" />
            </svg>
            &nbsp;Profile
          </a>

          <a class="dropdown-item" href="login/logout" rel="noopener">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-logout text-red">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
              <path d="M9 12h12l-3 -3" />
              <path d="M18 15l3 -3" />
            </svg>
            &nbsp;Logout
          </a>
        </div>
      </div>
    </div>

    <div class="collapse navbar-collapse" id="navbar-menu">
      <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
        <ul class="navbar-nav">

          <!-- menubar -->
          <li class="nav-item <?php if ($_GET['q'] == "d45h1" or $_GET['q'] == "d45h2") echo "active"; ?> dropdown">
            <a class="nav-link dropdown-toggle" href="#navbar-dashboard" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-presentation-analytics" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <path d="M9 12v-4"></path>
                  <path d="M15 12v-2"></path>
                  <path d="M12 12v-1"></path>
                  <path d="M3 4h18"></path>
                  <path d="M4 4v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-10"></path>
                  <path d="M12 16v4"></path>
                  <path d="M9 20h6"></path>
                </svg>
              </span>
              <span class="nav-link-title">
                Dashboard
              </span>
            </a>
            <div class="dropdown-menu">
              <a class="dropdown-item disabled" href="dashboard?q=d45h2">
                Penyelesaian&nbsp;<b>Standar Biaya</b>
              </a>
              <a class="dropdown-item disabled" href="dashboard?q=d45h1">
                Penyelesaian&nbsp;<b>Dokumen DIPA</b>
              </a>
              <a class="dropdown-item" href="dashboard?q=d45h01">
                Monitoring&nbsp;<b>Efisiensi Anggaran</b>
              </a>
              <a class="dropdown-item" href="dashboard?q=d45h02">
                Dashboard&nbsp;<b>Magang</b>
              </a>
            </div>
          </li>

          <li class="nav-item  <?php if ($_GET['q'] == "d45h3") echo "active"; ?> dropdown">
            <a class="nav-link dropdown-toggle" href="#navbar-dashboard" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chart-bubble" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <circle cx="6" cy="16" r="3"></circle>
                  <circle cx="16" cy="19" r="2"></circle>
                  <circle cx="14.5" cy="7.5" r="4.5"></circle>
                </svg>
              </span>
              <span class="nav-link-title">
                Analitika
              </span>
            </a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="analitika?q=QR4DK">
                Quick Report&nbsp;<b>ADK</b>
              </a>
              <a class="dropdown-item disabled" href="analitika?q=4n471k">
                Angka Dasar
              </a>
            </div>
          </li>

          <li class="nav-item  <?php if ($_GET['q'] == "RAG") echo "active"; ?>">
            <a class="nav-link" href="telaah?q=RAG">
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-template" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <rect x="4" y="4" width="16" height="4" rx="1"></rect>
                  <rect x="4" y="12" width="6" height="8" rx="1"></rect>
                  <line x1="14" y1="12" x2="20" y2="12"></line>
                  <line x1="14" y1="16" x2="20" y2="16"></line>
                  <line x1="14" y1="20" x2="20" y2="20"></line>
                </svg>
              </span>
              <span class="nav-link-title">
                Telaah
              </span>
            </a>
          </li>

          <li class="nav-item  <?php if ($_GET['q'] == "d45h4") echo "active"; ?> dropdown">
            <a class="nav-link dropdown-toggle" href="anggaran?q=myTask" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-report-analytics" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"></path>
                  <rect x="9" y="3" width="6" height="4" rx="2"></rect>
                  <path d="M9 17v-5"></path>
                  <path d="M12 17v-1"></path>
                  <path d="M15 17v-3"></path>
                </svg>
              </span>
              <span class="nav-link-title">
                Anggaran
              </span>
            </a>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="anggaran?q=MyTask">
                Monitoring <b>&nbsp;MyTask</b>
              </a>
              <a class="dropdown-item" href="referensi?q=RefSBM">
                Referensi<b>&nbsp;SBM</b>
              </a>
              <a class="dropdown-item" href="perbandingan?q=GrafSBM">
                Grafik<b>&nbsp;SBM</b>
              </a>
            </div>
          </li>

        </ul>
      </div>
    </div>
  </div>
</header>

<body>

  <script>
    function swal_profile() {
      Swal.fire({
        title: 'Profile Anda',
        timer: 1000,
        showConfirmButton: false,
        background: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#1f2937' : '#ffffff',
        color: document.documentElement.getAttribute('data-bs-theme') === 'dark' ? '#ffffff' : '#1f2937',
      });
    }
  </script>

  </html>