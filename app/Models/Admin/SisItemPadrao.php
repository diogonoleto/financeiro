<?php
	namespace App\Models\Admin;
	use Illuminate\Database\Eloquent\Model;
	class SisItemPadrao extends Model
	{
		public function parent() {
			return $this->belongsTo(SisItemPadrao::class, 'filho');
		}
		public function children() {
		    return $this->hasMany(SisItemPadrao::class, 'filho');
		}
	}
