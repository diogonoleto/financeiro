<?php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\TenantScope;
trait TenantModels
{
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new TenantScope);
        static::creating(function(Model $model){
            $aId = Auth()->user()->sis_conta_id;
            $model->sis_conta_id = $aId;
        });
    }
}