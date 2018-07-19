<?php

namespace Moonshiner\SnippetManager\Models;

use Illuminate\Database\Eloquent\Model;

class Snippet extends Model
{
    protected $table = 'ms_snippets';

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
