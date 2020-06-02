<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Edaran extends Model
{
    //
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'edaran';
    protected $primaryKey = 'edaran_nomor';
    public $incrementing = false;
    protected $keyType = 'string';
}
