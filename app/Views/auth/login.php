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
            <div class="text-center p-3">
                <img src="<?= base_url('dist/img/mili.jpg') ?>" alt="Mili" class="img-fluid">
                <small class="font-weight-bold text-center m-0">Jendela Pulsa Indonesia</small>
            </div>
            <div class="card-header text-center p-0">
                <h1 class="m-0"><b><?= env('app.name') ?></b></h1>
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
                        <label for="hp">HP</label>
                        <input type="text" name="hp" id="hp" class="form-control" placeholder="HP" autofocus required>
                        <small class="text-danger"><?= $errors['hp'] ?? null ?></small>
                    </div>
                    <div class="form-group mb-3">
                        <label for="pin">PIN</label>
                        <input type="password" name="pin" id="pin" class="form-control" placeholder="PIN" minlength="4" maxlength="4" required>
                        <?= csrf_field() ?>
                        <small class="text-danger"><?= $errors['pin'] ?? null ?></small>
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