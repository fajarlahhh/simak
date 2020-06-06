<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Opd extends Model
{
    //
    protected $table = 'opd';
    protected $primaryKey = 'opd_nama';
    public $incrementing = false;
    protected $keyType = 'string';
}
