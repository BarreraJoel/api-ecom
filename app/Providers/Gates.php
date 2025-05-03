<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\MoviePolicy;
use App\Policies\UserPolicy;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

class Gates {

    public static function define() {
        Gates::defineUserActions();
    }

    private static function defineUserActions() {
        Gate::define('update_user', [UserPolicy::class, 'update']);
        Gate::define('delete_user', [UserPolicy::class, 'delete']);
    }
}