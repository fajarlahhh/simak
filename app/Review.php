<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    //

    protected $table = 'review';
    protected $primaryKey = ['review_surat_nomor', 'review_nomor'];
    public $incrementing = false;
    protected $keyType = 'string';

	public function pengguna()
	{
		return $this->belongsTo('App\Pengguna', 'operator', 'pengguna_id');
	}

	public function edaran()
	{
		return $this->belongsTo('App\Edaran', 'review_surat_nomor', 'edaran_nomor');
	}

	public function pengantar()
	{
		return $this->belongsTo('App\Pengantar', 'review_surat_nomor', 'pengantar_nomor');
	}

	public function tugas()
	{
		return $this->belongsTo('App\Tugas', 'review_surat_nomor', 'tugas_nomor');
	}

	public function undangan()
	{
		return $this->belongsTo('App\Undangan', 'review_surat_nomor', 'undangan_nomor');
	}

	public function jabatan()
	{
		return $this->belongsTo('App\Jabatan', 'jabatan_id', 'jabatan_id');
	}
}
