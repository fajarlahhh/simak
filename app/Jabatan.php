<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'personalia.jabatan';
    protected $primaryKey = 'kd_jabatan';
    public $incrementing = false;
}
