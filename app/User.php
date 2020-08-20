<?php
namespace App;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Notifications\Notifiable;
use App\Models\Config\SisPermissao;
use App\Models\Config\SisRegra;
use App\Models\User\UserContato;
use App\Models\Empresa\Empresa;

class User extends Authenticatable
{

	use Notifiable;
    protected $fillable = [
        'empresa_id', 'name', 'email', 'img', 'cargo', 'deleted_at', 'status'
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];

	public function regra(){
        return $this->belongsToMany(SisRegra::class);
    }

    public function hasPermissao(SisPermissao $permissao)
    {
        return $this->hasAnyRegras($permissao->regra);
    }

    public function hasAnyRegras($regra)
    {
        if( is_array($regra) || is_object($regra))
        {
            return !! $regra->intersect($this->regra)->count();
        }
        return $this->regra->contains("nome", $regra);
    }

    public function isAdmin()
    {
        if($this->hasAnyRegras("0_administrador"))
        {
            return true;
        }
        return false;
    }

    public function regraUser()
    {
        return $this->belongsToMany(SisRegra::class);
    }

    public function userContato()
    {
        return $this->hasMany(UserContato::class);
    }
}