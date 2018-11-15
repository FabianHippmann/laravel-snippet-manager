<?php

function snippet(...$args)
{
    return App::make('snippet.manager')->get(...$args);
}

function snippetsByNamespace($namespace, $locale = null)
{
    return App::make('snippet.manager')->fetch($namespace, null, null, $locale);
}
