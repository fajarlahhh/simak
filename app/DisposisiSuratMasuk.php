<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DisposisiSuratMasuk extends Model
{
    //
    protected $table = 'disposisi_surat_masuk';
    protected $primaryKey = 'disposisi_surat_masuk_id';

    public function detail()
    {
        $this->hasMany('App\DisposisiSuratMasukDetail', 'disposisi_surat_masuk_id', 'disposisi_surat_masuk_id');
    }
}
