<?php

namespace App\Controllers;

use App\Models\DepositModel;
use App\Models\SettingModel;

class Deposit extends BaseController
{
    public function history()
    {
        $method = $this->request->getMethod();

        if ($method === "get") {
            $data = [
                "title" => "Riwayat Deposit",
                "nav_active" => "history-deposit",
            ];
            return view('deposit/riwayat', $data);
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
                ],
                'filter.status' => [
                    'label' => 'Status',
                    'rules' => 'permit_empty|in_list[0,1,2]',
                    'errors' => [
                        'in_list' => '{field} tidak valid.'
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

            $deposittModel = new DepositModel();
            $data_deposit = $deposittModel->dataDeposit($filter);
            $csrf_name = csrf_token();

            $data = [
                "data" => $data_deposit,
                "$csrf_name" => csrf_hash()
            ];

            return json_encode($data);
        }
    }

    public function topup()
    {
        $method = $this->request->getMethod();

        if ($method === "get") {
            $depositModel = new DepositModel();
            $deposit = $depositModel->findCurrentDeposit();

            $settingModel = new SettingModel();
            $bank = $settingModel->getBank();
            $banks = [
                [
                    "bank_name" => "Bank Central Asia (BCA)",
                    "account_number" => $bank->NoRekBCA,
                    "account_name" => $bank->NamaBCA
                ],
                [
                    "bank_name" => "Bank Negara Indonesia (BNI)",
                    "account_number" => $bank->NoRekBNI,
                    "account_name" => $bank->NamaBNI
                ],
                [
                    "bank_name" => "Bank Rakyat Indonesia (BRI)",
                    "account_number" => $bank->NoRekBRI,
                    "account_name" => $bank->NamaBRI
                ],
                [
                    "bank_name" => "Bank Mandiri Persero",
                    "account_number" => $bank->NoRekMANDIRI,
                    "account_name" => $bank->NamaMANDIRI
                ],
            ];

            $data = [
                "title" => "Tiket Deposit",
                "nav_active" => "deposit",
                "banks" => $banks,
                "deposit" => $deposit
            ];
            return view('deposit/topup', $data);
        } elseif ($method === "post") {
            $isValidRules = $this->validate([
                "amount" => [
                    "label" => "Jumlah",
                    "rules" => "required|integer|greater_than_equal_to[50000]|less_than_equal_to[25000000]",
                    "errors" => [
                        "required" => "{field} harus diisi.",
                        "integer" => "{field} harus bilangan bulat.",
                        "greater_than_equal_to" => "{field} harus lebih besar dari atau sama dengan Rp 50.000.",
                        "less_than_equal_to" => "{field} harus lebih kecil dari atau sama dengan Rp 25.000.000.",
                    ]
                ]
            ]);

            $session = session();

            if (!$isValidRules) {
                $session->setFlashdata('errors', $this->validator->getErrors());
                return redirect()->back();
            }

            $amount = $this->request->getPost('amount') + mt_rand(101, 999);
            // $notes = "(TEST) Silahkan transfer sejumlah Rp. " . number_format($amount) . " , mohon perhatikan sampai 3 digit terakhir.";
            $notes = "Silahkan transfer sejumlah Rp. ".number_format($amount)." , mohon perhatikan sampai 3 digit terakhir.";
            $data = [
                "agenid" => $session->agen_id,
                "bank" => "TIKET",
                "jmldep" => $amount,
                "tanggal" => date('Y-m-d H:i:s'),
                "catatan" => $notes
            ];

            $depositModel = new DepositModel();
            $isSuccess = $depositModel->insert($data, false);

            if (!$isSuccess) {
                $session->setFlashdata('message', [
                    "status" => false,
                    "text" => "Tiket deposit gagal dibuat."
                ]);
                return redirect()->back();
            }

            return redirect()->back();
        }
    }
}
