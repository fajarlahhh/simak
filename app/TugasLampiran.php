<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class TugasLampiran extends Model
{
    //
    use LogsActivity;

    protected $table = 'tugas_lampiran';
    protected $primaryKey = 'file';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'tugas_nomor', 'file'
    ];
}
