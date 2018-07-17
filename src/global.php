<?php

function snippet(...$args){
    return App::make('snippet.manager')->get(...$args);
}
