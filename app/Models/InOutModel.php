<?php

namespace App\Models;

use CodeIgniter\Model;

class InOutModel extends Model
{
    public function __construct()
    {
        $this->db = db_connect();
        $this->agenid = session()->agen_id;
        $this->level = session()->agen_level;
    }

    public function totalInOut($filter)
    {
        return $this->db->query("
        SELECT COUNT(tanggal) total_message FROM
        (
            SELECT in_starttime tanggal FROM inbox_read 
            WHERE agenid = '{$this->agenid}' AND DATE_FORMAT(in_starttime, '%Y-%m-%d') BETWEEN '{$this->db->escapeString($filter['tgl_awal'])}' 
            AND '{$this->db->escapeString($filter['tgl_akhir'])}'
            UNION ALL 
            SELECT out_starttime tanggal FROM outbox_read
            WHERE agenid = '{$this->agenid}' AND DATE_FORMAT(out_starttime, '%Y-%m-%d') BETWEEN '{$this->db->escapeString($filter['tgl_awal'])}' 
            AND '{$this->db->escapeString($filter['tgl_akhir'])}'
        ) AS message
        ")->getFirstRow()->total_message;
    }

    public function totalInOutFiltered($filter, $search)
    {
        $tipeIn = $search['tipe'] !== "" ? "AND 'In' = '{$this->db->escapeString($search['tipe'])}'" : null;
        $tipeOut = $search['tipe'] !== "" ? "AND 'Out' = '{$this->db->escapeString($search['tipe'])}'" : null;

        return $this->db->query("
        SELECT COUNT(tanggal) total_message FROM
        (
            SELECT in_starttime tanggal FROM inbox_read 
            WHERE agenid = '{$this->agenid}' AND in_message LIKE '%{$this->db->escapeLikeString($search['pesan'])}%' 
            $tipeIn
            AND DATE_FORMAT(in_starttime, '%Y-%m-%d') BETWEEN '{$this->db->escapeString($filter['tgl_awal'])}' 
            AND '{$this->db->escapeString($filter['tgl_akhir'])}'
            UNION ALL 
            SELECT out_starttime tanggal FROM outbox_read
            WHERE agenid = '{$this->agenid}' AND out_message LIKE '%{$this->db->escapeLikeString($search['pesan'])}%' 
            $tipeOut
            AND DATE_FORMAT(out_starttime, '%Y-%m-%d') BETWEEN '{$this->db->escapeString($filter['tgl_awal'])}' 
            AND '{$this->db->escapeString($filter['tgl_akhir'])}'
        ) AS message
        ")->getFirstRow()->total_message;
    }

    public function dataInOut($filter, $search, $offset)
    {
        $tipeIn = $search['tipe'] !== "" ? "AND 'In' = '{$this->db->escapeString($search['tipe'])}'" : null;
        $tipeOut = $search['tipe'] !== "" ? "AND 'Out' = '{$this->db->escapeString($search['tipe'])}'" : null;

        return $this->db->query("
        SELECT * FROM
        (
            SELECT in_starttime tanggal, 'In' tipe, in_message pesan FROM inbox_read 
            WHERE agenid = '{$this->agenid}' AND in_message LIKE '%{$this->db->escapeLikeString($search['pesan'])}%' 
            $tipeIn
            AND DATE_FORMAT(in_starttime, '%Y-%m-%d') BETWEEN '{$this->db->escapeString($filter['tgl_awal'])}' 
            AND '{$this->db->escapeString($filter['tgl_akhir'])}'
            UNION ALL 
            SELECT out_starttime tanggal, 'Out' tipe, out_message pesan FROM outbox_read
            WHERE agenid = '{$this->agenid}' AND out_message LIKE '%{$this->db->escapeLikeString($search['pesan'])}%' 
            $tipeOut
            AND DATE_FORMAT(out_starttime, '%Y-%m-%d') BETWEEN '{$this->db->escapeString($filter['tgl_awal'])}' 
            AND '{$this->db->escapeString($filter['tgl_akhir'])}'
        ) AS message
        ORDER BY tanggal DESC
        LIMIT $offset, 25
        ")->getResult();
    }
}
