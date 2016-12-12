<?php

namespace Ghero\DominantColor;

use Illuminate\Support\ServiceProvider;

class DominantColorServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(DominantColor::class, function($app) {
        	return new DominantColor;
        });
    }
	
	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides() {
		return [DominantColor::class];
	}
}
