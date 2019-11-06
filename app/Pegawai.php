<?php

namespace Akunting;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'personalia.pegawai';
    protected $primaryKey = ['id', 'nip'];
    public $incrementing = false;

    public function pengguna(){
    	return $this->belongsTo('Akunting\Pengguna', 'pegawai_id', 'id');
	}

	public function jabatan()
	{
		return $this->hasOne('Akunting\Jabatan', 'kd_jabatan', 'kd_jabatan');
	}

	public function unit()
	{
		return $this->belongsTo('Akunting\Unit', 'kd_unit', 'kd_unit');
	}

	public function bagian()
	{
		return $this->belongsTo('Akunting\Bagian', 'kd_bagian', 'kd_bagian');
	}

	public function seksi()
	{
		return $this->belongsTo('Akunting\Seksi', 'kd_seksi', 'kd_seksi');
	}
}
