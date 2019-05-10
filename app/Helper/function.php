<?php
function curl($url,$encrypt_str){
    // 1. 初始化
    $ch = curl_init();

    // 2. 设置选项参数
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$encrypt_str);
    curl_setopt($ch,CURLOPT_HTTPHEADER,['Content-type:text/plain']);

    // 3. 获取并抛出错误
    $num = curl_errno($ch);
    if($num>0){
        echo 'cURL错误码：'.$num;exit;
    }

    // 4. 发起请求
    curl_exec($ch);

    // 5. 关闭并释放资源
    curl_close($ch);
}