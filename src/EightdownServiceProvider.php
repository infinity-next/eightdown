<?php namespace InfinityNext\Eightdown;

use Illuminate\Support\ServiceProvider;

class EightdownServiceProvider extends ServiceProvider {
	
	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('markdown', function($app) {
			return new Eightdown;
		});
		
		$this->app->alias('markdown', 'InfinityNext\Eightdown\Eightdown');
	}
	
	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['InfinityNext\Eightdown\Eightdown'];
	}
	
}