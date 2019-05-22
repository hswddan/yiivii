<?php
namespace app\index\controller;

use think\Controller;
use think\Cache;
use QL\QueryList;
use app\common\model\Order;
use app\common\controller\Common;
class Index extends Common
{
    /**
     * @return mixed
     */
    public function index()
    {
        $list = Order::order('id desc')->limit(15)->select();
        $count = Order::where('uid',session('uid'))->count();
        $data['count'] = $count;
        $data['list'] = $list;
        $data['pp_day'] = cache('pp_day') ?: '数据加载中...';
        $data['pp_week'] = cache('pp_week') ?: '数据加载中...';
        return view('',$data);
    }

    /**
     * @return mixed
     */
    public function howtouse()
    {
        return view('');
    }

    /**
     * 枢轴点show
     * @param $t
     * @return mixed
     */
    public function pp($t)
    {
        $data['pp'] = Cache::get('pp_'.$t) ?: '数据加载中...';
        return view('',$data);
    }

    /**
     * 科普 枢轴点
     * main-content
     */
    public function baike()
    {
        $url = 'https://baike.baidu.com/item/%E6%9E%A2%E8%BD%B4%E7%82%B9/6689552';
        //写入缓存，时间3600
        Cache::remember('baike',function() use ($url){
            $ql = QueryList::get($url);
            return $ql->find('.main-content')->html();
        },3600);
        $data['url'] = $url;
        $data['baike'] = Cache::get('baike') ?: '数据加载中...';
        return view('',$data);
    }

    /**
     * 设置pp
     * 86400 每日
     * week 每周
     */
    public function set_pp($t = 'day')
    {
        $ql = QueryList::getInstance();
        $post['period'] =  $t == 'day' ? '86400' : 'week';
        $html = callapi('https://www.yiivii.com/investing/technical/pivot-points',$post);
        $ql->setHtml($html);
        $data = $ql->find('#curr_table')->html();
        Cache::set('pp_'.$t, $data);
        echo 'set pp ok ';
        echo date('Y-m-d H:i:s');
    }
}