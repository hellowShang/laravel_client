<?php

/**
 * curl  post发送数据
 * @param $url
 * @param $encrypt_str
 * @return bool|string
 */
function curl($url,$encrypt_str){
    // 1. 初始化
    $ch = curl_init();

    // 2. 设置选项参数
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$encrypt_str);
    curl_setopt($ch,CURLOPT_HTTPHEADER,['Content-type:text/plain']);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    // 3. 获取并抛出错误
    $num = curl_errno($ch);
    if($num>0){
        echo 'cURL错误码：'.$num;exit;
    }

    // 4. 发起请求
    $str = curl_exec($ch);
    return $str;
    // 5. 关闭并释放资源
    curl_close($ch);
}