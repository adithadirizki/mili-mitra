<?php

namespace App\Controllers;

use App\Models\MutasiModel;

class Mutasi extends BaseController
{
    public function index()
    {
        $method = $this->request->getMethod();

        if ($method === "get") {
            $data = [
                "title" => "Mutasi",
                "nav_active" => "mutasi",
            ];
            return view('mutasi', $data);
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

            $filter = $_POST['filter'];

            $search = [];
            $columns = $_POST['columns'];
            foreach ($columns as $value) {
                if ($value['searchable'] === "true")
                    $search[$value['data']] = $value['search']['value'];
            }

            $mutasitModel = new MutasiModel();
            $data_mutasi = $mutasitModel->dataMutasi($filter);
            $csrf_name = csrf_token();

            $data = [
                "data" => $data_mutasi,
                "$csrf_name" => csrf_hash()
            ];

            return json_encode($data);
        }
    }
}
