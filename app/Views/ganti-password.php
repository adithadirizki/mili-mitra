<?= $this->extend('template/layout') ?>

<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?= $title ?></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active"><?= $title ?></li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

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

    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-outline card-primary">
                <div class="card-body">
                    <form action="<?= base_url('/ganti-password') ?>" method="POST">
                        <div class="form-group row">
                            <label for="old_password" class="col-sm-4 col-form-label">Password Lama <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" name="old_password" id="old_password" required>
                                <small class="text-danger"><?= $errors['old_password'] ?? null ?></small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="new_password" class="col-sm-4 col-form-label">Password Baru <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" name="new_password" id="new_password" minlength="6" required>
                                <small class="text-danger"><?= $errors['new_password'] ?? null ?></small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="confirm_password" class="col-sm-4 col-form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" name="confirm_password" id="confirm_password" minlength="6" required>
                                <small class="text-danger"><?= $errors['confirm_password'] ?? null ?></small>
                            </div>
                        </div>

                        <button class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-outline card-primary">
                <div class="card-body">
                    <form action="<?= base_url('/ganti-pin') ?>" method="POST">
                        <div class="form-group row">
                            <label for="old_pin" class="col-sm-4 col-form-label">PIN Lama <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" name="old_pin" id="old_pin" required>
                                <small class="text-danger"><?= $errors['old_pin'] ?? null ?></small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="new_pin" class="col-sm-4 col-form-label">PIN Baru <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" name="new_pin" id="new_pin" minlength="4" maxlength="4" required>
                                <small class="text-danger"><?= $errors['new_pin'] ?? null ?></small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="confirm_pin" class="col-sm-4 col-form-label">Konfirmasi PIN <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input type="password" class="form-control" name="confirm_pin" id="confirm_pin" minlength="4" maxlength="4" required>
                                <small class="text-danger"><?= $errors['confirm_pin'] ?? null ?></small>
                            </div>
                        </div>

                        <button class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--/. container-fluid -->
</section>
<!-- /.content -->
<?= $this->endSection() ?>