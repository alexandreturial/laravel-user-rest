<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'TB_USUARIO';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id'
        ,'DS_NOME'
        , 'DS_EMAIL'
        , 'DS_SENHA',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'DS_SENHA', 'remember_token',
    ];

    public static function updatesenha($array)
    {
        $usuario = DB::table('TB_USUARIO')
            ->where('DS_EMAIL',$array['email'])
            ->update([
                'DS_SENHA' => hash::make($array['senha']),
            ]);

        return $array['senha'];
    }

    public function getAuthPassword()
    {
        return $this->DS_SENHA;
    }
}
