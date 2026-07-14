<?php

namespace App\Services;
use App\Models\Root;
use App\Models\Book;
use App\Models\User;
use App\Models\Info;
use App\Models\Borrow;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Mail;

use Cache;
use App\Mail\VerifyCodeMail;

class RootService
{
    public function login(array $data) {
        $root_email = $data['email'];
        $root_password = $data['password'];

        $root = Root::where('email',$root_email)->where('role', 'root')->first();

        if(!$root)
        {
            throw new \Exception('没用该root',0002);
        }

        //hash::check用于比较hash加密的数据，这里用来比较password
        if(!Hash::check($root_password,$root->password))
        {
            throw new \Exception('密码错误',0001);
        }

        $token = $root->createToken('login_token')->plainTextToken;
        return ['msg'=>'登陆成功' , 'token'=>$token , 'code'=>1000];
    }

    public function loginIO($root)
    {
        if(!$root)
        {
            throw new \Exception('root未登录',0000);
        }

        return ['code'=>1000];
    }







    //获取各个类型的书籍总数
    public function getBookTypeNumber($root)
    {
        if(!$root)
        {
            throw new \Exception('root未登录',0000);
        }

        $bookData = Book::select('category_id', DB::raw('COUNT(*) as number'))
            ->groupBy('category_id')
            ->get()
            ->map(function ($book) {
            $category = Category::find($book->category_id);
            return [
                'number'    => $book->number,
                'type_name' => $category->name,
            ] ;
        });

        return $bookData;
    }

    public function getUserNumber($root)
    {
        if(!$root)
        {
            throw new \Exception('root未登录',0000);
        }

        $userCount = User::where('role', 'user')->count();

        return $userCount;
    }

    //获取各个借阅总数
    public function getBorrowNumber($root)
    {
        if(!$root)
        {
            throw new \Exception('root未登录',0000);
        }

        $borrowData = Borrow::select('status', DB::raw('COUNT(*) as number'))
            ->groupBy('status')
            ->get()
            ->map(function ($borrow) {
            if ($borrow->status==0)
            {
                return [
                    'number'    => $borrow->number,
                    'type_name' => '借阅中',
                ] ;
            }
            if ($borrow->status==1)
            {
                return [
                    'number'    => $borrow->number,
                    'type_name' => '已归还',
                ] ;
            }
            if ($borrow->status==2)
            {
                return [
                    'number'    => $borrow->number,
                    'type_name' => '已逾期',
                ] ;
            }
            if ($borrow->status==3)
            {
                return [
                    'number'    => $borrow->number,
                    'type_name' => '已逾期',
                ] ;
            }
        });

        return $borrowData;
    }

    //退出登录
    public function logout($root)
    {
        if (!$root)
        {
            throw new \Exception('用户未登录',0000);
        }
        $root->currentAccessToken()->delete();

        return [
            'code' => 200,
            'msg' => '退出登录成功'
        ];
    }

    //查看用户信息
    public function getRootInfo($root)
    {
        if (!$root)
        {
            throw new \Exception('没有登录',0000);
        }

        $return_root = new Root();

        $return_root->email = $root->email;
        $return_root->nickname = $root->nickname;
        $return_root->status = $root->status;

        $create_time = $root->created_at;

        $diff = $create_time->diffInDays(now());

        $return_root->register_days = (int)$diff;

        return $return_root;
    }

    //获取所有书籍
    public function getAllBooks($root)
    {
         if (!$root)
        {
            throw new \Exception('没有登录',0000);
        }

        $books = Book::select('id', 'title', 'author', 'category_id', 'cover', 'available','stock','created_at')
            ->get()
            ->map(function ($book) {
                $category = Category::find($book->category_id);
                $book->cover = $book->cover ?: 'mr';
                return [
                    'id'          => $book->id,
                    'title'       => $book->title,
                    'author'      => $book->author,
                    'created_at'  => $book->created_at->format('Y-m-d'),
                    'category'    => $category->name,
                    'stock'       => $book->stock,
                    'cover'       => $book->cover,           // 封面图片地址
                    'available'   => $book->available,       // 可借数量（可选）
                ];
            });

            return $books;
    }

    //书本管理
    //修改书本
    public function updata_book(array $data, $root)
    {
        if (!$root) {
            throw new \Exception('没有登录', 0000);
        }

        // 查询书籍
        $book = Book::where('id', $data['book_id'])->first();

        if (!$book) {
            throw new \Exception('书籍不存在', 404);
        }

        // 查询分类
        $category = Category::where('id', $data['category_id'])->first();

        if (!$category) {
            throw new \Exception('分类不存在', 404);
        }

        // 更新普通数据
        $book->title = $data['title'];
        $book->author = $data['author'];
        $book->category_id = $category->id;
        $book->description = $data['description'];
        $book->stock = $data['stock'];


        // 如果上传了新图片
        if (isset($data['cover'])) {

            // 保存旧图片名称
            $oldCover = $book->cover;

            // 生成新图片名称
            $newCover = uniqid();

            // 保存新图片
            $data['cover']->storeAs(
                'Book',
                $newCover . '.jpg',
                'public'
            );

            // 更新数据库
            $book->cover = $newCover;

            // 删除旧图片
            if ($oldCover) {
                Storage::disk('public')->delete(
                    'Book/' . $oldCover . '.jpg'
                );
            }
        }


        $book->updated_at = Carbon::now();

        $book->save();

        return [
            'msg' => '更新成功'
        ];
    }
    //删除book
    public function delete_book(array $data ,$root)
    {
        if (!$root)
        {
            throw new \Exception('没有登录',0000);
        }

        $book = Book::where('id', $data['book_id'])->first();

        if (!empty($book->cover)) {
            Storage::disk('public')->delete(
                'Book/' . $book->cover . '.jpg'
            );
        }

        if ($book) {
            $book->delete();
            return [
                'code' => 200,
                'msg' => '删除成功'
            ];
        }

        throw new \Exception("此书不存在或者出现错误，请重试",0005);
    }
    //查询所有分类
    public function get_all_category($root)
    {
        if (!$root) {
            throw new \Exception('没有登录', 0000);
        }

        $categories = Category::all();

        return $categories;
    }
    //删除分类
    public function delete_category($data, $root)
    {
        if (!$root) {
            throw new \Exception('没有登录', 0000);
        }

        $categorie = Category::where('id',$data['id'])->first();

        $categorie->delete();

        return [
            'msg' => '200'
        ];
    }
    //添加类别
    public function add_category($data, $root)
    {
        if (!$root) {
            throw new \Exception('没有登录', 0000);
        }

        $categorie = Category::where('name',$data['name'])->first();

        if (!empty($categorie))
        {
            throw new \Exception('已有此类别', 1000);
        }

        Category::create([
            'name' => $data['name'],
        ]);
        return [
            'msg' => '200'
        ];
    }
    // 添加书籍
    public function add_book(array $data, $root)
    {
        if (!$root) {
            throw new \Exception('没有登录', 0000);
        }

        // 查询分类
        $category = Category::find($data['category_id']);

        if (!$category) {
            throw new \Exception('分类不存在', 404);
        }

        // 创建书籍
        $book = new Book();

        $book->title = $data['title'];
        $book->author = $data['author'];
        $book->category_id = $category->id;
        $book->description = $data['description'];
        $book->stock = $data['stock'];

        // 上传封面
        if (isset($data['cover'])) {

            // 生成图片名称
            $coverName = uniqid();

            // 保存图片
            $data['cover']->storeAs(
                'Book',
                $coverName . '.jpg',
                'public'
            );

            // 保存图片名
            $book->cover = $coverName;
        }

        $book->created_at = Carbon::now();

        $book->save();

        return [
            'msg' => '添加成功'
        ];
    }



    //公告
    //添加公告
    public function add_info(array $data ,$root)
    {
        if (!$root)
        {
            throw new \Exception('没有登录',0000);
        }

        Info::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'created_at' => Carbon::now()->format('Y-m-d'),
        ]);
    }

    //删除公告
    public function delete_info(array $data ,$root)
    {
        if (!$root)
        {
            throw new \Exception('没有登录',0000);
        }

        Info::where('id',$data['id'])->delete();
    }



    //user操作
    //获取所有user
    public function get_all_user($root)
    {
        if (!$root)
        {
            throw new \Exception('没有登录',0000);
        }

        $users = User::where('role','user')->get()
        ->map(function ($user){
            if ($user->status==1)
            {
                $user->status = '正常';
            }
            else
            {
                $user->status = '禁用';
            }
            return $user;
        });
        

        return $users;
    }
    //禁用user
    public function user_baned($data,$root)
    {
        if (!$root)
        {
            throw new \Exception('没有登录',0000);
        }

        $user = User::where('id',$data['id'])->first();
        
        Mail::to($user->email)->send(new VerifyCodeMail($data['code'],"封禁用户"));

        $user-> baned_at = Carbon::now()->format('Y-m-d');
        $user->status = 0;
        $user->ban_why = $data['code'];
        $user->save();
        return 200;
    }
    //解禁user
    public function user_baned_off($data,$root)
    {
        if (!$root)
        {
            throw new \Exception('没有登录',0000);
        }

        $user = User::where('id',$data['id'])->first();
        
        Mail::to($user->email)->send(new VerifyCodeMail("00","解封用户"));

        $user->status = 1;
        $user->save();
        return 200;
    }
    //删除user
    public function user_delete($data,$root)
    {
        if (!$root)
        {
            throw new \Exception('没有登录',0000);
        }

        $user = User::where('id',$data['id'])->first();
        
        Mail::to($user->email)->send(new VerifyCodeMail("00","删除用户"));
        // 删除旧头像（如果有）
        if ($user->avatar!='avatars/mr.jpg')
        {
            if ($user->avatar && file_exists(public_path('storage/'.$user->avatar))) {
                unlink(public_path('storage/'.$user->avatar));
            }
        }

        $user->delete();
        return 200;
    }

    //借阅
    //查询所有借阅记录
    public function get_all_borrow($data,$root)
    {
        if (!$root)
        {
            throw new \Exception('没有登录',0000);
        }

        $borrows = Borrow::where('status',$data['status'])->get()
        ->map(function($borrow){
            $book = Book::where('id',$borrow->book_id)->first();
            $user = User::where('id',$borrow->user_id)->first();
            $borrow->book = $book;
            $borrow->user = $user;
            return $borrow;
        });
        return $borrows;
    }
    //root改变状态，1：已归还，2：丢失
    public function root_confirm_action($data,$root)
    {
        if (!$root)
        {
            throw new \Exception('没有登录',0000);
        }

        $borrow = Borrow::where('id',$data['id'])->first();
        if ($data['statu']==1||$data['statu']==3)
        {
            $borrow->root_confirm=1;
            $borrow->status=1;
            $borrow->save();
            return ['msg'=> '200'];
        }
        $borrow->status = 3;
        $lost_time = Carbon::now()->format('Y-m-d H:i:s');
        $borrow->return_time = $lost_time;
        $borrow->save();
        return ['msg'=>'200'];
    }

    //密码重置
     public function sendEmailCodeToPassword(string $email)
    {

        $root = User::where('email', $email)->where('role','root')->first();
        //查找用户是否存在
        if (!$root) 
        {
            throw new \Exception('没有这个用户',3000);
        }
        // 限流
        if (Cache::has('email_lock_' . $email)) {
            throw new \Exception('发送太频繁',1000);
        }      

       //生成验证码
        $code = rand(100000,999999);

        //存缓存
        Cache::put('email_code_' . $email, $code,now()->addMinutes(5));

        //限制60秒
        cache::put('email_lock_' . $email, 1 , 60);

        Mail::to($email)->send(new VerifyCodeMail($code,"重置密码"));

        return ['msg'=>'1'];
    }

    //验证验证码
    public function resetPassword(array $data)
    {
        $cacheCode = Cache::get('email_code_'. $data['email']);

        if (!$cacheCode) 
        {
            //验证码已过期
           throw new \Exception('验证码过期',1002);
        }

        if ($cacheCode != $data['code'])
        {
            //验证码错误
            throw new \Exception('验证码错误',1003);
        }

         //删除验证码
        Cache::forget('email_code_'. $data['email']);

        $root = User::where('email', $data['email'])->where('role','root')->first();

        //获取更新时间
        $updateTime = Carbon::now()->format('Y-m-d H:i:s');

        $root->password = Hash::make($data['password']);
        $root->updated_at = $updateTime;

        $root->save();

        return ['msg'=> '1'];
    }


    
}