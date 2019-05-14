<?php

namespace App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class TestController extends Controller
{
    // 注册
    public function reg(){
        return view('login.reg');
    }

    // 注册执行
    public function register(){
            header('Access-Control-Allow-Origin:http://127.0.0.1:8020');

            $data = request()->all();
            if($data['pass1'] != $data['pass2']){
                die('两次密码输入不一致');
            }
            $res = DB::table('userinfo')->where(['email' => $data['email']])->first();
            if($res){
                die('该邮箱已经注册过了');
            }
            unset($data['pass2']);
            unset($data['_token']);
            $data['pass1'] = password_hash($data['pass1'],PASSWORD_BCRYPT);

            // 非对称加密
            $json_str = json_encode($data,JSON_UNESCAPED_UNICODE);
            $key = openssl_get_privatekey('file://'.storage_path('app/keys/private.pem'));
            openssl_private_encrypt($json_str,$encrypt,$key);
            $b64_str = base64_encode($encrypt);
            $url = 'http://api.1809a.com/reg';
            $str = curl($url,$b64_str);
            $arr = json_decode($str,true);
            if($arr['errcode'] == 0){
                die($arr['msg']);
            }
    }

    // 登录
    public function login(){
        return view('login.login');
    }

    // 登录执行
    public function loginDo(){
        $data = request()->only('email','pass');
        $key = openssl_get_privatekey('file://'.storage_path('app/keys/private.pem'));
        openssl_private_encrypt(json_encode($data),$encrypt,$key);
        $b64 = base64_encode($encrypt);
        $url = 'http://api.1809a.com/login';
        $str = curl($url,$b64);
        var_dump($str);
    }

}
