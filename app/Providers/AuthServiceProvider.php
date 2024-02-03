<?php

namespace App\Providers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Role;
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
        # Dashboard/OrderController
        Gate::define('order-edit-update-delete', function (User $user, Order $order): Response {
            if ($user->id !== $order->user_id) {
                return Response::deny();
            }

            return Response::allow();
        });

        # Dashboard/UserController
        Gate::define('user-show', function (User $user, User $currUser): Response {
            return $user->id === $currUser->id
                ? Response::deny()
                : Response::allow();
        });
        Gate::define('user-edit-update', function (User $user, User $currUser): Response {
            return $currUser->role === Role::ADMIN
                ? Response::deny()
                : Response::allow();
        });
        Gate::define('user-destroy', function (User $user, User $currUser): Response {
            return $currUser->role === Role::ADMIN || $user->id === $currUser->id
                ? Response::deny()
                : Response::allow();
        });

        # Dashboard/ProductController
        Gate::define('product-edit-update-destroy', function (User $user, Product $product): Response {
            return $user->id === $product->user_id
                ? Response::allow()
                : Response::deny();
        });
    }
}
