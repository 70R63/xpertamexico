<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('isSysAdmin', function ($user) {
            return $user->roles->first()->slug == 'sysadmin';
        });

        Gate::define('isAdmin', function ($user) {
            return $user->roles->first()->slug == 'admin';
        });

        Gate::define('isCliente', function ($user) {
            return $user->roles->first()->slug == 'cliente';
        });

        Gate::define('isEjecutivo', function ($user) {
            return $user->roles->first()->slug == 'ejecutivo';
        });

        Gate::define('isUsuario', function ($user) {
            return $user->roles->first()->slug == 'usuario';
        });
    }
}
