<?php

namespace clovergarden\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // SET GLOBAL VARIABLES
        view()->share('page_no', 1);
        view()->share('page_key');
        
        // SIDE BAR
        view()->share('cate_02_result');
        
        // USER
        view()->share('login_id');
        view()->share('group_name');
        view()->share('login_name');
        view()->share('login_state', null);
        view()->share('use_point');
        view()->share('login_email');
        view()->share('login_cell');
        view()->share('post1');
        view()->share('post2');
        view()->share('addr1');
        view()->share('addr2');
        
        // BOARD
        view()->share('seq');
        view()->share('search_key');
        view()->share('search_val');
        
        // CLOVERGARDEN
        view()->share('clover_name_v');
        
        // ADMIN
        view()->share('key_large');
        view()->share('title_txt');
        view()->share('content_txt');
        
        // view, write, list 링크는 최대한 삭제
        /*
        view()->share('list_link');
        view()->share('write_link');
        view()->share('view_link'); */
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
