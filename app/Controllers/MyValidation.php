<?php

namespace App\Controllers;

class MyValidation
{
    public function is_alnum($string)
    {
        return !(ctype_alpha($string) || is_numeric($string));
    }
}
