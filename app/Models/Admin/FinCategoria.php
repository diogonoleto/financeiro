<?php
namespace App\Models\Admin;
use Illuminate\Database\Eloquent\Model;
class FinCategoria extends Model
{
	public function parent() {
		return $this->belongsTo(FinCategoria::class, 'categoria_id');
	}
	public function children() {
	    return $this->hasMany(FinCategoria::class, 'categoria_id')->whereNull("deleted_at");
	}

}
