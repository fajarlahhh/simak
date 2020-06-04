<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    //
    protected $table = 'bidang';
    protected $primaryKey = 'bidang_nama';
    public $incrementing = false;
    protected $keyType = 'string';
}
