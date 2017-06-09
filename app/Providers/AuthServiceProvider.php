<?php

namespace GetCandy\Providers;

use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'GetCandy\Model' => 'GetCandy\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::tokensCan([
            'read' => 'Read API'
        ]);
        Passport::routes();

        Passport::tokensExpireIn(Carbon::now()->addMinutes(60));
        Passport::refreshTokensExpireIn(Carbon::now()->addMinutes(60));
    }
}
