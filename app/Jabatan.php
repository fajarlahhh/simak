<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'jabatan';
    protected $primaryKey = 'jabatan_nama';
    public $incrementing = false;
    protected $keyType = 'string';

	public function pengguna()
	{
		return $this->belongsTo('App\Jabatan', 'jabatan_nama', 'jabatan_nama');
	}

	public function atasan()
	{
		return $this->belongsTo('App\Jabatan', 'jabatan_parent', 'jabatan_nama');
	}
}
