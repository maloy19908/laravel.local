<?php

namespace App\Providers;

use App\Shortcodes\CostsShortcode;
use App\Shortcodes\TownsShortcode;
use Illuminate\Support\ServiceProvider;
use Webwizo\Shortcodes\Facades\Shortcode;

class ShortcodesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    /* НЕПОНЯТНО КАК СДЕЛАТЬ АВТОЛОАД

        foreach (glob(app_path() . '/Shortcodes/*.php') as $file) {
            require_once($file);
        } 
    */
        Shortcode::register('COST', CostsShortcode::class);
        Shortcode::register('TOWNS', TownsShortcode::class);
    }
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
