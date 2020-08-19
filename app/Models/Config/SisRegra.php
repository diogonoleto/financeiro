<?php
namespace App\Models\Config;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;
class SisRegra extends Model
{
	public function permissao()
	{
		return $this->belongsToMany(SisPermissao::class);
	}
	public function permissaoRegra()
	{
		return $this->belongsToMany(SisPermissao::class, 'sis_permissao_sis_regra', 'sis_regra_id');
	}
	public function user()
	{
		return $this->belongsToMany(User::class);
	}

}
