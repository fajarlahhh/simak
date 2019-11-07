<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penyimpanan extends Model
{
    //
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'penyimpanan';
    protected $primaryKey = 'penyimpanan_id';
}
