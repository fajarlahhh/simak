<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DisposisiDetail extends Model
{
    //

    protected $table = 'disposisi_detail';
    protected $primaryKey = ['disposisi_id', 'jabatan_id'];
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'disposisi_id', 'jabatan_id'
    ];
    public $timestamps = false;

	public function jabatan()
	{
		return $this->belongsTo('App\Jabatan', 'jabatan_id', 'jabatan_id');
	}
}
