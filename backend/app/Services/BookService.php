<?php

namespace App\Services;
use App\Models\Category;
use App\Models\Book;
use Illuminate\Support\Facades\Hash;

class BookService
{
    public function getAllBooks(){
        $books = Book::select('id', 'title', 'author', 'category_id', 'cover', 'available')
            ->get()
            ->map(function ($book) {
                $category = Category::find($book->category_id);
                $book->cover = $book->cover ?: 'mr';
                return [
                    'id'          => $book->id,
                    'title'       => $book->title,
                    'author'      => $book->author,
                    'category'    => $category->name,
                    'cover'       => $book->cover,           // 封面图片地址
                    'is_available'=> $book->available > 0,  // 是否可借阅
                    'available'   => $book->available,       // 可借数量（可选）
                ];
            });

        return $books;
    }

    public function getFiveBooks(){
        $books = Book::select('id', 'title', 'author', 'category_id', 'cover', 'available')
            ->inRandomOrder()
            ->get()
            ->take(4)
            ->map(function ($book) {
                $category = Category::find($book->category_id)->first();
                $book->cover = $book->cover ?: 'mr';
                return [
                    'id'          => $book->id,
                    'title'       => $book->title,
                    'author'      => $book->author,
                    'category'    => $category->name,
                    'cover'       => $book->cover,           // 封面图片地址
                    'is_available'=> $book->available > 0,  // 是否可借阅
                    'available'   => $book->available,       // 可借数量（可选）
                ];
            });

        return $books;
    }

    public function getBookInfo($id)
    {
        $book = Book::find($id);
        if (!$book) {
            return ['msg'=>('001')];
        }
        else {
        $category = Category::find($book->category_id)->first();
                $book->cover = $book->cover ?: 'mr';
           return [
                'id'          => $book->id,
                'stock'       => $book->stock,
                'title'       => $book->title,
                'author'      => $book->author,
                'description'      => $book->description,
                'category'    => $category->name,
                'cover'       => $book->cover,           // 封面图片地址
                'is_available'=> $book->available > 0,  // 是否可借阅
                'available'   => $book->available,       // 可借数量（可选）
                'category_id' => $book->category_id,
            ];
        }
    }

    public function getBooksByType($category_id)
    {
        if ($category_id == 0) 
        {
            $books = Book::select('id', 'title', 'author', 'category_id', 'cover', 'available')
            ->get()
            ->map(function ($book) {
                $category = Category::find($book->category_id)->first();
                $book->cover = $book->cover ?: 'mr';
                return [
                    'id'          => $book->id,
                    'title'       => $book->title,
                    'author'      => $book->author,
                    'category'    => $category->name,
                    'cover'       => $book->cover,           // 封面图片地址
                    'is_available'=> $book->available > 0,  // 是否可借阅
                    'available'   => $book->available,       // 可借数量（可选）
                ];
            });
            return $books;
        }
        $books = Book::where('category_id','=', $category_id)
        ->get()
        ->map(function ($book) {
            $category = Category::find($book->category_id)->first();
                $book->cover = $book->cover ?: 'mr';
            return [
                'id'          => $book->id,
                'title'       => $book->title,
                'author'      => $book->author,
                'category'    => $category->name,
                'cover'       => $book->cover,           // 封面图片地址
                'is_available'=> $book->available > 0,  // 是否可借阅
                'available'   => $book->available,       // 可借数量（可选）
            ];
        });
        return $books;
    }

    public function getBook($type,$name)
    {
        if (empty($type)||empty($name)) {
            return ['msg' => '0' ];
        }
        $books = Book::where($type, 'like', '%' . $name . '%')
        ->get()
        ->map(function ($book) {
            $category = Category::find($book->category_id)->first();
                $book->cover = $book->cover ?: 'mr';
            return [
                'id'          => $book->id,
                'title'       => $book->title,
                'author'      => $book->author,
                'category'    => $category->name,
                'cover'       => $book->cover,           // 封面图片地址
                'is_available'=> $book->available > 0,  // 是否可借阅
                'available'   => $book->available,       // 可借数量（可选）
            ];
        });
        return $books;
    }


    
}