<?php

namespace App\Http\Controllers\WeChat;

use App\Handlers\WechatConfigHandlers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WechatController extends Controller
{
    protected $wechat;

    public function __construct(WechatConfigHandlers $wechat)
    {
       /* //1. 将timestamp , nonce , token 按照字典排序
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];
        $token = "bei0501zhao";
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
        $this->wechat = $wechat;
    }

    public function serve($account)
    {
        $app = $this->wechat->app($account);

        $app->server->push(function($message){
            switch ($message['MsgType']) {
                case 'event':
                    if ($message->Event == 'subscribe') {
                        return '欢迎关注 lz！';
                    }
                    break;
                case 'text':
                    return '收到文字消息';
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }
        });

        $response = $app->server->serve();
        return $response;
    }

    public function oauth_callback($account,Request $request)
    {
        $app = $this->wechat->app($account);

        $response = $app->oauth->scopes(['snsapi_userinfo'])
            ->redirect();

        //回调后获取user时也要设置$request对象
        $user = $app->oauth->user();
        dd($user);
        session(['wechat.oauth_user' => $user->toArray()]);
        //不管在哪个页面检测用户登录状态，都要写入session值：target_url
        $targetUrl = session()->has('target_url') ? session('target_url') : '/' ;
        //header('location:'. $targetUrl);
        return redirect()->to($targetUrl);
    }
}
