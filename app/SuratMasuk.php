<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class SuratMasuk extends Model
{
    //
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'surat_masuk';
    protected $primaryKey = 'surat_masuk_id';

    public function disposisi()
    {
        return $this->hasMany('App\Disposisi', 'disposisi_surat_id', 'surat_masuk_id');
    }
}
