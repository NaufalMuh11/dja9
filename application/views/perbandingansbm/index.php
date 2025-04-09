<div class="page-wrapper">
   <div class="container-fluid">
      <div class="page-header d-print-none" style="margin-top: 10px;">
         <div class="row align-items-center">

            <div class="col">
               <div class="page-pretitle">
                  Anggaran
               </div>
               <h2 class="page-title">
                  <span class="text-cyan">Grafik&nbsp;</span> SBM
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
   <div class="page-body">
      <div class="container-fluid">
      </div>
   </div>
</div>

<script>
   document.addEventListener("DOMContentLoaded", function() {
      document.getElementById('lastUpdate').innerHTML = `<?php echo get_last_update(); ?>`; // get last update
   });
</script>