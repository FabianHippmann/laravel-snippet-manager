<?php

namespace Moonshiner\SnippetManager;

use Blade;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class SnippetManagerServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * Register any package services.
     */
    public function register()
    {
        $configPath = __DIR__.'/../config/snippet-manager.php';

        $this->mergeConfigFrom($configPath, 'snippet-manager');

        $this->publishes([$configPath => config_path('snippet-manager.php')], 'config');

        $this->app->singleton('snippet.manager', function ($app) {
            return new SnippetManager($app);
        });

        Blade::directive('namespace', function ($arguments) {
            return "<?php App::make('snippet.manager')->setNameSpace({$arguments}) ?>";
        });

        Blade::directive('s', function ($arguments) {
            return "<?php echo App::make('snippet.manager')->get({$arguments}) ?>";
        });
    }

    public function boot(Router $router)
    {
        $viewPath = __DIR__.'/../resources/views';
        $this->loadViewsFrom($viewPath, 'snippet-manager');
        $this->publishes([
            $viewPath => base_path('resources/views/vendor/snippet-manager'),
        ], 'views');

        $migrationPath = __DIR__.'/../database/migrations';
        $this->publishes([
            $migrationPath => base_path('database/migrations'),
        ], 'migrations');

        $config = $this->app['config']->get('snippet-manager.route', []);

        $config['namespace'] = 'Moonshiner\SnippetManager';

        $router->group($config, function ($router) {
            $router->get('/', 'Controller@getView');
            $router->get('/all', 'Controller@index');
            $router->put('/{snippet}', 'Controller@update');
            $router->get('search', 'Controller@search');
            $router->get('groups', 'Controller@groups');
            $router->post('clearCache', 'Controller@clearCache');
        });
    }

    public function provides()
    {
        return [
            'snippet.manager',
        ];
    }
}
