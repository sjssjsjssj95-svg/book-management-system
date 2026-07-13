<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Services\InfoService;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    //
    protected $infoService;

    public function __construct(InfoService $infoService)
    {
        $this->infoService = $infoService;
    }

    public function getAllInfo()
    {
        $info = $this->infoService->getAllInfo();
        return response()->json($info);
    }
}
