<?php
namespace App\Models\Financeiro;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\TenantModels;
use App\Models\Financeiro\FinMovimento;
use DB;
class FinCategoria extends Model
{
	use TenantModels;
	protected $fillable=[
		'nome', 'descricao','categoria_id','sis_conta_id', 'cod'
	];
	public function parent() {
		return $this->belongsTo(FinCategoria::class, 'categoria_id');
	}

	public function childrenfca() {
		return $this->hasMany(FinCategoria::class, 'categoria_id')->whereNotNull('categoria_id')->orderBy('ordem')->with('childrenfca');
	}

	public function childrendre() {
		return $this->hasMany(FinCategoria::class, 'categoria_id')
		->select(DB::raw('fin_categorias.id, fin_categorias.nome, fin_categorias.categoria_id, fin_categorias.tipo, fin_dres.descricao as descr'))
		->leftJoin('fin_dres', 'fin_dres.id', '=', 'fin_categorias.dre_id');
	}

	public function children() {
		return $this->hasMany(FinCategoria::class, 'categoria_id', 'id')->select(DB::raw('
			fin_categorias.id, fin_categorias.cod, fin_categorias.descricao as "desc", fin_categorias.categoria_id, fin_categorias.nome, fin_categorias.tipo, fin_dres.descricao'))
		->leftJoin('fin_dres', 'fin_dres.id', '=', 'fin_categorias.dre_id')
		->where('fin_categorias.nome', '!=' ,'Saldo Inicial')
		->where('fin_categorias.nome', '!=' ,'Transferência de Saída')
		->where('fin_categorias.nome', '!=' ,'Transferência de Entrada')
		->where('fin_categorias.nome', '!=' ,'Pagamento de Fatura')
		->orderBy('fin_categorias.tipo', 'ASC')
		->orderBy('fin_categorias.id', 'ASC')
		->orderBy('fin_categorias.nome', 'ASC')
		->whereNull("fin_categorias.deleted_at")
		->with('children');
	}
}
