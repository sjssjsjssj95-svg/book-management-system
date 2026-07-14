<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RootService;
use Illuminate\Http\Request;

class RootController extends Controller
{
    //
    protected $rootService;

    public function __construct(RootService $rootService)
    {
        $this->rootService = $rootService;
    }

    public function login(Request $request)
    {
        try {
            $root = $this->rootService->login($request->all());

            return response()->json([
                'code'  => 200,
                'msg'   => '登陆成功',
                'token' => $root['token']
            ]);
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(),'code' => $e->getCode(),]);
        }
    }

    public function loginIO (Request $request)
    {
        $root = $request->root();

        try {
            $root = $this->rootService->loginIO($root);

            return response()->json([
                'msg'=> '已登录',
                'code'=> 0001,
            ]);
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(),'code' => $e->getCode(),]);
        }
    }

    public function getBookTypeNumber (Request $request)
    {
        $root = $request->user();

        try {
            $typeData = $this->rootService->getBookTypeNumber($root);

            return $typeData;
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(),'code' => $e->getCode(),]);
        }
    }

    public function getUserNumber (Request $request)
    {
        $root = $request->user();

        try {
            $userCount = $this->rootService->getUserNumber($root);

            return $userCount;
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(),'code' => $e->getCode(),]);
        }
    }

    public function getBorrowNumber (Request $request)
    {
        $root = $request->user();

        try {
            $typeData = $this->rootService->getBorrowNumber($root);

            return $typeData;
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(),'code' => $e->getCode(),]);
        }
    }

    public function logout(Request $request)
    {
        $root = $request->user();

        try {
            $info = $this->rootService->logout($root);

            return response()->json($info);
        } catch (\Exception $e) {
            return response()->json(['msg'=> $e->getMessage(),'code'=> $e->getCode(),]);
        }
    }

    public function getRootInfo(Request $request)
    {
        $root = $request->user();

        try {
            $return_root = $this->rootService->getRootInfo($root);
            return $return_root;
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(),'code' => $e->getCode()]);
        }
    }

    public function getAllBooks(Request $request)
    {
        $root = $request->user();

        try {
            $return_root = $this->rootService->getAllBooks($root);
            return $return_root;
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(),'code' => $e->getCode()]);
        }
    }

     public function delete_book(Request $request)
    {
        $root = $request->user();

        try {
            $return_root = $this->rootService->delete_book($request->all(),$root);
            return $return_root;
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(),'code' => $e->getCode()]);
        }
    }

    public function get_all_category(Request $request)
    {
        $root = $request->user();

        try {
            $return_root = $this->rootService->get_all_category($root);
            return $return_root;
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(),'code' => $e->getCode()]);
        }
    }

    public function delete_category(Request $request)
    {
        $root = $request->user();

        try {
            $return_root = $this->rootService->delete_category($request->all(), $root);
            return $return_root;
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(),'code' => $e->getCode()]);
        }
    }

    public function add_category(Request $request)
    {
        $root = $request->user();

        try {
            $return_root = $this->rootService->add_category($request->all(), $root);
            return $return_root;
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(),'code' => $e->getCode()]);
        }
    }

    public function add_book(Request $request)
    {
        $root = $request->user();

        try {
            $return_root = $this->rootService->add_book($request->all(), $root);
            return $return_root;
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(),'code' => $e->getCode()]);
        }
    }

    public function add_info(Request $request)
    {
        $root = $request->user();

        try {
            $this->rootService->add_info($request->all(),$root);

            return response()->json([
                'code' => 200,
                'msg' => '添加成功'
            ]);
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(),'code' => $e->getCode()]);
        }
    }

    public function delete_info(Request $request)
    {
        $root = $request->user();

        try {
            $this->rootService->delete_info($request->all(),$root);

            return response()->json([
                'code' => 200,
                'msg' => '删除成功'
            ]);
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(),'code' => $e->getCode()]);
        }
    }

    public function get_all_user(Request $request)
    {
        $root = $request->user();

        try {
            $users = $this->rootService->get_all_user($root);
            
            return $users;
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(),'code' => $e->getCode()]);
        }
    }

     public function user_baned(Request $request)
    {
        $root = $request->user();

        try {
            $users = $this->rootService->user_baned($request->all(),$root);
            
            return $users;
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(),'code' => $e->getCode()]);
        }
    }

    public function user_baned_off(Request $request)
    {
        $root = $request->user();

        try {
            $users = $this->rootService->user_baned_off($request->all(),$root);
            
            return $users;
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(),'code' => $e->getCode()]);
        }
    }
    public function user_delete(Request $request)
    {
        $root = $request->user();

        try {
            $users = $this->rootService->user_delete($request->all(),$root);
            
            return $users;
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(),'code' => $e->getCode()]);
        }
    }

    public function get_all_borrow(Request $request)
    {
        $root = $request->user();

        try {
            $borrows = $this->rootService->get_all_borrow($request->all(),$root);
            
            return $borrows;
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(),'code' => $e->getCode()]);
        }
    }

    public function root_confirm_action(Request $request)
    {
        $root = $request->user();

        try {
            $borrows = $this->rootService->root_confirm_action($request->all(),$root);
            
            return $borrows;
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(),'code' => $e->getCode()]);
        }
    }

    public function updata_book(Request $request)
    {
        $root = $request->user();

        $data = $request->validate([
            'book_id' => 'required',
            'title' => 'required|string',
            'author' => 'required|string',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required',
            'description' => 'required|string',

            // 新图片可选
            'cover' => 'nullable|image|max:5120',
        ]);

        try {
            return $this->rootService->updata_book($data, $root);

        } catch (\Exception $e) {

            return response()->json([
                'msg' => $e->getMessage(),
                'code' => $e->getCode()
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
            $this->rootService->sendEmailCodeToPassword($request->email);

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
        ]);

        try {
            $root = $this->rootService->resetPassword($request->all());

            return response()->json([
                'msg' => '修改成功',
                'code' => 200,
            ]);    
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(),'code' => $e->getCode()]);
        }
    }
}
