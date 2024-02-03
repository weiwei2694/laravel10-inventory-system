<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('order-edit-update-delete', function (User $user, Order $order) {
            if ($user->id !== $order->user_id) {
                return Response::deny('FORBIDDEN', 403);
            }

            return Response::allow();
        });
    }
}
