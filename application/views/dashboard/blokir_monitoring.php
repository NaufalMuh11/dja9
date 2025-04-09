<div class="page-wrapper">
   <div class="container-fluid">
      <div class="page-header d-print-none" style="margin-top: 10px;">
         <div class="row align-items-center">

            <!-- select thang  -->
            <div class="col">
               <div class="page-pretitle">
                  Monitoring
               </div>
               <h2 class="page-title">
                  <span class="text-warning">Efisiensi Anggaran&nbsp;</span>
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
                  <div class="d-none d-sm-block ps-2">
                     <div id="lastUpdate"></div>
                  </div>
               </div>
            </div>

         </div>
      </div>
   </div>

   <div class="page-body" style="margin-top: 10px; margin-bottom: 0px">
      <div class="container-fluid">
         <div class="row row-deck row-cards">

            <!-- Judul - Progress -->
            <div class="col-12">
               <div class="card">
                  <div style="padding: 10px">
                     <div class="position-absolute top-10 left-0 px-1 mt-1 w-100">
                        <div class="row g-1">
                           <div class="col-8">
                              <div>Progres Penyelesaian
                                 &nbsp;
                                 <span class="text-end text-cyan" onclick="modal_sbkk_datatable()" style="cursor: pointer;" data-toggle="tooltip" title="Tampilkan Data per K/L">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-window-maximize">
                                       <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                       <path d="M3 16m0 1a1 1 0 0 1 1 -1h3a1 1 0 0 1 1 1v3a1 1 0 0 1 -1 1h-3a1 1 0 0 1 -1 -1z" />
                                       <path d="M4 12v-6a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-6" />
                                       <path d="M12 8h4v4" />
                                       <path d="M16 8l-5 5" />
                                    </svg>
                                 </span>
                              </div>
                              <div class="text-muted small">Berdasarkan <b>Kementerian/Lembaga</b></div>
                           </div>
                           <div class="col-4" style="padding-right: 30px">
                              <div class="text-end text-teal">
                                 <h2 style="cursor: pointer;" data-toggle="tooltip" title="Tampilkan Data per K/L" onclick="modal_sbkk_datatable()"><span id="total_kl_sbkk">0</span> %</h2>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div id="chart-KL" style="padding: 25px 0 0 0; height: 225px;"></div>
                  </div>
               </div>
            </div>

            <div class="col-6">
               <div class="card">
                  <div id="ambilData">AA</div>
               </div>
            </div>

            <div class="col-6">
               <div class="card">

                  <!-- SBKK -->
                  <div class="card-table" style="padding: 10px;">
                     <div class="row">
                        <div class="col">
                           <div class="row g-1">
                              <div class="col">
                                 <div class="strong">SBK Khusus
                                 </div>
                                 <div class="text-muted small">Berdasarkan Penyelesaian</div>
                              </div>
                              <div class="col">
                                 <div class="text-end text-teal">
                                    <h2><span class="sbkk_ditetapkan_total">0</span> SBKK</h2>
                                 </div>
                              </div>
                           </div>
                           <div class="card-table table-responsive">
                              <table class="table table-vcenter table-bordered table-nowrap table-list" style="margin-top: 0px; font-size: small;">
                                 <thead>
                                    <tr>
                                       <td class="w-50">
                                          <div class="strong text-muted">
                                             <span style="font-size:large;"><b>SBKU </b></span>
                                          </div>
                                       </td>
                                       <td class="text-end">
                                          <div class="text-muted">Diajukan <span style="font-size:large;"><b> <span class="sbku_diajukan">162</span></b></span>
                                          </div>
                                       </td>
                                       <td class="text-end">
                                          <div class="text-muted">Disetujui <span style="font-size:large;"><b> <span class="sbku_disetujui">162</span> </b></span>
                                          </div>
                                       </td>
                                       <td class="text-end">
                                          <div class="text-muted">Ditetapkan <span style="font-size:large;"><b><span class="sbku_ditetapkan">162</span> </b></span></div>
                                       </td>
                                       <td class="text-end" style="padding: 5px;">
                                          <div class="text-orange"><span style="font-size:large;"><b> <span class="sbku_persen">100.00</span>% </b></span></div>
                                       </td>
                                    </tr>
                                 </thead>
                                 <tbody>

                                    <tr>
                                       <td style="padding-top: 5px; padding-bottom: 5px;">
                                          <h4 style="margin: 0">SBKU inisiatif <span class="text-purple">DJA</span></h4>
                                       </td>
                                       <td style="padding-top: 5px; padding-bottom: 5px;" class="text-end strong">
                                          <span class="sbku_diajukan">162</span>
                                       </td>
                                       <td style="padding-top: 5px; padding-bottom: 5px;" class="text-end strong">
                                          <span class="sbku_disetujui">162</span>
                                       </td>
                                       <td style="padding-top: 5px; padding-bottom: 5px;" class="text-end strong">
                                          <span class="sbku_ditetapkan">162</span>
                                       </td>
                                       <td class="text-end" style="padding: 5px;">
                                          <h3 class="text-orange text-end"><span class="sbku_persen">100.00</span>%</h3>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td colspan="5" style="padding-top: 10px; padding-bottom: 10px;">
                                          <!-- <div style="margin: 0">Terdapat link <span class="text-cyan strong">SBKU - RO</span>
                                                   sejumlah <span class="strong text-orange">0 RO</span>
                                                   &nbsp;
                                                   <span class="text-end text-teal" data-bs-toggle="modal" data-bs-target="#modal-data" style="cursor: pointer;" data-toggle="tooltip" title="Tampilkan Link SBKU - RO">
                                                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-window-maximize">
                                                         <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                         <path d="M3 16m0 1a1 1 0 0 1 1 -1h3a1 1 0 0 1 1 1v3a1 1 0 0 1 -1 1h-3a1 1 0 0 1 -1 -1z" />
                                                         <path d="M4 12v-6a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-6" />
                                                         <path d="M12 8h4v4" />
                                                         <path d="M16 8l-5 5" />
                                                      </svg>
                                                   </span>
                                             </div> -->
                                          <div class="mt-1">Belum terdapat <span class="text-cyan strong">RO</span> yang di-link dengan <span class="text-cyan strong">SBKU</span>.
                                          </div>
                                       </td>
                                    </tr>
                                 </tbody>
                              </table>
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

<script>
   // Immediately Invoked Function Expression (IIFE) untuk menghindari variable global
   (() => {
      // Constants
      const SITE_URL = '<?= site_url("dashboard?q=rangeThang") ?>';
      const DEFAULT_THANG = '<?= $this->session->userdata("thang") ?>';

      // DOM Elements
      const elements = {
         dropdownMenu: document.querySelector('#selectThang .dropdown-menu'),
         dropdownToggle: document.querySelector('#selectThang .dropdown-toggle'),
         selectedThangInput: document.getElementById('selected_thang'),
         lastUpdate: document.getElementById('lastUpdate')
      };

      // Handlers
      const handleThangSelection = (item, element) => {
         // Remove active class from all items
         document.querySelectorAll('#selectThang .dropdown-item')
            .forEach(el => el.classList.remove('active'));

         // Add active class to selected item
         element.classList.add('active');

         // Update UI
         elements.dropdownToggle.textContent = `T.A. ${item}`;
         elements.selectedThangInput.value = item;

         // Here you can add your fetch data logic
         // fetchData(item);
      };

      const createDropdownItem = (item) => {
         const element = document.createElement('a');
         Object.assign(element, {
            className: 'dropdown-item',
            href: '#',
            textContent: `Tahun Anggaran ${item}`
         });

         element.addEventListener('click', (e) => {
            e.preventDefault();
            handleThangSelection(item, element);
         });

         return element;
      };

      const populateDropdown = (data) => {
         // Clear existing items
         elements.dropdownMenu.innerHTML = '';

         // Add new items
         const fragments = document.createDocumentFragment();
         data.forEach(item => fragments.appendChild(createDropdownItem(item)));
         elements.dropdownMenu.appendChild(fragments);

         // Set default selection
         const defaultItem = Array.from(elements.dropdownMenu.querySelectorAll('.dropdown-item'))
            .find(item => item.textContent.includes(DEFAULT_THANG));

         if (defaultItem) {
            handleThangSelection(DEFAULT_THANG, defaultItem);
         } else {
            // Jika tidak ada default, pilih item pertama
            const firstItem = elements.dropdownMenu.querySelector('.dropdown-item');
            if (firstItem) {
               const firstThang = firstItem.textContent.match(/\d+/)[0];
               handleThangSelection(firstThang, firstItem);
            }
         }
      };

      const getSelectThang = async () => {
         try {
            const response = await axios.post(SITE_URL, {});
            console.log('Thang ==>', response.data);
            populateDropdown(response.data);
         } catch (error) {
            console.error('Error Response >>', error);
         }
      };

      // Initialize
      document.addEventListener('DOMContentLoaded', () => {
         getSelectThang();
         elements.lastUpdate.innerHTML = '<?php echo get_last_update(); ?>';
      });
   })();
</script>

<!-- Charts Progress -->
<script>
   const options = {
      chart: {
         type: "bar",
         fontFamily: 'inherit',
         height: 200,
         // stacked: true,
         sparkline: {
            enabled: true
         },
         animations: {
            enabled: true
         },
      },
      dataLabels: {
         enabled: false,
      },
      fill: {
         colors: ["#ff7800"],
         type: 'solid'
      },
      series: [{
         name: "Progress",
         // data prosentase 
         data: [
            70, 10.11, 8.22, 16.45, 17.43, 25.09, 16.11, 18.22, 24.33, 17.54, 12.65, 5.44, 6.54, 3.90, 8.65, 4.4, 14.76, 30.87, 17.32, 19.23, 15.78, 14.56, 25.34, 32.32, 10, 55.21, 60.11, 48.33, 52.32, 30.55
         ]
      }],
      grid: {
         strokeDashArray: 3,
      },
      xaxis: {
         labels: {
            padding: 0,
         },
         tooltip: {
            enabled: false
         },
         axisBorder: {
            show: false,
         },
      },
      yaxis: {
         labels: {
            padding: 1
         },
      },
      labels: [
         // data kl 
         '020 NMKL', '021 NMKL', '022 NMKL', '023 NMKL', '024 NMKL', '025 NMKL', '026 NMKL', '027 NMKL', '028 NMKL', '029 NMKL', '030 NMKL', '101 NMKL', '102 NMKL', '103 NMKL', '104 NMKL', '105 NMKL', '106 NMKL', '107 NMKL', '108 NMKL', '109 NMKL', '110 NMKL', '111 NMKL', '112 NMKL', '113 NMKL', '114 NMKL', '115 NMKL', '116 NMKL', '117 NMKL', '118 NMKL', '119 NMKL'
      ],
      legend: {
         show: false,
      },
      point: {
         show: false
      }
   }

   var chart = new ApexCharts(document.getElementById("chart-KL"), options);
   chart.render();
</script>