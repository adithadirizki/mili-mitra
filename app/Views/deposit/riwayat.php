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
                            <input type="date" name="tgl_awal" class="form-control" id="tgl-awal" placeholder="Tgl Mulai" required>
                        </div>
                        <div class="d-inline-block form-group ml-2">
                            <label for="tgl-akhir">Tanggal Akhir</label>
                            <input type="date" name="tgl_akhir" class="form-control" id="tgl-akhir" placeholder="Tgl Akhir" required>
                        </div>
                        <div class="d-inline-block ml-2">
                            <label for="status">Status</label>
                            <select class="form-control" name="status" id="status">
                                <option value="">-- Semua --</option>
                                <option value="1">Sukses</option>
                                <option value="2">Dibatalkan</option>
                                <option value="0">Menunggu Pembayaran</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                </form>
                <div class="table-responsive">
                    <div id="loader" style="display: none;">
                        <div class="spinner-border text-primary"></div>
                    </div>
                    <table id="table_riwayat_deposit" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Bank</th>
                                <th>Jumlah</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Bank</th>
                                <th>Jumlah</th>
                                <th>Keterangan</th>
                                <th>Status</th>
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
        const tableRiwayatDeposit = $("#table_riwayat_deposit").on('processing.dt', function(e, settings, processing) {
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
                url: "<?= base_url('/riwayat-deposit') ?>",
                type: "post",
                dataType: "json",
                data: function(data) {
                    data.filter = {
                        tgl_awal: $("#tgl-awal").val() === "" ? null : $("#tgl-awal").val(),
                        tgl_akhir: $("#tgl-akhir").val() === "" ? null : $("#tgl-akhir").val(),
                        status: $("#status").val() === "" ? null : $("#status").val(),
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
                    data: "bank",
                    searchable: false
                },
                {
                    data: "jumlah_deposit",
                    mRender: function (jumlah_deposit) {
                        return Number(jumlah_deposit).toLocaleString('id-ID');
                    },
                    searchable: false
                },
                {
                    data: "keterangan",
                    searchable: false
                },
                {
                    data: "status",
                    mRender: function(status) {
                        const text = {
                            1: 'Sukses',
                            2: 'Dibatalkan',
                            0: 'Menunggu Pembayaran'
                        };
                        const color = {
                            1: 'success',
                            2: 'danger',
                            0: 'warning'
                        };
                        return `<div class="badge badge-${color[status]}">${text[status]}</div>`;
                    },
                    searchable: false
                }
            ]
        });

        $(document).on('submit', '#filter', (e) => {
            e.preventDefault();
            tableRiwayatDeposit.ajax.reload();
            return false;
        })
    })
</script>
<?= $this->endSection() ?>