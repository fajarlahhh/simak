<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermintaaBarangDetail extends Model
{
    //
    protected $table = 'permintaan_barang_detail';
    protected $primaryKey = ['pb_nomor','pb_detail_barang'];
    public $incrementing = false;
    protected $keyType = 'string';
}
