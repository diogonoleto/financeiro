<?php
namespace App\Models\Produto;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\TenantModels;
class ProdutoCategoria extends Model
{
	use TenantModels;

	protected $fillable=[
		'nome', 'descricao','produto_categoria_id'
	];

	public function parent() {
		return $this->belongsTo(ProdutoCategoria::class, 'produto_categoria_id');
	}

	public function children() {
	    return $this->hasMany(ProdutoCategoria::class, 'produto_categoria_id');
	}
}
