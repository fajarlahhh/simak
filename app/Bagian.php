<?php

namespace Akunting;

use Illuminate\Database\Eloquent\Model;

class Bagian extends Model
{
    protected $table = 'personalia.bagian';
    protected $primaryKey = 'kd_bagian';
    public $incrementing = false;

    public function seksi()
    {
    	return $this->hasMany('Akunting\Seksi', 'kd_bagian', 'kd_bagian');
    }
}
