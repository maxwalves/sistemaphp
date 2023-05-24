<?php

namespace App\Providers;

use App\Models\User;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Auth;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Policies\UserPolicy;
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
     */
    public function boot(): void
    {
        Gate::resource('users', UserPolicy::class);
        $this->registerPolicies();

        Fortify::authenticateUsing(function ($request) {

            $credentials = $request->only('username', 'password');
            $user = User::where('username', $request->username)->first();
            try {
                if ($user->employeeNumber==null) {

                    return null;
                }
            } catch (\Throwable $th) {
                //throw $th;
            }
            
            $validated = Auth::validate([
                'mail' => $request->username,
                'password' => $request->password
            ]);

            return $validated ? Auth::getLastAttempted() : null;
        });

        Fortify::confirmPasswordsUsing(function (User $user, $password) {
            return Auth::validate([
                'mail' => $user->username,
                'password' => $password,
            ]);
        });

    }
}
