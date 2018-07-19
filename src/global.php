<?php

function snippet(...$args)
{
    return App::make('snippet.manager')->get(...$args);
}

function snippetsByNamespace($namespace)
{
    return App::make('snippet.manager')->fetch($namespace);
}
