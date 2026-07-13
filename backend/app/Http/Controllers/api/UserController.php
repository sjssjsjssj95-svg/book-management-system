<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    //
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function sendEmailCodeToRegister(Request $request)
    {
        $request->validate([
            "email"=> "required|email",
        ]);

        try {
            $this->userService->sendEmailCodeToRegister($request->email);

            return response()->json([
                'code' => 200,
                'msg'  => '发送成功'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'code' => $e->getCode(),
                'msg'=> $e->getMessage()
            ]);
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|min:3',
            'email'    => 'required|email',
            'password' => 'required|min:6',
            'nickname'=> 'required|min:1',
            'code'     => 'required'
        ],[
            'username.required' => '用户名不能为空',
            'username.min'      => 'username最少3位',

            'nickname.required' => '昵称不能为空',
            'nickname.min'      => '昵称最少1位',

            'password.required' => '密码不能为空',
            'password.min'      => 'password最少6位',
        ]);

        try {
            $user = $this->userService->register($request->all());

            return response()->json([
                'msg' => '注册成功',
                'code' => 200,
            ]);    
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(),'code' => $e->getCode()]);
        }
    }

    //登录
    public function passwordLogin(Request $request)
    {
        try {
            $user = $this->userService->passwordLogin($request->all());

            return response()->json([
                'code'=> 200,
                'msg'=> '登录成功',
                'token'=>$user['token']
            ]);
        }   catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(),'code' => $e->getCode(),]);
        }
    }

    public function loginIO(Request $request)
    {
        $user = $request->user();

        try {
            $user = $this->userService->loginIO($user);

            return response()->json([
                'msg'=> '已登录',
                'code'=> 0001,
            ]);
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(),'code' => $e->getCode(),]);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        try {
            $info = $this->userService->logout($user);

            return response()->json($info);
        } catch (\Exception $e) {
            return response()->json(['msg'=> $e->getMessage(),'code'=> $e->getCode(),]);
        }
    }

    public function sendEmailCodeToUserName(Request $request)
    {
        $request->validate([
            "email"=> "required|email",
        ]);

        try {
            $this->userService->sendEmailCodeToUserName($request->email);

            return response()->json([
                'code' => 200,
                'msg'  => '发送成功'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'code' => $e->getCode(),
                'msg'=> $e->getMessage()
            ]);
        }
    }

    //重置密码
    //发送邮件
    public function sendEmailCodeToPassword(Request $request)
    {
        $request->validate([
            "email"=> "required|email",
        ]);

        try {
            $this->userService->sendEmailCodeToPassword($request->email);

            return response()->json([
                'code' => 200,
                'msg'  => '发送成功'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'code' => $e->getCode(),
                'msg'=> $e->getMessage()
            ]);
        }
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
            'code'     => 'required'
        ],[
            'username.required' => '用户名不能为空',
            'username.min'      => 'username最少3位',

            'password.required' => '密码不能为空',
            'password.min'      => 'password最少6位',
        ]);

        try {
            $user = $this->userService->resetPassword($request->all());

            return response()->json([
                'msg' => '修改成功',
                'code' => 200,
            ]);    
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(),'code' => $e->getCode()]);
        }
    }

    public function getUserInfo(Request $request)
    {
        $user = $request->user();

        try {
            $return_user = $this->userService->getUserInfo($user);
            return $return_user;
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(),'code' => $e->getCode()]);
        }
    }

    public function updateUserName(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'username'    => 'required|min:2',
        ]);

        try { 
            $user = $this->userService->updateUserName($user,$request->all());
            return response()->json([
                'msg' => '修改成功',
                'code' => 200,
            ]); 
        }   catch (\Exception $e) {
            return response()->json(['msg'=> $e->getMessage(),'code'=> $e->getCode()]);
        }
    }

    //修改头像
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $user = $request->user();

        // 删除旧头像（如果有）
        if ($user->avatar!='avatars/mr.jpg')
        {
            if ($user->avatar && file_exists(public_path('storage/'.$user->avatar))) {
                unlink(public_path('storage/'.$user->avatar));
            }
        }

        // 存新头像
        $path = $request->file('avatar')->store('avatars', 'public');

        // 保存路径到数据库
        $user->avatar = $path;
        $user->save();

        return response()->json([
            'message' => '上传成功',
            'code' => 200
        ]);
    }

    public function updateUserPassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'password'    => 'required|min:6',
        ]);

        try { 
            $this_new = $this->userService->updateUserPassword($user,$request->all());
            return $this_new; 
        }   catch (\Exception $e) {
            return response()->json(['msg'=> $e->getMessage(),'code'=> $e->getCode()]);
        }
    }

    public function resetEmailSendEmail(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'email'  => 'required|email',
        ]);

        try { 
            $this_new = $this->userService->resetEmailSendEmail($user,$request->all());
            return $this_new; 
        }   catch (\Exception $e) {
            return response()->json(['msg'=> $e->getMessage(),'code'=> $e->getCode()]);
        }
    }

    public function resetEmail(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'email'  => 'required|email',
        ]);

        try { 
            $this_new = $this->userService->resetEmail($user,$request->all());
            return $this_new; 
        }   catch (\Exception $e) {
            return response()->json(['msg'=> $e->getMessage(),'code'=> $e->getCode()]);
        }
    }
}
