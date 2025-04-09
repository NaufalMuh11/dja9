<!doctype html>
<html lang="en">
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
<meta http-equiv="X-UA-Compatible" content="ie=edge" />
<meta http-equiv="refresh" content="18000">
<title>DATA: Dashboard Analitika & Telaah Anggaran</title>

<?php $this->load->view('main/link'); ?>
<body>
    <div class="wrapper">
        <?php $this->load->view('main/header'); 
        if (!isset($view)) $view = $bread['view'];
        $this->load->view($view);
        ?>
    </div>
</body>
</html>