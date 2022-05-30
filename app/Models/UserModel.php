<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'stockiest';
    protected $primaryKey = 'hp';
    protected $returnType    = 'object';
    protected $allowedFields = ['password', 'pin'];

    public function __construct()
    {
        $this->agenid = session()->agen_id;
    }

    public function findUser($hp, $pin)
    {
        $no = $hp * 1;
        $this->groupStart();
        $this->where('hp', $hp);
        $this->orWhere('hp', $no);
        $this->groupEnd();
        $this->where('pin', $pin);
        return $this->get()->getFirstRow();
    }

    public function getUser()
    {
        $this->where('agenid', $this->agenid);
        $this->limit(1);
        return $this->get()->getFirstRow();
    }

    public function getBalance()
    {
        $this->select('balance');
        $this->where('agenid', $this->agenid);
        $this->limit(1);
        return $this->get()->getFirstRow()->balance;
    }

    public function updatePassword($password)
    {
        $this->set('password', $password);
        $this->where('agenid', $this->agenid);
        $this->limit(1);
        return $this->update();
    }

    public function updatePIN($pin)
    {
        $this->set('pin', $pin);
        $this->where('agenid', $this->agenid);
        $this->limit(1);
        return $this->update();
    }

    public function totalDownline()
    {
        $this->selectCount('hp', 'total_downline');
        $this->where('upline', $this->agenid);
        $this->where('upline <> agenid');
        $this->limit(1);
        return $this->get()->getFirstRow()->total_downline;
    }

    public function totalDownlineFiltered($search)
    {
        $this->selectCount('hp', 'total_downline');
        $this->groupStart();
        $this->where('upline', $this->agenid);
        $this->where('upline <> agenid');
        $this->groupEnd();
        $search['nama'] === "" ? null :
            $this->like('nama', $search['nama']);
        $this->limit(1);
        return $this->get()->getFirstRow()->total_downline;
    }

    public function dataDownline($search, $offset)
    {
        $this->select('nama, status');
        $this->groupStart();
        $this->where('upline', $this->agenid);
        $this->where('upline <> agenid');
        $this->groupEnd();
        $search['nama'] === "" ? null :
            $this->like('nama', $search['nama']);
        $this->orderBy('nama', 'asc');
        $this->limit(25, $offset);
        return $this->get()->getResult();
    }
}
