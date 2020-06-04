<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class EdaranLampiran extends Model
{
    //
    use LogsActivity;

    protected $table = 'edaran_lampiran';
    protected $primaryKey = 'file';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'edaran_nomor', 'file'
    ];
}
