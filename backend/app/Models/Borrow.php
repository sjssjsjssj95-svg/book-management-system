<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    protected $table = 'borrow';

    protected $fillable = [
        'user_id',
        'book_id',
        'borrow_time',
        'due_time',
        'return_time',
        'status',
        'created_at',
        'updated_at',
        'root_confirm'
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'date:Y-m-d',
            'due_time' => 'date:Y-m-d',
            'return_time' => 'date:Y-m-d',
        ];
    }
}
