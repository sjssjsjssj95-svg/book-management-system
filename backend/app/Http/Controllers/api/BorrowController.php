<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\BorrowService;

class BorrowController extends Controller
{
    //
    protected $borrowService;

    public function __construct(BorrowService $borrowService)
    {
        $this->borrowService = $borrowService;
    }

    public function createBorrow(Request $request)
    {
        $user = $request->user();

        try {
            $borrow = $this->borrowService->createBorrow($request->all(),$user);
            return response()->json([
                'code'=> 200,
                'msg'=> '借阅成功',
            ]);
        }  catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(),'code' => $e->getCode(),]);
        }
    }

    public function getBorrowNorLog(Request $request)
    {
        $user = $request->user();

        try {
            $borrows = $this->borrowService->getBorrowNorLog($user);
            return $borrows;
        }   catch (\Exception $e) {
            return response()->json(['msg'=> $e->getMessage(),'code'=> $e->getCode(),]);
        }
    }

    public function getBorrowOutLog(Request $request)
    {
        $user = $request->user();

        try {
            $borrows = $this->borrowService->getBorrowOutLog($user);
            return $borrows;
        }   catch (\Exception $e) {
            return response()->json(['msg'=> $e->getMessage(),'code'=> $e->getCode(),]);
        }
    }

    public function getBorrowLostLog(Request $request)
    {
        $user = $request->user();

        try {
            $borrows = $this->borrowService->getBorrowLostLog($user);
            return $borrows;
        }   catch (\Exception $e) {
            return response()->json(['msg'=> $e->getMessage(),'code'=> $e->getCode(),]);
        }
    }

    public function getBackLog(Request $request)
    {
        $user = $request->user();

        try {
            $borrows = $this->borrowService->getBackLog($user);
            return $borrows;
        }   catch (\Exception $e) {
            return response()->json(['msg'=> $e->getMessage(),'code'=> $e->getCode(),]);
        }
    }

    public function backBook(Request $request)
    {
        $user = $request->user();

        try {
            $borrows = $this->borrowService->backBook($request->all(),$user);
            return $borrows;
        }   catch (\Exception $e) {
            return response()->json(['msg'=> $e->getMessage(),'code'=> $e->getCode(),]);
        }
    }
}
