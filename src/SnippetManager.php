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

    private function getKeyName($locale, $key) {
        $path = [$this->getLocale($locale), $this->namespace, $key];
        return implode('/', $path);
    }

    /**
     * @param String $locale
     * @return String
     */
    private function getLocale($locale = null)
    {
        return $locale ? $locale : $this->app['config']['app.locale'];
    }

    /**
     * @param type $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    public function get($key, $default = '', $namespace = null, $locale = null)
    {
        if (null !== $namespace) {
            $this->namespace = $namespace;
        }
        
        $locale = $this->getLocale($locale);
        $storeKey = $this->getKeyName($locale, $key);
        $default = $default ? $default : $storeKey;
        $manager = $this;
        $namespace = $this->namespace;
        
        $snippet = Cache::rememberForever($storeKey, function () use ($namespace, $key, $default, $manager, $locale) {
            return $manager->fetch($namespace, $key, $default, $locale);
        });
        return $snippet;
    }

    public function fetch($namespace, $key = null, $default = '', $locale = null)
    {
        $locale = $this->getLocale($locale);
        $default = $this->getKeyName($locale, $key);
        $query = DB::table('ms_snippets');
        if ($key) {
            $query->where('key', $key);
        }
        if ('' != $namespace) {
            $query->where('namespace', $namespace);
        }
        $query->where('locale', $locale);
        if ($key) {
            $snippetValue = $query->pluck('value')->first();
            if (! $snippetValue) {
                return $this->missingSnippet($namespace, $key, $default, $locale);
            }

            return $snippetValue;
        }
        $valuesByNamespace = [$namespace => $query->pluck('value', 'key')->toArray()];

        return $valuesByNamespace;
    }

    public function missingSnippet($namespace, $key, $value, $locale = null)
    {
        $locale = $this->getLocale($locale);
        Snippet::firstOrCreate([
            'locale' => $locale,
            'namespace' => $namespace,
            'key' => $key,
            'value' => $value,
        ]);
        
        return $value;
    }
}
