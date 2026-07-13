<?php

namespace App\Http\Controllers\Api;
use App\Services\CategotyService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategotyController extends Controller
{
    //
    protected $categotyService;

    public function __construct(CategotyService $categotyService)
    {
        $this->categotyService = $categotyService;
    }

    public function getAllCategoty()
    {
        $categoty = $this->categotyService->getAllCategoty();
        return response()->json($categoty);
    }
}
