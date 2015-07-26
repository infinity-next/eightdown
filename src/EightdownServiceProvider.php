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
		$this->app->singleton('parsedown', function($app) {
			return new EightdownOverload;
		});
		
		$this->app->alias('parsedown', 'InfinityNext\Eightdown\EightdownOverload');
	}
	
	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['InfinityNext\Eightdown\EightdownOverload'];
	}
	
}