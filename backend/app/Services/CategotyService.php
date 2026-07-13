<?php

namespace App\Services;
use App\Models\Category;
use App\Models\Book;
use Illuminate\Support\Facades\Hash;

class CategotyService
{   
    public function getAllCategoty()
    {
        $categories = Category::select('id as value', 'name as label')->get()->toArray();

        array_unshift($categories, [
            'value' => 0,
            'label' => '全部分类'
        ]);

        return $categories;
    }
}