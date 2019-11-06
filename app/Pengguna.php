<?php

namespace Akunting;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;

class Pengguna extends Authenticatable
{

    use Notifiable;
    use HasRoles;
    use LogsActivity;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'pengguna';
    protected $primaryKey = 'pengguna_nip';
    public $incrementing = false;
    protected $rememberTokenName = 'remember_token';
    protected $keyType = 'string';

    protected $fillable = [
        'pengguna_nip', 'pengguna_sandi'
    ];

    public function getAuthPassword()
    {
        return $this->pengguna_sandi;
    }

    public function pegawai(){
        return $this->hasOne('Akunting\Pegawai', 'nip', 'pengguna_nip');
	}
}
