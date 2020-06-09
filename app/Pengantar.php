<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengantar extends Model
{
    //
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'pengantar';
    protected $primaryKey = 'pengantar_nomor';
    public $incrementing = false;
    protected $keyType = 'string';

    public function lampiran()
    {
        return $this->hasMany('App\PengantarLampiran', 'pengantar_nomor', 'pengantar_nomor');
    }

    public function bidang()
    {
		return $this->belongsTo('App\Bidang', 'bidang_id', 'bidang_id');
    }

    public function review()
    {
        return $this->hasMany('App\Review', 'review_surat_nomor', 'pengantar_nomor');
    }

    public function harus_revisi()
    {
        return $this->hasOne('App\Review', 'review_surat_nomor', 'pengantar_nomor')->where('fix', 1)->where('selesai', 0);
    }

    public function belum_review()
    {
        return $this->hasOne('App\Review', 'review_surat_nomor', 'pengantar_nomor')->whereNull('fix');
    }
}
