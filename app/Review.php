<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    //

    protected $table = 'review';
    protected $primaryKey = ['review_nomor_surat', 'review_nomor'];
    public $incrementing = false;
    protected $keyType = 'string';

	public function edaran()
	{
		return $this->belongsTo('App\Edaran', 'edaran_nomor', 'review_nomor_surat');
	}

	public function pengantar()
	{
		return $this->belongsTo('App\Pengantar', 'pengantar_nomor', 'review_nomor_surat');
	}

	public function tugas()
	{
		return $this->belongsTo('App\Tugas', 'tugas_nomor', 'review_nomor_surat');
	}

	public function undangan()
	{
		return $this->belongsTo('App\Undangan', 'undangan_nomor', 'review_nomor_surat');
	}

	public function jabatan()
	{
		return $this->belongsTo('App\Jabatan', 'jabatan_id', 'jabatan_id');
	}
}
