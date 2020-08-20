<?php
namespace App\Models\Produto;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\TenantModels;
class UnidadeMedidaConversao extends Model
{
	use TenantModels;
}
