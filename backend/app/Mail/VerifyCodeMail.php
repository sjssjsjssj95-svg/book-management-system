<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class VerifyCodeMail extends Mailable
{
    public $code; // 给视图用
    public $type;

    public function __construct($code,$type)
    {
        $this->code = $code;
        $this->type = $type;
    }

    public function build()
    {
        if ($this->type == "找回账户")
        {
            return $this->subject('找回账户') // 标题
                        ->view('emails.find_username'); // 模板
        }
        if ($this->type == "借阅书籍")
        {
            return $this->subject('借书成功通知') // 标题
                        ->view('emails.borrow_book'); // 模板
        }
        if ($this->type == "修改邮箱")
        {
            return $this->subject('修改邮箱') // 标题
                        ->view('emails.change_email'); // 模板
        }
        if ($this->type == '封禁用户')
        {
            return $this->subject('封禁用户') // 标题
                        ->view('emails.user_baned'); // 模板
        }
        if ($this->type == '解封用户')
        {
            return $this->subject('解封用户') // 标题
                        ->view('emails.user_baned_off'); // 模板
        }
        if ($this->type == '删除用户')
        {
            return $this->subject('删除用户') // 标题
                        ->view('emails.user_delete'); // 模板
        }
        return $this->subject('邮箱验证码') // 标题
                    ->view('emails.verify_code'); // 模板
    }
}