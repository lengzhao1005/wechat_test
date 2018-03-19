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

    public function oauth_callback($account)
    {
        $app = $this->wechat->app($account);
        $user = $app->oauth->user();
        session(['wechat.oauth_user' => $user->toArray()]);
        //不管在哪个页面检测用户登录状态，都要写入session值：target_url
        $targetUrl = session()->has('target_url') ? session('target_url') : '/' ;
        //header('location:'. $targetUrl);
        return redirect()->to($targetUrl);
    }
}
