<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'jabatan';
    protected $primaryKey = 'jabatan_id';

	public function pengguna()
	{
		return $this->hasMany('App\Pengguna', 'jabatan_id', 'jabatan_id');
	}

	public function bidang()
	{
		return $this->belongsTo('App\Bidang', 'bidang_id', 'bidang_id');
	}

	public function atasan()
	{
		return $this->hasOne('App\Jabatan', 'jabatan_id', 'jabatan_parent');
	}

	public function review()
	{
		return $this->hasMany('App\Review', 'jabatan_id', 'jabatan_parent');
	}
}
