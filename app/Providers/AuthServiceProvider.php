<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use App\Models\Config\SisPermissao;
use Carbon\Carbon;
use App\User;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
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
