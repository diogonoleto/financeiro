<?php

namespace App\Models\Financeiro;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\TenantModels;
class FinCentroCusto extends Model
{
    use TenantModels;
}
