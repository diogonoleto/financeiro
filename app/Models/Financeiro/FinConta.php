<?php
namespace App\Models\Financeiro;
use Illuminate\Database\Eloquent\Model;
use App\Models\Config\Banco\SisBanco;
use App\Models\Config\Financeiro\FinContaTipo;
use App\Scopes\TenantModels;
class FinConta extends Model
{
	use TenantModels;
    public function banco() {
		return $this->belongsTo(SisBanco::class);
	}

	public function ContaTipo() {
		return $this->belongsTo(FinContaTipo::class);
	}
}
