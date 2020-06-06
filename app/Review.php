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
		return $this->hasOne('App\Edaran', 'edaran_nomor', 'review_nomor_surat');
	}

	public function pengantar()
	{
		return $this->hasOne('App\Pengantar', 'pengantar_nomor', 'review_nomor_surat');
	}

	public function tugas()
	{
		return $this->hasOne('App\Tugas', 'tugas_nomor', 'review_nomor_surat');
	}

	public function undangan()
	{
		return $this->hasOne('App\Undangan', 'undangan_nomor', 'review_nomor_surat');
	}

	public function jabatan()
	{
		return $this->hasOne('App\Jabatan', 'jabatan_id', 'jabatan_id');
	}
}
