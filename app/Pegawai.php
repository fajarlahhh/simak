<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'personalia.pegawai';
    protected $primaryKey = 'nip';
    public $incrementing = false;
    protected $keyType = 'string';

    public function pengguna(){
    	return $this->belongsTo('App\Pengguna', 'pegawai_id', 'id');
	}

	public function jabatan()
	{
		return $this->hasOne('App\Jabatan', 'kd_jabatan', 'kd_jabatan');
	}

	public function unit()
	{
		return $this->belongsTo('App\Unit', 'kd_unit', 'kd_unit');
	}

	public function bagian()
	{
		return $this->belongsTo('App\Bagian', 'kd_bagian', 'kd_bagian');
	}

	public function seksi()
	{
		return $this->belongsTo('App\Seksi', 'kd_seksi', 'kd_seksi');
	}
}
