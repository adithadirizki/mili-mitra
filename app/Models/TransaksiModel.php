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
        $time_awal = strtotime($tgl_awal);
        $time_akhir = strtotime($tgl_akhir);
        $ym_awal = date('ym', $time_awal);
        $ym_akhir = date('ym', $time_akhir);

        $queries = [];
        $db = \Config\Database::connect();

        while ($time_akhir >= $time_awal) {
            $ym_akhir = date('ym', $time_akhir);

            $builder = $db->table("log_purchase_$ym_akhir");
            $builder->select("tanggal, id, vtype, tujuan, harga, status, vsn, ref");
            $builder->where("tanggal between '$tgl_awal' and '$tgl_akhir'");

            $queries[] = $builder->getCompiledSelect();

            $time_akhir = strtotime('-1 month', $time_akhir);
        }

        $where = [];
        $search['id'] === "" ? null : $where[] = "id = '{$this->db->escapeString($search['id'])}'";
        $search['kode_produk'] === "" ? null : $where[] = "kode_produk = '{$this->db->escapeString($search['kode_produk'])}'";
        $search['operator'] === "" ? null : $where[] = "operator = '{$this->db->escapeString($search['operator'])}'";
        $search['tujuan'] === "" ? null : $where[] = "tujuan = '{$this->db->escapeString($search['tujuan'])}'";
        $search['status'] === "" ? null : $where[] = "status = '{$this->db->escapeString($search['status'])}'";
        
        if (count($where) > 0) {
            $where = implode(' and ', $where);
            $where = "where $where";
        } else {
            $where = "";
        }

        $union = implode(' union ', $queries);
        return $db->query("select tanggal, id, voucher.vtype kode_produk, voucher.opr operator, tujuan, harga, t1.status, vsn sn, ref from ($union) as t1 inner join voucher on voucher.vtype = t1.vtype $where order by tanggal desc")->getResult();
    }
}
