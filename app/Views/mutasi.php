<?= $this->extend('template/layout') ?>

<?= $this->section('header') ?>
<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<style>
    #loader {
        position: absolute;
        width: 100%;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
<?= $this->endSection() ?>

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
        <div class="card">
            <div class="card-body">
                <?php
                $session = session();
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
                <form id="filter" class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="d-inline-block form-group">
                            <label for="tgl-awal">Tanggal Awal</label>
                            <input type="date" name="tgl_awal" class="form-control" id="tgl-awal" value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d', strtotime('-30 day')) ?>" max="<?= date('Y-m-d') ?>" placeholder="Tgl Mulai" required>
                        </div>
                        <div class="d-inline-block form-group ml-2">
                            <label for="tgl-akhir">Tanggal Akhir</label>
                            <input type="date" name="tgl_akhir" class="form-control" id="tgl-akhir" value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d', strtotime('-30 day')) ?>" max="<?= date('Y-m-d') ?>" placeholder="Tgl Akhir" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                </form>
                <div class="table-responsive">
                    <div id="loader" style="display: none;">
                        <div class="spinner-border text-primary"></div>
                    </div>
                    <table id="table_mutasi" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Ket</th>
                                <th>Debet</th>
                                <th>Kredit</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Ket</th>
                                <th>Debet</th>
                                <th>Kredit</th>
                                <th>Balance</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    <!--/. container-fluid -->
</section>
<!-- /.content -->
<?= $this->endSection() ?>

<?= $this->section('footer') ?>
<!-- DataTables -->
<script src="<?= base_url('plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>

<script>
    $(document).ready(function() {
        let csrf_name = '<?= csrf_token() ?>';
        let csrf_token = '<?= csrf_hash() ?>';
        const tableMutasi = $("#table_mutasi").on('processing.dt', function(e, settings, processing) {
            processing ? $('#loader').show() : $('#loader').hide();
        }).DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            lengthChange: false,
            ordering: false,
            paging: false,
            info: false,
            deferLoading: 0,
            ajax: {
                url: "<?= base_url('/mutasi') ?>",
                type: "post",
                dataType: "json",
                data: function(data) {
                    data.filter = {
                        tgl_awal: $("#tgl-awal").val() === "" ? null : $("#tgl-awal").val(),
                        tgl_akhir: $("#tgl-akhir").val() === "" ? null : $("#tgl-akhir").val()
                    }
                    data[csrf_name] = csrf_token;
                },
                dataSrc: function (result) {
                    csrf_token = result[csrf_name];
                    return result.data;
                }
            },
            dom: '<"mb-4"<"dt-action-buttons"B>><"d-flex justify-content-between align-items-center mx-1 row"<"col-sm-12 col-md-6"l>>t<"d-flex justify-content-between mx-1 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            columns: [{
                    data: null,
                    mRender: function(data, row, type, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    searchable: false
                },
                {
                    data: "tanggal",
                    searchable: false
                },
                {
                    data: "keterangan",
                    searchable: false
                },
                {
                    data: "debet",
                    mRender: function(debet) {
                        return `<span class="font-weight-bold">${Number(debet).toLocaleString('id-ID')}</span>`;
                    },
                    className: 'bg-success',
                    searchable: false
                },
                {
                    data: "kredit",
                    mRender: function(kredit) {
                        return `<span class="font-weight-bold">${Number(kredit).toLocaleString('id-ID')}</span>`;
                    },
                    className: 'bg-primary',
                    searchable: false
                },
                {
                    data: "balance",
                    mRender: function(balance) {
                        return `<span class="font-weight-bold">${Number(balance).toLocaleString('id-ID')}</span>`;
                    },
                    searchable: false
                }
            ]
        });

        $(document).on('submit', '#filter', (e) => {
            e.preventDefault();
            tableMutasi.ajax.reload();
            return false;
        })
    })
</script>
<?= $this->endSection() ?>