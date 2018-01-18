<?php

namespace CSUNMetaLab\Support\Providers;

use Illuminate\Support\ServiceProvider;

class SupportServiceProvider extends ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {

	}

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot() {
		// publish configuration
		$this->publishes([
        	__DIR__.'/../config/support.php' => config_path('support.php'),
    	], 'config');

    	// publish migrations
    	$this->publishes([
    		__DIR__.'/../migrations' => database_path('migrations'),
    	], 'migrations');

    	// publish models into the app directory
    	$this->publishes([
    		__DIR__.'/../Models' => app_path(),
    	], 'models');

    	// publish language files
    	$this->publishes([
    		__DIR__.'/../lang' => base_path('resources/lang/en')
    	], 'lang');

    	// publish views and make them available as well
    	$this->loadViewsFrom(__DIR__.'/../views', 'support');
	    $this->publishes([
	        __DIR__.'/../views' => base_path('resources/views/vendor/support'),
	    ], 'views');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides() {
		return array();
	}

}
