<?php

namespace App\Models\PDV;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\TenantModels;
use App\Models\Config\PDV\Plataforma;

class PontoDeVenda extends Model
{
	use TenantModels;

	// protected $fillable=[
	// 	'nome', 'responsavel','plataforma_id'
	// ];

	public function plataforma() {
		return $this->belongsTo(Plataforma::class);
	}
}
