<?php
namespace App\Models\Produto;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\TenantModels;
use App\Models\Empresa\Empresa;
class Produto extends Model
{
	use TenantModels;

	public function produtoCategoria(){
		return $this->belongsTo(ProdutoCategoria::class);
	}
	public function fornecedor(){
		return $this->belongsTo(Empresa::class);
	}
}
