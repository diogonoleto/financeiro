<?php
namespace App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\TenantModels;
use App\Models\Config\SisPermissao;
use App\Models\Config\SisRegra;
use App\Models\Empresa\Empresa;
use App\Models\Config\Banco\SisBanco;
class User extends Model
{
	use TenantModels;
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
    public function roles(){
        return $this->belongsToMany(SisRegra::class);
    }
    public function bancos() {
        return $this->belongsTo(SisBanco::class);
    }
    public function contatos() {
        return $this->hasMany(UserContato::class)->orderBy("tipo_contato");
    }
    public function enderecos() {
        return $this->hasMany(UserEndereco::class)->orderBy("tipo_endereco");
    }
    public function contas() {
        return $this->hasMany(UserConta::class)->orderBy("tipo_conta");
    }
    public function maisInfo() {
        return $this->hasOne(UserMaisInfo::class);
    }
}
