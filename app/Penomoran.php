<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Penomoran extends Model
{
    //
    protected $table = 'penomoran';
    protected $primaryKey = 'penomoran_jenis';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'penomoran_jenis', 'penomoran_format', 'operator', 'created_at', 'updated_at'
    ];
}
