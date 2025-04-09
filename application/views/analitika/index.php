<div class="page-wrapper">
   <div class="container-fluid">
      <div class="page-header d-print-none" style="margin-top: 10px;">
         <div class="row align-items-center">

            <!-- select thang  -->
            <div class="col">
               <div class="page-pretitle">
                  Analitika
               </div>
               <h2 class="page-title">
                  <span class="text-cyan">Angka Dasar&nbsp;</span> 
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
</div>

<!-- Event Listener -->
<script>
   document.addEventListener("DOMContentLoaded", function() {
      getSelectThang(); // get select thang
      document.getElementById('lastUpdate').innerHTML = `<?php echo get_last_update(); ?>`; // get last update
   });
</script>

<!-- get Select Thang  -->
<script>
   const getSelectThang = () => {
      axios.post('<?= site_url("analitika?q=rangeThang") ?>', {})
         .then(function(resp) {  
               let data = resp.data;
               console.log('Thang ==>', data);
               let dropdownMenu = document.querySelector('#selectThang .dropdown-menu');
               let dropdownToggle = document.querySelector('#selectThang .dropdown-toggle');
               let selectedThangInput = document.getElementById('selected_thang');
               // clear dropdown menu
               dropdownMenu.innerHTML = '';
               let thangDefault = '<?= $this->session->userdata("thang") ?>';
               console.log('Thang Default ==>', thangDefault);

               data.forEach(function(item) {
                  let a = document.createElement('a');
                  a.className = 'dropdown-item';
                  a.href = '#';	
                  a.textContent = `Tahun Anggaran ${item}`;

                  a.addEventListener('click', function(event) {
                     event.preventDefault();
                     document.querySelectorAll('#selectThang .dropdown-item').forEach(function(el) {
                        el.classList.remove('active');
                     });
                     // Add active class to the clicked item
                     a.classList.add('active');
                     // Update the dropdown toggle text and input value
                     dropdownToggle.textContent = `T.A. ${item}`;
                     selectedThangInput.value = item;

                     // fetch data
                     // ----------
                  });
                  dropdownMenu.appendChild(a);
               });
               dropdownMenu.querySelectorAll('.dropdown-item').forEach(function(item) {
                  if (item.textContent.includes(thangDefault)) {
                     item.classList.add('active');
                     dropdownToggle.textContent = `T.A. ${thangDefault}`;
                     selectedThangInput.value = thangDefault;
                  }
               });
         })
         .catch(function(error) {
            console.error('Error Response >> ', error);
         });  
   }
</script>
