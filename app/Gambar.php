<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gambar extends Model
{
    //
    protected $table = 'gambar';
    protected $primaryKey = 'gambar_nama';
    public $incrementing = false;
    protected $keyType = 'string';
}
