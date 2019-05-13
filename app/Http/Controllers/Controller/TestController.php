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
            curl($url,$b64_str);
    }

    // 登录
    public function login(){
        return view('login.login');
    }

    // 登录执行
    public function loginDo(){
        $data = request()->only('email','pass');
        $arr = DB::table('userinfo')->where(['email' => $data['email']])->first();
        $arr = json_decode(json_encode($arr),true);
        if($arr){
            if(password_verify($data['pass'],$arr['pass1'])){
                // 生成token
                $token = substr(md5(time().$arr['id'].Str::random(10).rand(111,999)),5,20);
                if($token){
                    // 存cookie
                    setcookie('token',$token,time()+604800,'/','1809a.com',false,true);
                    setcookie('id',$arr['id'],time()+604800,'/','1809a.com',false,true);

                    // 存缓存
                    $key = 'token_'.$_SERVER['REMOTE_ADDR'].'_'.$arr['id'];
                    Redis::set($key,$token);
                    Redis::expire($key,604800);
                    die('登录成功');
                }
            }else{
                die('密码错误');
            }
        }else{
            die('账号错误');
        }
    }
}
