<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bidang extends Model
{
    //
    protected $table = 'bidang';
    protected $primaryKey = 'bidang_id';

	public function jabatan()
	{
		return $this->hasMany('App\Jabatan', 'bidang_id', 'bidang_id');
	}
}
