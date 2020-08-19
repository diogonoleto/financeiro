<?php
namespace App\Models\Empresa;
use Illuminate\Database\Eloquent\Model;
use App\Models\Config\Banco\SisBanco;
use App\Scopes\TenantModels;
use App\User;
class Empresa extends Model
{
  use TenantModels;
	public function bancos() {
		return $this->belongsTo(SisBanco::class);
	}
	public function contatos() {
		return $this->hasMany(EmpresaContato::class)->orderBy("tipo_contato");
	}
	public function enderecos() {
		return $this->hasMany(EmpresaEndereco::class)->orderBy("tipo_endereco");
	}
	public function contas() {
		return $this->hasMany(EmpresaConta::class)->orderBy("tipo_conta");
	}
	public function maisInfo() {
		return $this->hasOne(EmpresaMaisInfo::class);
	}
}