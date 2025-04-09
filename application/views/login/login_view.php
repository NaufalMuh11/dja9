<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Login - DATA</title>
    <link href="<?= base_url('./assets/dist/css/tabler.css'); ?>" rel="stylesheet" />

</head>

<body class="d-flex flex-column">
    <div class="page page-center">
        <div class="container container-tight py-4">

            <div class="card card-md">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <a href="." class="navbar-brand navbar-brand-autodark">
                            <img src="./files/images/data.png" height="36" alt="">
                        </a>
                    </div>
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= $this->session->flashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    <form action="<?= base_url('login/process') ?>" method="post" autocomplete="off">
                        <div class="mb-3">
                            <label class="form-label">User ID</label>
                            <input type="text" name="username" class="form-control" placeholder="User ID" autocomplete="off" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">
                                Password
                            </label>
                            <div class="input-group input-group-flat">
                                <input type="password" name="password" class="form-control" placeholder="Password" autocomplete="off" required>
                            </div>
                        </div>
                        <div class="mb-2"></div>
                        <div class="form-footer mb-4">
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</body>

</html>