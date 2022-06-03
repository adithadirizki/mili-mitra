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
        helper('number');
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

        <div class="row">
            <div class="col-md-6">
                <div class="card card-outline card-primary">
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-7 mb-4">
                                <h4>Transfer Bank</h4>

                                <?php foreach ($banks as $bank) : ?>
                                    <div class="font-weight-bold"><?= $bank['bank_name'] ?></div>
                                    <div><?= $bank['account_number'] ?> a.n <?= $bank['account_name'] ?></div>
                                <?php endforeach; ?>

                                <p class="text-danger mt-4 mb-0">
                                    Minimal deposit: Rp 50.000
                                    <br>
                                    Maksimal deposit: Rp 25.000.000
                                </p>
                            </div>
                            <div class="col-md-5">
                                <form action="<?= base_url('/deposit') ?>" method="POST">
                                    <div class="form-group">
                                        <label for="amount">Jumlah (Rp)</label>
                                        <input type="number" name="amount" id="amount" class="form-control" placeholder="Rp" min="50000" max="25000000" required>
                                        <?= csrf_field() ?>
                                        <small class="text-danger"><?= $errors['amount'] ?? null ?></small>
                                    </div>
                                    <button class="btn btn-primary btn-block">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-outline card-primary">
                    <!-- /.card-header -->
                    <div class="card-body">
                        <h4>Tiket Deposit</h4>

                        <?php if ($deposit) : ?>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>Jumlah yang harus dibayar: </td>
                                        <td><strong class="h5"><?= number_to_currency($deposit->jmldep, 'IDR', 'id-ID') ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal tiket deposit: </td>
                                        <td><?= $deposit->tanggal ?></td>
                                    </tr>
                                    <tr>
                                        <td>Keterangan: </td>
                                        <td><?= $deposit->catatan ?></td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="text-danger p-3">Note: Pastikan jumlah yang ditransfer sesuai dengan tiket yang didapatkan.</div>
                        <?php else : ?>
                            <div class="h5 text-center">Tiket Kosong</div>
                        <?php endif; ?>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
    <!--/. container-fluid -->
</section>
<!-- /.content -->
<?= $this->endSection() ?>