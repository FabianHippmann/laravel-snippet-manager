<?php

namespace Moonshiner\SnippetManager;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Collection;
use Moonshiner\SnippetManager\Models\Snippet;
use Artisan;
use App;
use Cache;
class Controller extends BaseController
{
    /** @var \Moonshiner\SnippetManager\SnippetManager  */
    protected $manager;
    public function __construct(SnippetManager $manager)
    {
        $this->manager = $manager;
    }

    public function getView()
    {
       return view('snippet-manager::index');
    }
    public function index(){
        $allTranslations = Snippet::take(10)->get();

        return $allTranslations;
    }

    public function search(){
        $allTranslations = Snippet::where('value', 'LIKE', '%'.request()->input('s', '').'%')->orWhere('key', 'LIKE', '%'.request()->input('s', '').'%')->get();

        return $allTranslations;
    }
    public function update(Request $request, Snippet $snippet){

        $snippet->value = $request->input('value', '');
        $path = [App::getLocale(),$snippet->namespace, $snippet->key];

        $storeKey = implode('/', $path);

        Cache::put($storeKey, $snippet->value);
        $this->clearCache();

        $snippet->save();
    }
    protected function loadLocales()
    {
        //Set the default locale as the first one.
        $locales = Snippet::groupBy('locale')
            ->select('locale')
            ->get()
            ->pluck('locale');
        if ($locales instanceof Collection) {
            $locales = $locales->all();
        }
        $locales = array_merge([config('app.locale')], $locales);
        return array_unique($locales);
    }

    public function clearCache(){
        Artisan::call('view:clear');
    }
}
