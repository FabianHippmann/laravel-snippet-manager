<?php
return array(
    /*
    |--------------------------------------------------------------------------
    | Routes group config
    |--------------------------------------------------------------------------
    |
    | The default group settings for the snippetmanager routes.
    |
    */
    'route' => [
        'prefix' => 'snippets',
        'middleware' => 'auth',
    ],
);
