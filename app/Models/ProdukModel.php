<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    protected $table      = 'voucher';
    protected $primaryKey = 'vtype';
    protected $returnType    = 'object';
    protected $allowedFields = [];  

    public function __construct() {
        $this->level = session()->agen_level;
    }
    
    public function totalProduct()
    {
        $this->join('operator', 'operator.opr = voucher.opr');
        $this->selectCount("vtype", "total_product");
        $this->limit(1);
        return $this->get()->getFirstRow()->total_product;
    }
    
    public function totalProductFiltered($search)
    {
        $this->join('operator', 'operator.opr = voucher.opr');
        $this->selectCount("vtype", "total_product");
        $search['kode_produk'] === "" ? null :
            $this->like("vtype", $search['kode_produk']);
        $search['nama_produk'] === "" ? null :
            $this->like("ket", $search['nama_produk']);
        $search['operator'] === "" ? null :
            $this->like("voucher.opr", $search['operator']);
        $search['status'] === "" ? null :
            $this->like("voucher.status", $search['status']);
        $this->limit(1);
        return $this->get()->getFirstRow()->total_product;
    }
    
    public function dataProduct($search, $offset)
    {
        $this->join('operator', 'operator.opr = voucher.opr');
        $this->select("vtype kode_produk, ket nama_produk, voucher.opr operator, harga{$this->level} harga, voucher.status status");
        $search['kode_produk'] === "" ? null :
            $this->like("vtype", $search['kode_produk']);
        $search['nama_produk'] === "" ? null :
            $this->like("ket", $search['nama_produk']);
        $search['operator'] === "" ? null :
            $this->like("voucher.opr", $search['operator']);
        $search['status'] === "" ? null :
            $this->like("voucher.status", $search['status']);
        $this->orderBy("vtype", "asc");
        $this->limit(25, $offset);
        return $this->get()->getResult();
    }
}
