<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa\Empresa;
use App\Models\Admin\SisModulo;
class SisConta extends Model
{
	// public function sisconta(){
	// 	return $this->belongsToMany(Empresa::class);
	// }
	protected $fillable = [
        'deleted_at', 'status'
    ];

	public function modulo(){
		return $this->belongsToMany(SisModulo::class);
	}
	public function user(){
		return $this->belongsTo(User::class);
	}

	public function checkAccess($idmodulo)
	{
		return $this->join('sis_vendas', 'sis_vendas.conta_id', 'sis_contas.id')
					->where('sis_vendas.conta_id', Auth()->user()->sis_conta_id)
					->where('sis_vendas.modulo_id', $idmodulo)
					->where('sis_vendas.status', 'aprovado');
	}
}
