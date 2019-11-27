<?php

namespace App\Providers;

use App\Models\UserType;
use App\Policies\UserPolicy;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('admin', function ($user) {
            return $user->user_types_id == UserType::ADMIN;
        });

        Gate::define('office', function ($user) {
            return $user->user_types_id == UserType::OFFICE || $user->user_types_id == UserType::ADMIN;
        });

        Gate::define('customer', function ($user) {
            return $user->user_types_id == UserType::CUSTOMER;
        });
    }
}
