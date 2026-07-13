<?php

namespace App\Services;
use App\Models\User;
use Cache;
use Illuminate\Support\Facades\Hash;
use Mail;
use App\Mail\VerifyCodeMail;
use Carbon\Carbon;

class UserService
{
    public function sendEmailCodeToRegister(string $email)
    {
        // 限流
        if (Cache::has('email_lock_' . $email)) {
            throw new \Exception('发送太频繁',1000);
        }      

        // 邮箱已注册
        if (User::where('email', $email)->exists()) {
            throw new \Exception('邮箱已被注册',1001);
        }


        //生成验证码
        $code = rand(100000,999999);

        //存缓存
        Cache::put('email_code_' . $email, $code,now()->addMinutes(5));

        //限制60秒
        cache::put('email_lock_' . $email, 1 , 60);

        Mail::to($email)->send(new VerifyCodeMail($code,"注册用户"));

        return ['msg'=>'1'];
    }

    public function register(array $data)
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
    
        //用户名已存在
        if (User::where('username', $data['username'])->exists())
        {
            throw new \Exception('用户名已被注册',1004);
        }

        //获取创建时间
        $createTime = Carbon::now()->format('Y-m-d H:i:s');

        //创建用户
        User::create([
            'username'=> $data['username'],
            'email'=> $data['email'],
            //hash是加密方法，用户加密password
            'password'=> Hash::make($data['password']),
            'nickname'=> $data['nickname'],
            'role'     => 'user',
            'status'   => 1,
            'created_at' => $createTime,
        ]);

        return ['msg'=> '1'];
    }

    //账户密码登录
    public function passwordLogin(array $data) {
        $userName = $data['username'];
        $userPassword = $data['password'];

        $user = User::where('username', $userName)->where('role', 'user')->first();

        if (!$user)
        {
            throw new \Exception('没有该用户',2001);
        }

        //hash::check用于比较hash加密的数据，这里用来比较password
        if (!Hash::check($userPassword , $user->password))
        {
            throw new \Exception('密码错误',2002);
        }

        $token = $user->createToken('login_token')->plainTextToken;

        return ['msg'=> '登陆成功','token'=> $token , 'code' => 2000];
    }

    //验证登录状态
    public function loginIO($user)
    {
        if (!$user)
        {
            throw new \Exception('用户未登录',0000);
        }

        return ['code'=>'0001'];
    }

    //退出登录
    public function logout($user)
    {
        if (!$user)
        {
            throw new \Exception('用户未登录',0000);
        }
        $user->currentAccessToken()->delete();

        return [
            'code' => 200,
            'msg' => '退出登录成功'
        ];
    }

    public function sendEmailCodeToUserName(string $email)
    {

        $user = User::where('email', $email)->where('role','user')->first();
        //查找用户是否存在
        if (!$user) 
        {
            throw new \Exception('没有这个用户',3000);
        }
        // 限流
        if (Cache::has('email_lock_' . $email)) {
            throw new \Exception('发送太频繁',1000);
        }      

        //获取username
        $code = $user->username;

        Mail::to($email)->send(new VerifyCodeMail($code,"找回账户"));

        return ['msg'=>'1'];
    }

    public function sendEmailCodeToPassword(string $email)
    {

        $user = User::where('email', $email)->where('role','user')->first();
        //查找用户是否存在
        if (!$user) 
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

        $user = User::where('email', $data['email'])->first();

        //获取更新时间
        $updateTime = Carbon::now()->format('Y-m-d H:i:s');

        $user->password = Hash::make($data['password']);
        $user->updated_at = $updateTime;

        $user->save();

        return ['msg'=> '1'];
    }

    //查看用户信息
    public function getUserInfo($user)
    {
        if (!$user)
        {
            throw new \Exception('没有登录',0000);
        }

        $return_user = new User();

        $return_user->username = $user->username;
        $return_user->email = $user->email;
        $return_user->nickname = $user->nickname;
        $return_user->avatar = $user->avatar;
        $return_user->status = $user->status;

        $create_time = $user->created_at;

        $diff = $create_time->diffInDays(now());

        $return_user->register_days = (int)$diff;

        return $return_user;
    }

    //修改昵称
    public function updateUserName($user, $data)
    {
        if (!$user)
        {
            throw new \Exception('没有登录',0000);
        }

        $user->nickname = $data['username'];
        $user->save();
        return ['msg'=> '1'];
    }

    //user登录后修改密码
    public function updateUserPassword($user, $data)
    {
        if (!$user)
        {
            throw new \Exception('没有登录',0000);
        }

        $old_password = $data['old_password'];
        $new_password = $data['password'];
        if (!Hash::check($old_password , $user->password))
        {
            throw new \Exception('旧密码错误',4001);
        }
        $user->password = $new_password;
        $user->save();
        return ['code'=> '1'];
    }

    public function resetEmailSendEmail($user,$data)
    {
        $email = $data['email'];
        // 限流
        if (Cache::has('email_lock_' . $email)) {
            throw new \Exception('发送太频繁',1000);
        }      

        // 邮箱已注册
        if (User::where('email', $email)->exists()) {
            throw new \Exception('邮箱已被注册',1001);
        }


        //生成验证码
        $code = rand(100000,999999);

        //存缓存
        Cache::put('email_code_' . $email, $code,now()->addMinutes(5));

        //限制60秒
        cache::put('email_lock_' . $email, 1 , 60);

        Mail::to($email)->send(new VerifyCodeMail($code,"注册用户"));

        return ['msg'=>'1'];
    }

    public function resetEmail($user,$data)
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

        Mail::to($user->email)->send(new VerifyCodeMail(0000,"修改邮箱"));

        $user->email = $data['email'];
        $user->save();

        return ['msg'=> '1'];
    }
}