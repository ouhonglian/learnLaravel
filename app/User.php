<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class User extends Model
{
    //判断数据是否存在
    function has_username_has_password(){
        $username = Request::get('username');
        $password = Request::get('password');
        if($username&&$password)
            return [$username,$password];
        return false;
    }
    //注册
    public function register()
    {
        //首先判断数据是否为空
        $chek=$this->has_username_has_password();
        $username=$chek[0];
        $password=$chek[1];
        if (!$chek)
            return ['status' => 100, 'msg' => '用户名和密码不能为空'];

        //判断数据是否存在
        $user_exists = $this->where(['username' => $username])->exists();
        if ($user_exists) return '用户名已存在';

        //加密pass
        $hashed_password = Hash::make($password);
        //存入数据库
        $this->username = $username;
        $this->password = $hashed_password;
        $this->email=Request::get('email');
        $this->phone=Request::get('phone');
        if ($this->save()) {
            return ['status' => 1, 'id' => $this->id, 'msg' => '注册成功'];
        } else {
            return '未知原因导致失败';
        }
    }

    //登录
    public function login(){
        //首先判断数据是否为空
        $chek=$this->has_username_has_password();
        $username=$chek[0];
        $password=$chek[1];
        if (!$chek)
            return ['status' => 0, 'msg' => '用户名和密码不能为空'];
        //判断用户名和密码是否正确
        $ret=$this->where('username',$username)->first();
        if(!$ret||!Hash::check($password,$ret->password)) return ['status'=>0,'info'=>'用户名或密码错误'];
        //写入session
        Session::put('username',$ret->username);
        Session::put('user_id',$ret->id);
        return ['status' => 1, 'msg' => '登录成功'];
    }
    //检测用户是否登录
    function is_login(){
        return Session::get('user_id')?:false;
    }
    //退出登录
    public function out(){
        Session::forget('username');
        Session::forget('user_id');
        dd(Session::all());
    }
}
