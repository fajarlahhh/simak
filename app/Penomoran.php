<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Penomoran extends Model
{
    //
    protected $table = 'penomoran';
    protected $primaryKey = 'penomoran_jenis';
    public $incrementing = false;
    protected $keyType = 'string';
}
