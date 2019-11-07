<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'personalia.unit';
    protected $primaryKey = 'kd_unit';
    public $incrementing = false;
}
