<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Revisi extends Model
{
    //

    protected $table = 'revisi';
    protected $primaryKey = ['nomor_surat', 'revisi_nomor'];
    public $incrementing = false;
    protected $keyType = 'string';
}
