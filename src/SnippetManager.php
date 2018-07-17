<?php
namespace Moonshiner\SnippetManager;

use Moonshiner\SnippetManager\Manager;
use Illuminate\Foundation\Application;
use Moonshiner\SnippetManager\Models\Snippet;
use Cache;
use DB;
/**
*
*/
class SnippetManager
{
	private $namespace = "";
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

	public function get($key, $default = '', $namespace = null){
        if($namespace !== null){
            $this->namespace = $namespace;
        }

        $path = [$this->app['config']['translation-manager'], $this->namespace, $key];

		$storeKey = implode('/', $path);
        $manager = $this;
        $namespace = $this->namespace;
		$snippet = Cache::get($storeKey, function () use ($namespace, $key, $default, $manager){
		    return $manager->fetch($namespace, $key, $default);
		});

		return $snippet;
	}

    public function fetch($namespace, $key, $default = ''){
        $query = DB::table('ms_snippets')->where('key', $key);
        if($namespace != ''){
            $query->where('namespace', $namespace);
        }
        $query->where('locale', $this->app['config']['app.locale']);
        $snippetValue = $query->pluck('value')->first();
        if(!$snippetValue){
            return $this->missingSnippet($namespace, $key, $default);
        }
        return $snippetValue;
    }

    public function missingSnippet($namespace, $key, $value)
    {
        Snippet::firstOrCreate(array(
            'locale' => $this->app['config']['app.locale'],
            'namespace' => $namespace,
            'key' => $key,
            'value' => $value
        ));
        return $value;
    }
}
