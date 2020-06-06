<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class UndanganLampiran extends Model
{
    //
    use LogsActivity;

    protected $table = 'undangan_lampiran';
    protected $primaryKey = 'file';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'undangan_nomor', 'file'
    ];
}
