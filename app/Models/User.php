<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo',
        'type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $timestamps=false; 

    public function adminlte_image()
    {
        return asset('storage/users/'.Auth::user()->photo);
    }

    public function adminlte_desc()
    {   
        $type=['','Administrador','Gestão','Operação','Comercialização','Júridico','Cliente'];
        return 'Tipo Acesso: '.$type[Auth::user()->type];
    }

    public function adminlte_profile_url()
    {
        return 'myProfile';
    }


}
