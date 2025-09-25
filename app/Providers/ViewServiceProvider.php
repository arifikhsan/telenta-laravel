<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use App\Models\AcmMenu;
use App\Models\AcmRoleMenu;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {
            $role = Session::get('role');

            $menus = AcmRoleMenu::with('acmMenu')
            ->whereHas('role', function ($query) use ($role) {
                $query->where('name', $role);
            })
            ->get()
            ->map(function ($item) {
                return (object) [
                    'name' => $item->acmMenu->name ?? '',
                    'url'  => $item->acmMenu->url ?? '#',
                    'icon' => $item->acmMenu->icon ?? 'default-icon',
                ];
            });

            $view->with('menu', $menus);
            $view->with('role', $role);
        });
    }


    public function register()
    {
        //
    }
}
