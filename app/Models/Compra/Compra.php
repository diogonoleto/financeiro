<?php
namespace App\Models\Compra;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\TenantModels;
use App\Models\Empresa\Empresa;
use App\Models\Compra\CompraItem;
class Compra extends Model
{
    use TenantModels;
    public function fornecedor(){
        return $this->belongsTo(Empresa::class);
    }

    public function compraItem(){
        return $this->hasMany(CompraItem::class);
    }
}