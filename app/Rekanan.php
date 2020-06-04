<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rekanan extends Model
{
    //
    protected $table = 'rekanan';
    protected $primaryKey = 'rekanan_nama';
    public $incrementing = false;
    protected $keyType = 'string';
}
