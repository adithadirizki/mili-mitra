<?php

namespace App\Controllers;

use App\Models\OperatorModel;
use App\Models\TransaksiModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class Transaksi extends BaseController
{
    public function index()
    {
        $method = $this->request->getMethod();

        if ($method === "get") {
            $operatorModel = new OperatorModel();

            $data = [
                "title" => "Transaksi",
                "nav_active" => "transaction",
                "operator" => $operatorModel->getOperator()
            ];
            return view('transaksi', $data);
        } elseif ($method === "post") {
            $isValidRules = $this->validate([
                'filter.tgl_awal' => [
                    'label' => 'Tanggal Awal',
                    'rules' => 'required|valid_date[Y-m-d]',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'valid_date' => 'Format {field} tidak valid.'
                    ]
                ],
                'filter.tgl_akhir' => [
                    'label' => 'Tanggal Akhir',
                    'rules' => 'required|valid_date[Y-m-d]',
                    'errors' => [
                        'required' => '{field} harus diisi.',
                        'valid_date' => 'Format {field} tidak valid.'
                    ]
                ]
            ]);

            $filter = $_POST['filter'];
            $tgl_awal = $filter['tgl_awal'];
            $tgl_akhir = $filter['tgl_akhir'];

            $max_start = date('Y-m-d', strtotime('-30 day'));
            $max_end = date('Y-m-d');

            if ($tgl_awal < $max_start || $tgl_awal > $max_end) {
                $this->validator->setError('filter.tgl_awal', 'Tanggal tidak valid.');
                $isValidRules = false;
            }
            if ($tgl_akhir < $max_start || $tgl_akhir > $max_end) {
                $this->validator->setError('filter.tgl_akhir', 'Tanggal tidak valid.');
                $isValidRules = false;
            }

            if (!$isValidRules) {
                return json_encode(['errors' => $this->validator->getErrors()]);
            }

            $search = [];
            $columns = $_POST['columns'];
            foreach ($columns as $value) {
                if ($value['searchable'] === "true")
                    $search[$value['data']] = $value['search']['value'];
            }   

            $transaksiModel = new TransaksiModel();
            $data_transaksi = $transaksiModel->dataTransaction($filter, $search);
            $csrf_name = csrf_token();

            $data = [
                "data" => $data_transaksi,
                "$csrf_name" => csrf_hash()
            ];

            return json_encode($data);
        }
    }

    public function export()
    {
        $search['id'] = $this->request->getPost('id');
        $search['kode_produk'] = $this->request->getPost('kode_produk');
        $search['operator'] = $this->request->getPost('operator');
        $search['tujuan'] = $this->request->getPost('tujuan');
        $search['status'] = $this->request->getPost('status');
        $filter['tgl_awal'] = $this->request->getPost('tgl_awal');
        $filter['tgl_akhir'] = $this->request->getPost('tgl_akhir');

        $transaksiModel = new TransaksiModel();
        $transaksi = $transaksiModel->dataTransaction($filter, $search);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Tgl Transaksi');
        $sheet->setCellValue('B1', 'ID Transaksi');
        $sheet->setCellValue('C1', 'Kode Produk');
        $sheet->setCellValue('D1', 'Operator');
        $sheet->setCellValue('E1', 'No Pelanggan');
        $sheet->setCellValue('F1', 'Status');
        $sheet->setCellValue('G1', 'SN / Keterangan');
        $sheet->setCellValue('H1', 'Harga');
        $sheet->setCellValue('I1', 'Ref');

        foreach ($transaksi as $key => $value) {
            $sheet->setCellValue('A' . ($key + 2), $value->tanggal);
            $sheet->setCellValue('B' . ($key + 2), $value->id);
            $sheet->setCellValue('C' . ($key + 2), $value->kode_produk);
            $sheet->setCellValue('D' . ($key + 2), $value->operator);
            $sheet->setCellValue('E' . ($key + 2), "'" . $value->tujuan);
            $sheet->setCellValue('F' . ($key + 2), $value->status);
            $sheet->setCellValue('G' . ($key + 2), $value->sn . ';');
            $sheet->setCellValue('H' . ($key + 2), $value->harga);
            $sheet->setCellValue('I' . ($key + 2), $value->ref);
        }

        $filename = $filter['tgl_awal'] . '_' . $filter['tgl_akhir'] . '.xls';
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new Xls($spreadsheet);
        $writer->save('php://output');
    }
}
