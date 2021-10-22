<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use modules\Admins\Policies\AdminPolicy;
use modules\Admins\Models\Admin;
use modules\Admins\Policies\AdminsPolicy;
use modules\Customers\Models\Customer;
use modules\Customers\Policies\CustomerPolicy;
use modules\Products\Models\Product;
use modules\Products\Policies\OrderPolicy;
use modules\Categories\Models\Category;
use modules\Users\Models\User;
use Spatie\Permission\Models\Role;
use  modules\Roles\Policy\RolePolicy;
use Spatie\Permission\Models\Permission;
use  modules\Permissions\Policy\PermissionPolicy;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
//         Admin::class => AdminPolicy::class,
         Admin::class => AdminsPolicy::class,
         Product::class => OrderPolicy::class,
         Role::class => RolePolicy::class,
         Permission::class => PermissionPolicy::class,
         Customer::class => CustomerPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
