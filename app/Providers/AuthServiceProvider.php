<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\Post::class => \App\Policies\PostPolicy::class,
    ];
    public function register(): void
    {
        
    }

    
    public function boot(): void
    {
        $this->registerPolicies();
        
    
       
    }
}
