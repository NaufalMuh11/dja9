<div class="page-wrapper">
   <div class="container-fluid">
      <div class="page-header d-print-none" style="margin-top: 10px;">
         <div class="row align-items-center">

            <!-- select thang  -->
            <div class="col">
               <div class="page-pretitle">
                  Dashboard
               </div>
               <h2 class="page-title">
                  <span class="text-warning">Magang&nbsp;</span>
                  <input type="hidden" id="selected_thang">
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

    <div class="page-body" style="margin-top: 10px; margin-bottom: 0px;">
        <div class="container-fluid">
            <div class="card mb-3 rounded-3">
                <div class="card-body">
                    <div class="row align-items-center mb-4">
                        <div class="col">
                            <p class="mb-0 fw-bolder fs-2 lh-1">Meet The Team</p>
                            <p class="mb-0 fw-medium fst-italic fs-3 lh-1" style="color: #002DE3;">Magang Batch #1</p>
                        </div>
                        <div class="col-auto">
                            <img src="<?= base_url('files/images/kemenkeu.png'); ?>" alt="Logo Kemenkeu" style="height: 40px; width: auto;">
                        </div>
                    </div>

                    <div class="row g-4 justify-content-center mb-2">
                        <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                            <div class="text-center">
                                <div class="mx-auto">
                                    <img src="<?= base_url('files/team/wulan1.png'); ?>" alt="Team Member 1" class="avatar-img rounded-circle mb-2" style="object-fit: cover; transition: all 0.4s ease; cursor: pointer; transform-origin: center;" onmouseover="this.style.transform='translateY(-10px) scale(1.05) rotate(-2deg)'; this.style.opacity='0.9'; this.classList.add('shadow-lg')" onmouseout="this.style.transform='translateY(0px) scale(1) rotate(0deg)'; this.style.opacity='1'; this.classList.remove('shadow-lg')">
                                </div>
                                <h4 class="mt-2 mb-1 fs-3">Putri Wulan</h4>
                                <p class="text-muted small mb-3">Posisi/Role</p>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="https://www.instagram.com/_putriwulannn_/" target="_blank" class="text-dark" style="transition: transform 0.3s ease, color 0.3s ease;" onmouseover="this.style.transform='scale(1.15)'; this.style.color='#666';" onmouseout="this.style.transform='scale(1)'; this.style.color='#000';" onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1.15)'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                                            <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334" />
                                        </svg>
                                    </a>
                                    <a href="#" target="_blank" class="text-dark" style="transition: transform 0.3s ease, color 0.3s ease;" onmouseover="this.style.transform='scale(1.15)'; this.style.color='#666';" onmouseout="this.style.transform='scale(1)'; this.style.color='#000';" onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1.15)'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
                                            <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854zm4.943 12.248V6.169H2.542v7.225zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.226 2.4 3.934c0 .694.521 1.248 1.327 1.248zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016l.016-.025V6.169h-2.4c.30.678 0 7.225 0 7.225z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                            <div class="text-center">
                                <div class="mx-auto">
                                    <img src="<?= base_url('files/team/jopan1.png'); ?>" alt="Team Member 2" class="avatar-img rounded-circle mb-2" style="object-fit: cover; transition: all 0.4s ease; cursor: pointer; transform-origin: center;" onmouseover="this.style.transform='translateY(-10px) scale(1.05) rotate(-2deg)'; this.style.opacity='0.9'; this.classList.add('shadow-lg')" onmouseout="this.style.transform='translateY(0px) scale(1) rotate(0deg)'; this.style.opacity='1'; this.classList.remove('shadow-lg')">
                                </div>
                                <h4 class="mt-2 mb-1 fs-3">Gede Jovan</h4>
                                <p class="text-muted small mb-3">Posisi/Role</p>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="https://www.instagram.com/gede_jovan04/" target="_blank" class="text-dark" style="transition: transform 0.3s ease, color 0.3s ease;" onmouseover="this.style.transform='scale(1.15)'; this.style.color='#666';" onmouseout="this.style.transform='scale(1)'; this.style.color='#000';" onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1.15)'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                                            <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334" />
                                        </svg>
                                    </a>
                                    <a href="#" target="_blank" class="text-dark" style="transition: transform 0.3s ease, color 0.3s ease;" onmouseover="this.style.transform='scale(1.15)'; this.style.color='#666';" onmouseout="this.style.transform='scale(1)'; this.style.color='#000';" onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1.15)'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
                                            <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854zm4.943 12.248V6.169H2.542v7.225zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.226 2.4 3.934c0 .694.521 1.248 1.327 1.248zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016l.016-.025V6.169h-2.4c.30.678 0 7.225 0 7.225z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                            <div class="text-center">
                                <div class="mx-auto">
                                    <img src="<?= base_url('files/team/muh1.png'); ?>" alt="Team Member 3" class="avatar-img rounded-circle mb-2" style="object-fit: cover; transition: all 0.4s ease; cursor: pointer; transform-origin: center;" onmouseover="this.style.transform='translateY(-10px) scale(1.05) rotate(-2deg)'; this.style.opacity='0.9'; this.classList.add('shadow-lg')" onmouseout="this.style.transform='translateY(0px) scale(1) rotate(0deg)'; this.style.opacity='1'; this.classList.remove('shadow-lg')">
                                </div>
                                <h4 class="mt-2 mb-1 fs-3">Naufal Muhammad</h4>
                                <p class="text-muted small mb-3">Posisi/Role</p>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="https://www.instagram.com/naufalmuh.11" target="_blank" class="text-dark" style="transition: transform 0.3s ease, color 0.3s ease;" onmouseover="this.style.transform='scale(1.15)'; this.style.color='#666';" onmouseout="this.style.transform='scale(1)'; this.style.color='#000';" onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1.15)'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                                            <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334" />
                                        </svg>
                                    </a>
                                    <a href="#" target="_blank" class="text-dark" style="transition: transform 0.3s ease, color 0.3s ease;" onmouseover="this.style.transform='scale(1.15)'; this.style.color='#666';" onmouseout="this.style.transform='scale(1)'; this.style.color='#000';" onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1.15)'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
                                            <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854zm4.943 12.248V6.169H2.542v7.225zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.226 2.4 3.934c0 .694.521 1.248 1.327 1.248zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016l.016-.025V6.169h-2.4c.30.678 0 7.225 0 7.225z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                            <div class="text-center">
                                <div class="mx-auto">
                                    <img src="<?= base_url('files/team/hendrik2.png'); ?>" alt="Team Member 4" class="avatar-img rounded-circle mb-2" style="object-fit: cover; transition: all 0.4s ease; cursor: pointer; transform-origin: center;" onmouseover="this.style.transform='translateY(-10px) scale(1.05) rotate(-2deg)'; this.style.opacity='0.9'; this.classList.add('shadow-lg')" onmouseout="this.style.transform='translateY(0px) scale(1) rotate(0deg)'; this.style.opacity='1'; this.classList.remove('shadow-lg')">
                                </div>
                                <h4 class="mt-2 mb-1 fs-3">Hendrik</h4>
                                <p class="text-muted small mb-3">Posisi/Role</p>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="https://www.instagram.com/h.tarigannn_" target="_blank" class="text-dark" style="transition: transform 0.3s ease, color 0.3s ease;" onmouseover="this.style.transform='scale(1.15)'; this.style.color='#666';" onmouseout="this.style.transform='scale(1)'; this.style.color='#000';" onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1.15)'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                                            <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334" />
                                        </svg>
                                    </a>
                                    <a href="#" target="_blank" class="text-dark" style="transition: transform 0.3s ease, color 0.3s ease;" onmouseover="this.style.transform='scale(1.15)'; this.style.color='#666';" onmouseout="this.style.transform='scale(1)'; this.style.color='#000';" onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1.15)'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
                                            <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854zm4.943 12.248V6.169H2.542v7.225zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.226 2.4 3.934c0 .694.521 1.248 1.327 1.248zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016l.016-.025V6.169h-2.4c.30.678 0 7.225 0 7.225z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-4 col-sm-6 col-12">
                            <div class="text-center">
                                <div class="mx-auto">
                                    <img src="<?= base_url('files/team/firdauss.png'); ?>" alt="Team Member 5" class="avatar-img rounded-circle mb-2" style="object-fit: cover; transition: all 0.4s ease; cursor: pointer; transform-origin: center;" onmouseover="this.style.transform='translateY(-10px) scale(1.05) rotate(-2deg)'; this.style.opacity='0.9'; this.classList.add('shadow-lg')" onmouseout="this.style.transform='translateY(0px) scale(1) rotate(0deg)'; this.style.opacity='1'; this.classList.remove('shadow-lg')">
                                </div>
                                <h4 class="mt-2 mb-1 fs-3">Firdaus</h4>
                                <p class="text-muted small mb-3">Posisi/Role</p>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="https://www.instagram.com/firdaus_nuzula12" target="_blank" class="text-dark" style="transition: transform 0.3s ease, color 0.3s ease;" onmouseover="this.style.transform='scale(1.15)'; this.style.color='#666';" onmouseout="this.style.transform='scale(1)'; this.style.color='#000';" onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1.15)'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                                            <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.9 3.9 0 0 0-1.417.923A3.9 3.9 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.9 3.9 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.9 3.9 0 0 0-.923-1.417A3.9 3.9 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599s.453.546.598.92c.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.5 2.5 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.5 2.5 0 0 1-.92-.598 2.5 2.5 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233s.008-2.388.046-3.231c.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92s.546-.453.92-.598c.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92m-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217m0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334" />
                                        </svg>
                                    </a>
                                    <a href="https://linkedin.com/in/username" target="_blank" class="text-dark" style="transition: transform 0.3s ease, color 0.3s ease;" onmouseover="this.style.transform='scale(1.15)'; this.style.color='#666';" onmouseout="this.style.transform='scale(1)'; this.style.color='#000';" onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1.15)'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
                                            <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854zm4.943 12.248V6.169H2.542v7.225zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248S2.4 3.226 2.4 3.934c0 .694.521 1.248 1.327 1.248zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016l.016-.025V6.169h-2.4c.30.678 0 7.225 0 7.225z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card rounded-3 mb-3">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                        <div class="gallery-item">
                                <img src="<?= base_url('files/gallery/gallery_dja.png'); ?>" class="img-fluid rounded" alt="team" style="width: 100%; height: 200px; object-fit: cover;">
                                <div class="gallery-overlay">
                                    <h4>Foto Terakhir</h4>
                                    <p>Momen foto bersama di depan gedung DJA.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="gallery-item">
                                <img src="<?= base_url('files/gallery/gallery_meet.png'); ?>" class="img-fluid rounded" alt="meeting" style="width: 100%; height: 200px; object-fit: cover;">
                                <div class="gallery-overlay">
                                    <h4>Diskusi Tim</h4>
                                    <p>Kolaborasi dalam sesi brainstorming projek.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="gallery-item">
                                <img src="<?= base_url('files/gallery/gallery_farewell.png'); ?>" class="img-fluid rounded" alt="farewell photo" style="width: 100%; height: 200px; object-fit: cover;">
                                <div class="gallery-overlay">
                                    <h4>Foto Perpisahan</h4>
                                    <p>Momen saat acara perpisahan dengan mentor dan rekan kerja.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="gallery-item">
                                <img src="<?= base_url('files/gallery/gallery_lunch.png'); ?>" class="img-fluid rounded" alt="lunch" style="width: 100%; height: 200px; object-fit: cover;">
                                <div class="gallery-overlay">
                                    <h4>Makan Siang</h4>
                                    <p>Makan pecel ayam bersama-sama.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
        
        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: var(--tblr-border-radius);
            cursor: pointer;
        }

        .gallery-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            color: white;
            backdrop-filter: blur(1px);
            -webkit-backdrop-filter: blur(1px);
            

            opacity: 0;
            transition: opacity 0.4s ease;

            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 20px;
        }

        .gallery-overlay h4, .gallery-overlay p {
            transform: translateY(10px);
            opacity: 0;
            transition: opacity 0.3s ease-out, transform 0.3s ease-out;
        }
        
        .gallery-overlay p {
            transition-delay: 0.1s;
        }

        .gallery-item:hover img {
            transform: scale(1.1); 
        }

        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }

        .gallery-item:hover .gallery-overlay h4,
        .gallery-item:hover .gallery-overlay p {
            transform: translateY(0);
            opacity: 1;
        }

    </style>