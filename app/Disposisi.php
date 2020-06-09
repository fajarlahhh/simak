<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Disposisi extends Model
{
    //
    protected $table = 'disposisi';
    protected $primaryKey = 'disposisi_id';

	public function surat_masuk()
	{
		return $this->belongsTo('App\SuratMasuk', 'disposisi_surat_id', 'surat_masuk_id');
	}

	public function detail()
	{
		return $this->belongsTo('App\DisposisiDetail', 'disposisi_id', 'disposisi_id');
	}
}