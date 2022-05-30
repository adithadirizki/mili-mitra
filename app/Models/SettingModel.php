<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table      = 'setting';
    protected $primaryKey = 'id';
    protected $returnType    = 'object';
    protected $allowedFields = ['agenid', 'bank', 'jmldep', 'tanggal', 'catatan'];

    public function getBank()
    {
        $this->select('id, NoRekBCA, NoRekBNI, NoRekMANDIRI, NoRekBRI, NamaBCA, NamaBNI, NamaMANDIRI, NamaBRI');
        $this->limit(1);
        return $this->get()->getFirstRow();
    }
}
