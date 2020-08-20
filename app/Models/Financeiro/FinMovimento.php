<?php
namespace App\Models\Financeiro;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa\Empresa;
use App\Scopes\TenantModels;
use Carbon\Carbon;
class FinMovimento extends Model
{
	use TenantModels;
	protected $fillable = [
        'movimento_id', 'empresa_id', 'categoria_id', 'conta_id', 'user_id','fornecedor_id','importacao_id', 'conta_fatura_id', 'descricao', 'observacao', 'valor', 'desconto', 'valor_recebido', 'juro', 'data_baixa', 'centro_custo_id', 'data_vencimento', 'data_emissao', 'recorrencia', 'num_doc', 'flag_pontual'
    ];
    public function empresa(){
		return $this->belongsTo(Empresa::class);
	}
	public function conta(){
		return $this->belongsTo(FinConta::class);
	}
	public function categoria(){
		return $this->belongsTo(FinCategoria::class);
	}
	public function fornecedor(){
		return $this->belongsTo(Empresa::class, 'fornecedor_id');
	}
	public function centroCusto(){
		return $this->belongsTo(FinCentroCusto::class);
	}

	// public function getDataVencimentoAttribute($value)
	// {
	// 	return  Carbon::parse($value)->format('d/m/Y');
	// }

}