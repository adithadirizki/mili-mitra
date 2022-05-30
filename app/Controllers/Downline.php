<?php

namespace App\Controllers;

use App\Models\UserModel;

class Downline extends BaseController
{
    public function index()
    {
        $method = $this->request->getMethod();

        if ($method === "get") {
            $data = [
                "title" => "Downline",
                "nav_active" => "downline"
            ];
            return view('downline', $data);
        } elseif ($method === "post") {
            $offset = $_POST['start'];

            $search = [];
            $columns = $_POST['columns'];
            foreach ($columns as $value) {
                if ($value['searchable'] === "true")
                    $search[$value['data']] = $value['search']['value'];
            }

            $userModel = new UserModel();
            $total_downline = $userModel->totalDownline();
            $total_downline_filtered = $userModel->totalDownlineFiltered($search);
            $data_downline = $userModel->dataDownline($search, $offset);
            $csrf_name = csrf_token();

            $data = [
                "recordsTotal" => $total_downline,
                "recordsFiltered" => $total_downline_filtered,
                "data" => $data_downline,
                "$csrf_name" => csrf_hash()
            ];

            return json_encode($data);
        }
    }
}
