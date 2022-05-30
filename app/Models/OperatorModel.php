<?php

namespace App\Models;

use CodeIgniter\Model;

class OperatorModel extends Model
{
    protected $table      = 'operator';
    protected $primaryKey = 'opr';
    protected $returnType    = 'object';
    protected $allowedFields = [];

    public function getOperator()
    {
        $this->select('opr operator');
        return $this->get()->getResult();
    }
}
