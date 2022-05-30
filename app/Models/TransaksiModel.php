<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table      = 'log_purchase';
    protected $primaryKey = 'id';
    protected $returnType    = 'object';
    protected $allowedFields = [];

    public function __construct()
    {
        $this->agenid = session()->agen_id;
    }

    public function getStatisticTransactionToday()
    {
        $this->select('status, COUNT(id) total, SUM(harga) sum');
        $this->where('agenid', $this->agenid);
        $this->where("DATE_FORMAT(tanggal, '%Y-%m-%d') = DATE(NOW())");
        $this->groupBy('status');
        return $this->get()->getResult();
    }

    public function getTransactionOneWeek()
    {
        $this->select("COUNT(id) total, SUM(harga) sum, DAYOFWEEK(tanggal) dayofweek, tanggal");
        $this->where('agenid', $this->agenid);
        $this->where("DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN DATE_ADD(CURDATE(), INTERVAL -6 DAY) AND CURDATE()");
        $this->groupBy("DAYOFWEEK(tanggal)");
        return $this->get()->getResult();
    }

    public function getTransactionOneMonth()
    {
        $this->select("COUNT(id) total, SUM(harga) sum, DATE_FORMAT(tanggal, '%Y-%m-%d') date, tanggal");
        $this->where('agenid', $this->agenid);
        $this->where("DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN DATE_ADD(CURDATE(), INTERVAL -29 DAY) AND CURDATE()");
        $this->groupBy("DATE_FORMAT(tanggal, '%Y-%m-%d')");
        return $this->get()->getResult();
    }

    public function getTransactionOneYear()
    {
        $this->select("COUNT(id) total, SUM(harga) sum, DATE_FORMAT(tanggal, '%Y-%m') month, tanggal");
        $this->where('agenid', $this->agenid);
        $this->where("DATE_FORMAT(tanggal, '%Y-%m') BETWEEN DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL -11 MONTH), '%Y-%m') AND DATE_FORMAT(CURDATE(), '%Y-%m')");
        $this->groupBy("DATE_FORMAT(tanggal, '%Y-%m')");
        return $this->get()->getResult();
    }

    public function dataTransaction($filter, $search)
    {
        $tgl_awal = $filter['tgl_awal'];
        $tgl_akhir = $filter['tgl_akhir'];

        $this->join('voucher', 'voucher.vtype = log_purchase.vtype');
        $this->select("tanggal, id, voucher.vtype kode_produk, voucher.opr operator, tujuan, harga, log_purchase.status, vsn sn, ref");
        $this->groupStart();
        $this->where('agenid', $this->agenid);
        $this->groupEnd();
        $search['id'] === "" ? null :
            $this->where("id", $search['id']);
        $search['kode_produk'] === "" ? null :
            $this->where("voucher.vtype", $search['kode_produk']);
        $search['operator'] === "" ? null :
            $this->where("voucher.opr", $search['operator']);
        $search['tujuan'] === "" ? null :
            $this->where("tujuan", $search['tujuan']);
        $search['status'] === "" ? null :
            $this->where("log_purchase.status", $search['status']);
        if ($tgl_awal && $tgl_akhir)
            $this->where("DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '{$tgl_awal}' AND '{$tgl_akhir}'");
        $this->orderBy("id", "desc");
        return $this->get()->getResult();
    }
}
