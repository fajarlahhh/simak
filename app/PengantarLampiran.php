<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class PengantarLampiran extends Model
{
    //
    use LogsActivity;

    protected $table = 'pengantar_lampiran';
    protected $primaryKey = 'file';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $fillable = [
        'pengantar_nomor', 'file'
    ];
}
