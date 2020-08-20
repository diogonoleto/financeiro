<?php
namespace App\Models\Financeiro;
use Illuminate\Database\Eloquent\Model;
class FinDre extends Model
{
	public function parent() {
		return $this->belongsTo(FinDre::class, 'dre_id');
	}
	public function children() {
		return $this->hasMany(FinDre::class, 'dre_id');
	}
}
