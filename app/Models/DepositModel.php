<?php

namespace App\Models;

use CodeIgniter\Model;

class DepositModel extends Model
{
    protected $table      = 'deposit';
    protected $primaryKey = 'id';
    protected $returnType    = 'object';
    protected $allowedFields = ['agenid', 'bank', 'jmldep', 'tanggal', 'catatan'];

    public function __construct()
    {
        $this->agenid = session()->agen_id;
        $this->level = session()->agen_level;
    }

    public function dataDeposit($filter)
    {
        $tgl_awal = $filter['tgl_awal'];
        $tgl_akhir = $filter['tgl_akhir'];

        $this->select("id, tanggal, bank, jmldep jumlah_deposit, catatan keterangan, status");
        $this->groupStart();
        $this->where('agenid', $this->agenid);
        $this->groupEnd();
        $filter['status'] === "" ? null : $this->where("status", $filter['status']);
        if ($tgl_awal && $tgl_akhir)
            $this->where("DATE_FORMAT(tanggal, '%Y-%m-%d') BETWEEN '{$tgl_awal}' AND '{$tgl_akhir}'");
        $this->orderBy("id", "desc");
        return $this->get()->getResult();
    }

    public function findCurrentDeposit()
    {
        $this->where('agenid', $this->agenid);
        $this->where('status', 0);
        $this->where('bank', 'TIKET');
        $this->orderBy('tanggal', 'desc');
        $this->limit(1);
        return $this->get()->getFirstRow();
    }
}
