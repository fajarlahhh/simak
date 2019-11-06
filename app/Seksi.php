<?php

namespace Akunting;

use Illuminate\Database\Eloquent\Model;

class Seksi extends Model
{
    protected $table = 'personalia.seksi';
    protected $primaryKey = 'kd_seksi';
    public $incrementing = false;

    public function bagian()
    {
    	return $this->belongsTo('Akunting\Bagian', 'kd_bagian', 'kd_bagian');
    }
}
