<?php
namespace App\Models\Compra;
use Illuminate\Database\Eloquent\Model;
use App\Models\Empresa\Empresa;
use App\Models\Produto\Produto;
use App\Models\Produto\UnidadeMedida;
class CompraItem extends Model
{
    public function produto(){
        return $this->belongsTo(Produto::class);
    }
    public function unidadeMedida(){
        return $this->belongsTo(UnidadeMedida::class);
    }
}