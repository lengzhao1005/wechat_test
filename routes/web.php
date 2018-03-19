<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

   /* //1. 将timestamp , nonce , token 按照字典排序
    $timestamp = $_GET['timestamp'];
    $nonce = $_GET['nonce'];
    $token = "你自定义的Token值 用于验证";
    $signature = $_GET['signature'];
    $array = array($timestamp,$nonce,$token);
    sort($array);

    //2.将排序后的三个参数拼接后用sha1加密
    $tmpstr = implode('',$array);
    $tmpstr = sha1($tmpstr);

    //3. 将加密后的字符串与 signature 进行对比, 判断该请求是否来自微信
    if($tmpstr == $signature)
    {
        echo $_GET['echostr'];
        exit;
    }*/

    return view('welcome');
});
