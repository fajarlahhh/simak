<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'jabatan';
    protected $primaryKey = 'jabatan_id';

	public function pengguna()
	{
		return $this->belongsTo('App\Jabatan', 'jabatan_id', 'jabatan_id');
	}
}
