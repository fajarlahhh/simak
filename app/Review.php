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
}
