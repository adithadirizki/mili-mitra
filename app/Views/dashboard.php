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

        <!-- Info boxes -->
        <div class="row">
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Transaksi Berhasil Hari Ini</span>
                        <span class="info-box-number">
                            <?= $data['transaction']['today']['success']->total ?>
                            (<?= number_to_currency($data['transaction']['today']['success']->sum, 'IDR', 'id-ID') ?>)
                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-shopping-cart"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Transaksi Refund Hari Ini</span>
                        <span class="info-box-number">
                            <?= $data['transaction']['today']['refund']->total ?>
                            (<?= number_to_currency($data['transaction']['today']['refund']->sum, 'IDR', 'id-ID') ?>)
                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-shopping-cart"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Transaksi Gagal Hari Ini</span>
                        <span class="info-box-number">
                            <?= $data['transaction']['today']['failed']->total ?>
                            (<?= number_to_currency($data['transaction']['today']['failed']->sum, 'IDR', 'id-ID') ?>)
                        </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="card card-primary card-outline card-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="false">1 Minggu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="true">1 Bulan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-three-messages-tab" data-toggle="pill" href="#custom-tabs-three-messages" role="tab" aria-controls="custom-tabs-three-messages" aria-selected="false">1 Tahun</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-three-tabContent">
                    <div class="tab-pane fade active show" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
                        <div class="chart">
                            <h3>Total Transaksi: <strong><?= array_sum($data['transaction']['oneWeek']['total']) ?></strong></h3>
                            <canvas id="total-trx-one-week" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                        <div class="chart mt-4">
                            <h3>Nilai Transaksi: <strong><?= number_to_currency(array_sum($data['transaction']['oneWeek']['sum']), 'IDR', 'id-ID') ?></strong></h3>
                            <canvas id="sum-trx-one-week" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
                        <div class="chart">
                            <h3>Total Transaksi: <strong><?= array_sum($data['transaction']['oneMonth']['total']) ?></strong></h3>
                            <canvas id="total-trx-one-month" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                        <div class="chart mt-4">
                            <h3>Nilai Transaksi: <strong><?= number_to_currency(array_sum($data['transaction']['oneMonth']['sum']), 'IDR', 'id-ID') ?></strong></h3>
                            <canvas id="sum-trx-one-month" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-three-messages" role="tabpanel" aria-labelledby="custom-tabs-three-messages-tab">
                        <div class="chart">
                            <h3>Total Transaksi: <strong><?= array_sum($data['transaction']['oneYear']['total']) ?></strong></h3>
                            <canvas id="total-trx-one-year" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                        <div class="chart mt-4">
                            <h3>Nilai Transaksi: <strong><?= number_to_currency(array_sum($data['transaction']['oneYear']['sum']), 'IDR', 'id-ID') ?></strong></h3>
                            <canvas id="sum-trx-one-year" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!--/. container-fluid -->
</section>
<!-- /.content -->
<?= $this->endSection() ?>

<?= $this->section('footer') ?>
<!-- ChartJS -->
<script src="<?= base_url('plugins/chart.js/Chart.min.js') ?>"></script>

<script>
    $(document).ready(function() {
        // week
        var totalTrxWeekCanvas = $('#total-trx-one-week').get(0).getContext('2d');
        var sumTrxWeekCanvas = $('#sum-trx-one-week').get(0).getContext('2d');
        // month
        var totalTrxMonthCanvas = $('#total-trx-one-month').get(0).getContext('2d');
        var sumTrxMonthCanvas = $('#sum-trx-one-month').get(0).getContext('2d');
        // year
        var totalTrxYearCanvas = $('#total-trx-one-year').get(0).getContext('2d');
        var sumTrxYearCanvas = $('#sum-trx-one-year').get(0).getContext('2d');

        // week
        <?php 
        $days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        $daysofweek = [];
        foreach ($data['transaction']['oneWeek']['daysofweek'] as $value) {
            $daysofweek[] = $days[$value];
        }
        ?>
        var totalTrxWeekData = {
            labels: <?= json_encode($daysofweek) ?>,
            datasets: [{
                label: 'Total TRX',
                data: <?= json_encode($data['transaction']['oneWeek']['total']) ?>,
                borderColor: '#ff0000',
                tension: 0
            }]
        }
        var sumTrxWeekData = {
            labels: <?= json_encode($daysofweek) ?>,
            datasets: [{
                label: 'Nilai TRX',
                data: <?= json_encode($data['transaction']['oneWeek']['sum']) ?>,
                borderColor: '#000fff',
                tension: 0
            }]
        }
        // month
        var totalTrxMonthData = {
            labels: <?= json_encode($data['transaction']['oneMonth']['dates']) ?>,
            datasets: [{
                label: 'Total TRX',
                data: <?= json_encode($data['transaction']['oneMonth']['total']) ?>,
                borderColor: '#ff0000',
                tension: 0
            }]
        }
        var sumTrxMonthData = {
            labels: <?= json_encode($data['transaction']['oneMonth']['dates']) ?>,
            datasets: [{
                label: 'Nilai TRX',
                data: <?= json_encode($data['transaction']['oneMonth']['sum']) ?>,
                borderColor: '#000fff',
                tension: 0
            }]
        }
        // year
        var totalTrxYearData = {
            labels: <?= json_encode($data['transaction']['oneYear']['months']) ?>,
            datasets: [{
                label: 'Total TRX',
                data: <?= json_encode($data['transaction']['oneYear']['total']) ?>,
                borderColor: '#ff0000',
                tension: 0
            }]
        }
        var sumTrxYearData = {
            labels: <?= json_encode($data['transaction']['oneYear']['months']) ?>,
            datasets: [{
                label: 'Nilai TRX',
                data: <?= json_encode($data['transaction']['oneYear']['sum']) ?>,
                borderColor: '#000fff',
                tension: 0
            }]
        }

        // week
        var totalTrxWeek = new Chart(totalTrxWeekCanvas, {
            type: 'line',
            data: totalTrxWeekData,
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        })
        var sumTrxWeek = new Chart(sumTrxWeekCanvas, {
            type: 'line',
            data: sumTrxWeekData,
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        })
        // month
        var totalTrxMonth = new Chart(totalTrxMonthCanvas, {
            type: 'line',
            data: totalTrxMonthData,
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        })
        var sumTrxMonth = new Chart(sumTrxMonthCanvas, {
            type: 'line',
            data: sumTrxMonthData,
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        })
        // year
        var totalTrxYear = new Chart(totalTrxYearCanvas, {
            type: 'line',
            data: totalTrxYearData,
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        })
        var sumTrxYear = new Chart(sumTrxYearCanvas, {
            type: 'line',
            data: sumTrxYearData,
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        })
    })
</script>
<?= $this->endSection() ?>