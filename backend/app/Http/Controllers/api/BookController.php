<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BookService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    //
     protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function getAllBooks()
    {
        $books = $this->bookService->getAllBooks();
        return response()->json($books);
    }

    public function getFiveBooks()
    {
        $books = $this->bookService->getFiveBooks();
        return response()->json($books);
    }

    public function getBookInfo($id)
    {
        $book = $this->bookService->getBookInfo($id);
        return response()->json($book);
    }

    public function getBooksByType($category_id)
    {
        $books = $this->bookService->getBooksByType($category_id);
        return response()->json($books);
    }

    public function getBook($type,$name)
    {
        $book = $this->bookService->getBook($type,$name);
        return response()->json($book);
    }
}
