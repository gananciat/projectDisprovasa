<?php

namespace App;

use App\Models\Person;
use App\Models\Rol;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    const USUARIO_VERIFICADO = 'VERIFICADO';
    const USUARIO_NO_VERIFICADO = 'NO VERIFICADO';

    const USUARIO_ADMINISTRADOR = 'ADMINISTRADOR';
    const USUARIO_REGULAR = 'REGULAR';

    protected $table = 'users';

    protected $fillable = ['email', 'verified', 'verification_token', 'admin', 'people_id', 'rols_id'];

    protected $hidden = [
        'password', 'remember_token', 'verification_token'
    ];
    
    protected $casts = [];

    public function setEmailAttribute($valor){
        $this->attributes['email'] = mb_strtoupper($valor);
    }

    public function setPasswordAttribute($valor){
        $this->attributes['password'] = bcrypt($valor);
    }    

    public function esVerificado()
    {
        return $this->verified == User::USUARIO_VERIFICADO;
    }

    public function esAdministrador()
    {
        return $this->verified == User::USUARIO_ADMINISTRADOR;
    }

    public static function generarVerificationToken()
    {
        return Str::random(40);
    }   
    
    public function people()
    {
        return $this->hasOne(Person::class);
    }

    public function rol()
    {
        return $this->hasOne(Rol::class);
    }
}
