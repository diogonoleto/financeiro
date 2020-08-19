<?php
namespace App\Providers;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Config\SisPermissao;
use Carbon\Carbon;
use App\User;
class AuthServiceProvider extends ServiceProvider
{
  public function boot(GateContract $gate)
  {
    Carbon::setLocale('pt_BR');
    setlocale(LC_TIME, 'Portuguese');

    $this->registerPolicies($gate);
    
    $permissaos = SisPermissao::all();
    foreach ($permissaos as $permissao)
    {
      $gate->define($permissao->nome, function(User $user) use ($permissao){
        return $user->hasPermissao($permissao);
      });
    }
    
    $gate->before(function(User $user, $ability){
      if($user->hasAnyRegras("0_administrador"))
      {
        return true;
      }
    });
  }
}
