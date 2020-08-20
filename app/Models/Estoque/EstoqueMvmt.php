<?php
namespace App\Models\Estoque;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\TenantModels;

class EstoqueMvmt extends Model
{
	use TenantModels;
}