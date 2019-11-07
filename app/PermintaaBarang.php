<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermintaaBarang extends Model
{
    //
    protected $table = 'permintaan_barang';
    protected $primaryKey = 'pb_nomor';
    public $incrementing = false;
    protected $keyType = 'string';

    public function detail(){
        return $this->hasMany('App\PermintaaBarangDetail', 'pb_nomor', 'pb_nomor');
	}
}
