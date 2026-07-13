<?php

namespace App\Services;
use App\Models\Borrow;
use App\Models\Book;
use Illuminate\Support\Facades\Hash;
use Mail;
use App\Mail\VerifyCodeMail;
use Carbon\Carbon;

class BorrowService
{
    public function createBorrow(array $data,$user)
    {
        if (!$user) 
        {
            throw new \Exception("用户未登录",4001);
        }
        $exists = Borrow::where('user_id', $user->id)
                        ->where('book_id', $data['book_id'])
                        ->whereIn('status', [0, 2, 3])
                        ->exists();
        if ($exists)
        {
            throw new \Exception("此书已被借阅",4002);
        }
        $borrow_time = now()->format('Y-m-d');
        $due_time = now()->addDays(10)->format('Y-m-d');

        $book = Book::where('id',$data['book_id'])->first();
        if ($book->available<=0) {
            throw new \Exception('没有库存',4000);
        }
        $book->available = $book->available - 1 ;
        $book->save();

        Borrow::create([
            'user_id'=> $user->id,
            'book_id'=> $data['book_id'],
            'borrow_time'=> $borrow_time,
            'due_time'=> $due_time,
            'status'=> 0,
            'created_at'=> $borrow_time,
        ]);

        Mail::to($user->email)->send(new VerifyCodeMail($book->title,"借阅书籍"));

        return ['msg'=> '1'];
    }

    //获取借还记录
    //借阅中：正常状态
    public function getBorrowNorLog($user)
    {
        if (!$user) 
        {
            throw new \Exception("用户未登录",4001);
        }
        $borrows = Borrow::where('user_id',$user->id)
        ->where('status', 0)
        ->get();
        foreach ($borrows as $borrow)
        {
            $book = Book::find($borrow->book_id);

            $borrow->book = $book;
        }
        $out_time = Borrow::where('user_id',$user->id)
        ->where('status', 2)
        ->exists();
        if ($out_time)
        {
            $borrow = new Borrow();
            $borrow->user_id = 0;
            $lost = Borrow::where('user_id',$user->id)
            ->where('status',3)
            ->exists();
            if ($lost)
            {
                $borrow->book_id = 0;
            }
            else
            {
                $borrow->book_id = 1;
            }
            $borrows->prepend($borrow);
        }
        if (!$out_time)
        {
            $borrow = new Borrow();
            $borrow->user_id = 1;
            $lost = Borrow::where('user_id',$user->id)
            ->where('status',3)
            ->exists();
            if ($lost)
            {
                $borrow->book_id = 0;
            }
            else
            {
                $borrow->book_id = 1;
            }
            $borrows->prepend($borrow);
        }
        return $borrows;
    }

    //借阅中：过期
    public function getBorrowOutLog($user)
    {
        if (!$user) 
        {
            throw new \Exception("用户未登录",4001);
        }
        $borrows = Borrow::where('user_id',$user->id)
        ->where('status', 2)
        ->get();
        foreach ($borrows as $borrow)
        {
            $book = Book::find($borrow->book_id);

            $borrow->book = $book;
        }
        return $borrows;
    }

    //借阅中：书本丢失
    public function getBorrowLostLog($user)
    {
        if (!$user) 
        {
            throw new \Exception("用户未登录",4001);
        }
        $borrows = Borrow::where('user_id',$user->id)
        ->where('status', 3)
        ->get();
        foreach ($borrows as $borrow)
        {
            $book = Book::find($borrow->book_id);

            $borrow->book = $book;
        }
        return $borrows;
    }

    //借阅中：已归还
    public function getBackLog($user)
    {
        if (!$user) 
        {
            throw new \Exception("用户未登录",4001);
        }
        $borrows = Borrow::where('user_id',$user->id)
        ->where('status', 1)
        ->get();
        foreach ($borrows as $borrow)
        {
            $book = Book::find($borrow->book_id);

            $borrow->book = $book;
        }
        return $borrows;
    }

    //user归还图书
    public function backBook(array $data,$user)
    {
        if (!$user) 
        {
            throw new \Exception("用户未登录",4001);
        }
        $borrow = Borrow::where('id',$data['id'])->first();

        if ( $borrow->status != 0 && $borrow->status != 2)
        {
            throw new \Exception('图书未在可归还状态',5000);
        }

        $return_time = Carbon::now()->format('Y-m-d H:i:s');

        $borrow->status = 1;
        $borrow->return_time = $return_time;
        $borrow->save();

        return 1;
    }
}