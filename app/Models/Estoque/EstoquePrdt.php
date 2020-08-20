<?php
namespace App\Models\Estoque;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\TenantModels;

class EstoquePrdt extends Model
{
	use TenantModels;
}