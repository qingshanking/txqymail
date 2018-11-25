<?php
/*
*腾讯企业邮箱接口 - 单点登录实现
*开发文档：https://exmail.qq.com/qy_mng_logic/doc#10001
*作者：萧瑟
*版本：v0.2.18.11.25
*博客：http://qsh5.cn
*时间：2018年11月25日 15:54:44
*/

//配置企业id
$corpid='';
//配置(单点登录)应用的凭证密钥
$corpsecret='';
$mail = $_GET['mail'];//获取地址栏传入的用户邮箱帐号(即成员UserID)
if($mail == ""){
    echo "请输入要登陆的帐号";
    return;
}
//根据开发文档介绍 第一步获取access_token
//拼凑Url地址
$get_access_token_url = 'https://api.exmail.qq.com/cgi-bin/gettoken?corpid='.$corpid.'&corpsecret='.$corpsecret;
//将获取到的数据转化为Json
$access_token_json = json_decode(file_get_contents($get_access_token_url));
//查询获取的状态，成功时：errmsg会返回 ok
$state= $access_token_json->errmsg;
if($state == "ok"){//判断当前获取数据是否成功
    //获取access_token值
    $access_token = $access_token_json->access_token;
    //根据access_token和之前转入的UserId(邮箱帐号)拼凑请求地址
    $login_url='https://api.exmail.qq.com/cgi-bin/service/get_login_url?access_token='.$access_token.'&userid='.$mail;
    //将请求后的数据转化为Json
    $login_url_json = json_decode(file_get_contents($login_url));
    //判断状态 跟第一步请求一样 成功后，会在返回的数据中返回 Ok
    if($login_url_json->errmsg=="ok"){
        //成功后，之间跳转到接口返回的地址 即可成功，第一次登陆 有一个验证 验证完成就可以正常使用了。
        header("location:".$login_url_json->login_url);
     }
     else{
        echo "获取登录地址失败";    
     }    
}else{
    echo "获取access_token失败！";
}
?>
