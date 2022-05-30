<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= env('app.name') ?> | <?= $title ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url('dist/css/adminlte.min.css') ?>">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <h1><b><?= env('app.name') ?></b></h1>
            </div>
            <div class="card-body">
                <p class="login-box-msg">Silahkan login untuk masuk ke dashboard</p>

                <?php
                $session = session();
                $errors = $session->getFlashdata("errors");
                $message = $session->getFlashdata("message");
                ?>
                <?php if ($message) : ?>
                    <div class="alert alert-<?= $message['status'] ? "success" : "danger" ?> alert-dismissible" role="alert">
                        <?php if ($message['status']) : ?>
                            <i class="fa fa-check mr-2"></i>
                        <?php else : ?>
                            <i class="fa fa-times mr-2"></i>
                        <?php endif; ?>
                        <?= $message['text'] ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('/auth/login') ?>" method="post">
                    <div class="form-group mb-3">
                        <label for="userid">User ID</label>
                        <input type="text" name="userid" id="userid" class="form-control" placeholder="User ID" autofocus required>
                        <small class="text-danger"><?= $errors['userid'] ?? null ?></small>
                    </div>
                    <div class="form-group mb-3">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                        <?= csrf_field() ?>
                        <small class="text-danger"><?= $errors['password'] ?? null ?></small>
                    </div>
                    <div class="row">
                        <div class="col-8">
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="<?= base_url('plugins/jquery/jquery.min.js') ?>"></script>
    <!-- Bootstrap -->
    <script src="<?= base_url('plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

</body>

</html>