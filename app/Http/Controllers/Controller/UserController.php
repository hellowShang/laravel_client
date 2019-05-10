<?php

namespace App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    // 凯撒加密算法

    /**
     * 凯撒加密
     * @param $str  原始数据
     * @param int $n 偏移量
     * @return string 返回加密密文
     */
    public function caesarEncryopt($str,$n=1){
        // 获取长度
        $length = strlen($str);
        $pass = '';
        for($i=0;$i<$length;$i++){
            // ord 返回字符的 ASCII 码值
            // chr 返回相对应于 ascii 所指定的单个字符。
            $pass .= chr(ord($str[$i])+$n);
        }
        return $pass;
    }

    /**
     * 凯撒解密
     * @param $str 加密密文
     * @param int $n 偏移量
     * @return string 返回解密密文
     */
    public function caesarDecrypt($str,$n=1){
        // 获取长度
        $length = strlen($str);
        $pass = '';
        for($i=0;$i<$length;$i++){
            // ord 返回字符的 ASCII 码值
            // chr 返回相对应于 ascii 所指定的单个字符。
            $pass .= chr(ord($str[$i])-$n);
        }
        return $pass;
    }

    public function index(){
        $a = 'Hellow World';
        $n = 3;
        $str = $this->caesarEncryopt($a,$n);
        echo '加密密文：'.$str."<br / >";
        $str1 = $this->caesarDecrypt($str,$n);
        echo '解密密文：'.$str1."<br / >";
    }

    /**
     * 对称加密
     * @param $data 原始数据
     * @return string 加密密文
     */
    public function encrypt(){
        // 数据
        $data = [
            'name'      =>  '啦啦啦',
            'email'     =>  'lalala@qq.com',
            'num'       =>  '63456829938249293859823',
            'tel'       =>  '17898237764'
        ];

        // 转化成json
        $json_str = json_encode($data,JSON_UNESCAPED_UNICODE);

        // key
        $key = 'CBCENCRYPT';
        // 密码学方式 使用AES-128-CBC加密算法
        $method = 'AES-128-CBC';
        // OPENSSL_RAW_DATA 或 OPENSSL_ZERO_PADDING 加密解密须一致
        $options = OPENSSL_RAW_DATA;
        // 非空的初始化向量 16位
        $iv = 'abcdefghijklmnop';

        // 返回加密后的密文 方便进行传输密文使用base64算法
        $encrypt_str = base64_encode(openssl_encrypt($json_str,$method,$key,$options,$iv));

        // 请求接口
        $url = 'http://api.1809a.com/user/decrypt';

        // curl发送数据
        curl($url,$encrypt_str);
    }

    // 非对称加密后发送数据请求服务端
    public function keyEncrypt(){
        // 数据
        $data = [
            'name'      =>  '张氏五',
            'email'     =>  'zhangsiwu@qq.com',
            'num'       =>  '63456829938249293859823',
            'tel'       =>  '17898237764'
        ];

        // 转化成json字符串
        $json_str = json_encode($data,JSON_UNESCAPED_UNICODE);

        // 获取私钥
        $privatete_key = openssl_get_privatekey('file://'.storage_path('app/keys/private.pem'));

        // 用私钥加密数据并转化成base64字符串
        openssl_private_encrypt($json_str,$encrypt_str,$privatete_key);
        $encrypt_str = base64_encode($encrypt_str);

        // 请求接口
        $url = 'http://api.1809a.com/user/dec';

        // 使用curl全局函数发送数据
        curl($url,$encrypt_str);
    }

    // 签名
    public function sign(){
        // 数据
        $data = [
            'name'      =>  '沙雕',
            'email'     =>  'shadiao@qq.com',
            'num'       =>  '63456829938249293859823',
            'tel'       =>  '17898237764'
        ];

        // 转化成json字符串
        $json_str = json_encode($data,JSON_UNESCAPED_UNICODE);

        // 获取私钥
        $key = openssl_get_privatekey('file://'.storage_path('app/keys/private.pem'));

        // 签名
        openssl_sign($json_str,$sign,$key);

        // 转码成base64
        $encrypt_str = base64_encode($sign);

        // 请求路由
        $url = 'http://api.1809a.com/user/sign?sign='.urlencode($encrypt_str);

        // curl发送请求
        curl($url,$json_str);
    }
}

