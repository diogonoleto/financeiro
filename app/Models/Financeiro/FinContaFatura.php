<?php

namespace App\Models\Financeiro;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\TenantModels;
class FinContaFatura extends Model
{
    use TenantModels;

    public function conta() {
		return $this->belongsTo(FinConta::class);
	}
}
