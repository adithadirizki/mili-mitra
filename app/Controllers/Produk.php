<?php

namespace App\Controllers;

use App\Models\OperatorModel;
use App\Models\ProdukModel;

class Produk extends BaseController
{
    public function index()
    {
        $method = $this->request->getMethod();

        if ($method === "get") {
            $operatorModel = new OperatorModel();

            $data = [
                "title" => "Produk",
                "nav_active" => "product",
                "operator" => $operatorModel->getOperator()
            ];
            return view('produk', $data);
        } elseif ($method === "post") {
            $offset = $_POST['start'];

            $search = [];
            $columns = $_POST['columns'];
            foreach ($columns as $value) {
                if ($value['searchable'] === "true")
                    $search[$value['data']] = $value['search']['value'];
            }

            $productModel = new ProdukModel();
            $total_product = $productModel->totalProduct();
            $total_product_filtered = $productModel->totalProductFiltered($search);
            $data_product = $productModel->dataProduct($search, $offset);
            $csrf_name = csrf_token();

            $data = [
                "recordsTotal" => $total_product,
                "recordsFiltered" => $total_product_filtered,
                "data" => $data_product,
                "$csrf_name" => csrf_hash()
            ];

            return json_encode($data);
        }
    }
}
