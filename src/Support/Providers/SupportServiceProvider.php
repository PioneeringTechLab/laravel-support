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
		$versionString = $this->app->version();
		$versionArr = explode(".", $versionString);
		$majorMinor = implode(".", [$versionArr[0], $versionArr[1]]);

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

    	// publish views and make them available as well
    	$this->loadViewsFrom(__DIR__.'/../views', 'support');
	    $this->publishes([
	        __DIR__.'/../views' => base_path('resources/views/vendor/support'),
	    ], 'views');

		// check Laravel version since the service provider will have to
		// boot in different ways based upon the version
		if($majorMinor == "5.0") {
			// code specific to Laravel 5.0
			// load routes
			require __DIR__.'/../routes/support.php';
		}
		else if(in_array($majorMinor, ["5.1", "5.2"])) {
			// code specific to Laravel 5.1 and 5.2
			// load routes
			if (! $this->app->routesAreCached()) {
		        require __DIR__.'/../routes/support.php';
		    }
		}
		else
		{
			// code for 5.3 and above
			// load routes
			$this->loadRoutesFrom(__DIR__.'/../routes/support.php');
		}
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
