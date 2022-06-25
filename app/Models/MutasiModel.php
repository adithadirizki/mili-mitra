<?php

namespace App\Models;

use CodeIgniter\Model;

class MutasiModel extends Model
{
    protected $table      = 'mutasi';
    protected $primaryKey = 'id';
    protected $returnType    = 'object';
    protected $allowedFields = [];

    public function __construct()
    {
        $this->agenid = session()->agen_id;
        $this->level = session()->agen_level;
    }

    public function dataMutasi($filter)
    {
        $tgl_awal = $filter['tgl_awal'];
        $tgl_akhir = $filter['tgl_akhir'];

        $this->select("id, tanggal, ket keterangan, debet, kredit, currbalance balance");
        $this->groupStart();
        $this->where('agenid', $this->agenid);
        $this->groupEnd();
        if ($tgl_awal && $tgl_akhir)
            $this->where("tanggal BETWEEN '{$tgl_awal}' AND '{$tgl_akhir}'");
        $this->orderBy("id", "desc");
        return $this->get()->getResult();
    }
}
