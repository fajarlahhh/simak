<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Undangan extends Model
{
    //
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'undangan';
    protected $primaryKey = 'undangan_nomor';
    public $incrementing = false;
    protected $keyType = 'string';

    public function lampiran()
    {
        return $this->hasMany('App\UndanganLampiran', 'undangan_nomor', 'undangan_nomor');
    }

    public function bidang()
    {
		return $this->belongsTo('App\Bidang', 'bidang_id', 'bidang_id');
    }

    public function review()
    {
        return $this->hasMany('App\Review', 'review_surat_nomor', 'undangan_nomor');
    }

    public function harus_revisi()
    {
        return $this->hasOne('App\Review', 'review_surat_nomor', 'undangan_nomor')->where('fix', 1)->where('selesai', 0);
    }

    public function belum_review()
    {
        return $this->hasOne('App\Review', 'review_surat_nomor', 'undangan_nomor')->whereNull('fix');
    }

    public function delete()
    {
        $this->review()->delete();
        return parent::delete();
    }
}
