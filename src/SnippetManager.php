<?php

namespace Moonshiner\SnippetManager;

use Cache;
use DB;
use Illuminate\Foundation\Application;
use Moonshiner\SnippetManager\Models\Snippet;

class SnippetManager
{
    private $namespace = '';
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param type $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    public function get($key, $default = '', $namespace = null)
    {
        if (null !== $namespace) {
            $this->namespace = $namespace;
        }

        $path = [$this->app['config']['app.locale'], $this->namespace, $key];
        $storeKey = implode('/', $path);
        $manager = $this;
        $namespace = $this->namespace;
        $snippet = Cache::rememberForever($storeKey, function () use ($namespace, $key, $default, $manager) {
            return $manager->fetch($namespace, $key, $default);
        });

        return $snippet;
    }

    public function fetch($namespace, $key = null, $default = '')
    {
        $query = DB::table('ms_snippets');
        if ($key) {
            $query->where('key', $key);
        }
        if ('' != $namespace) {
            $query->where('namespace', $namespace);
        }
        $query->where('locale', $this->app['config']['app.locale']);
        if ($key) {
            $snippetValue = $query->pluck('value')->first();
            if (! $snippetValue) {
                return $this->missingSnippet($namespace, $key, $default);
            }

            return $snippetValue;
        }
        $valuesByNamespace = [$namespace => $query->pluck('value', 'key')->toArray()];

        return $valuesByNamespace;
    }

    public function missingSnippet($namespace, $key, $value)
    {
        Snippet::firstOrCreate([
            'locale' => $this->app['config']['app.locale'],
            'namespace' => $namespace,
            'key' => $key,
            'value' => $value,
        ]);

        return $value;
    }
}
