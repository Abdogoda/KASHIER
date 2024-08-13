<?php

namespace App\Providers;

use App\Models\Notification;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider{
    public function register(): void{
        //
    }

    public function boot(): void{
        // can and cannot
        Blade::if('can', function ($permission) {
            return auth()->check() && auth()->user()->role->permissions->pluck('en_name')->contains($permission);
        });

        if (DB::connection()->getDatabaseName()) {

            // current shift cache
            View::composer('*', function ($view) {
                $current_shift = null;
                $current_shift = Cache::get('current_shift');
                $view->with('current_shift', $current_shift);
            });

            // site settings
            $siteSettings = null;
            if (Schema::hasTable('settings')) {
                $siteSettings = Setting::all()->pluck('value', 'key')->toArray();
            }
            View::share('siteSettings', $siteSettings);
            
            // site Notifications
            $siteNotifications = null;
            if (Schema::hasTable('notifications')) {
                $siteNotifications = Notification::where('read', false)->orderBy('created_at', 'DESC')->limit(7)->get();
            }
            View::share('siteNotifications', $siteNotifications);

            // Cache all products when the application starts
            Cache::rememberForever('all_products', function () {
                return Product::all();
            });
        }
    }
}