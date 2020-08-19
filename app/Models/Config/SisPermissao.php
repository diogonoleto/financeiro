<?php
namespace App\Models\Config;
use Illuminate\Database\Eloquent\Model;
use DB;
class SisPermissao extends Model
{
   	public function regra()
    {
        return $this->belongsToMany(SisRegra::class);
    }

    public function parent() {
		return $this->belongsTo(SisPermissao::class, 'sis_modulo_id');
	}

	public function area() {
	    return $this->hasMany(SisPermissao::class, 'sis_modulo_id', 'sis_modulo_id')->select(DB::raw('sis_permissaos.area, sis_permissaos.descricao, sis_permissaos.sis_modulo_id, COUNT(area) as qtde'))
                            ->join('sis_conta_sis_modulo', 'sis_conta_sis_modulo.sis_modulo_id', '=', 'sis_permissaos.sis_modulo_id')
                            ->join('sis_modulos', 'sis_modulos.id', '=', 'sis_permissaos.sis_modulo_id')
                            ->groupBy('sis_permissaos.sis_modulo_id', 'sis_permissaos.area', 'sis_permissaos.descricao')
                            ->where('sis_conta_sis_modulo.sis_conta_id', Auth()->user()->sis_conta_id)
                            ->with('children');
	}

	public function children() {
	    return $this->hasMany(SisPermissao::class, 'area', 'area')->orderBy('nome');
	}


}
