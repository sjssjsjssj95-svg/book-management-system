<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    //
    protected $table = 'info';

    public $timestamps = false;

    protected $fillable = [
        'title',
        'content',
        'created_at',
    ];
}
