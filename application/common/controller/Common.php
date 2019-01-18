<?php
namespace app\common\controller;
use think\Controller;
use app\common\model\Member;
class Common extends Controller {
    protected function initialize() {
        /**
         * 系统状态初始化
         * 1000 游客状态
         * 1001 登陆状态
         */
        //读取cookie
        $email = cookie('email');
        $uid = cookie('uid');
        //写入session
        if(!empty($email) && session('status') != 1001){
            $user = Member::get(['email'=>$email,'uid'=>$uid]);
            session('uid',$user->id);
            session('email',$email);
            session('status',1001);
        }
    }
}