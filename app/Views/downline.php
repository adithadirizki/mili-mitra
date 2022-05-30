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
                <button type="button" id="filter" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                <div class="table-responsive">
                    <div id="loader" style="display: none;">
                        <div class="spinner-border text-primary"></div>
                    </div>
                    <table id="table_downline" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
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
        const thead = $("#table_downline thead")

        // tambah tr pada thead untuk filter
        thead.prepend('<tr class="filter"></tr>');

        const tr = thead.find('tr');
        const th = tr.eq(1).find('th');

        th.each((index, el) => {
            const title = $(el).text();

            if (index === 0) {
                // hide column No dan Harga
                tr.eq(0).append(`<th><input type="hidden" /></th>`);
            } else {
                // input filter
                tr.eq(0).append(`<th><input type="text" class="form-control" placeholder="${title}" /></th>`);
            }
        })

        const tableDownline = $("#table_downline").on('processing.dt', function(e, settings, processing) {
            processing ? $('#loader').show() : $('#loader').hide();
        }).DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            lengthChange: false,
            ordering: false,
            pageLength: 25,
            ajax: {
                url: "<?= base_url('/downline') ?>",
                type: "post",
                dataType: "json"
            },
            dom: '<"mb-4"<"dt-action-buttons"B>><"d-flex justify-content-between align-items-center mx-1 row"<"col-sm-12 col-md-6"l>>t<"d-flex justify-content-between mx-1 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            columns: [{
                    data: null,
                    mRender: function(data, row, type, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {
                    data: "nama"
                }
            ],
            initComplete: function() {
                var api = this.api();

                // For each column
                api
                    .columns()
                    .eq(0)
                    .each(function(colIdx) {
                        thead.find('input, select').eq(colIdx)
                            .on('keyup change', function(e) {
                                e.stopPropagation();

                                // Search the column for that value
                                api
                                    .column(colIdx)
                                    .search(this.value);
                            });
                    });
            }
        });

        $(document).on('click', '#filter', (e) => {
            e.preventDefault();
            tableDownline.ajax.reload();
        })
    })
</script>
<?= $this->endSection() ?>