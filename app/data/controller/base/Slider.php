<?php

namespace app\data\controller\base;

use think\admin\Controller;
use think\admin\model\SystemBase;

/**
 * 图片内容管理
 * Class Slider
 * @package app\data\controller\base
 */
class Slider extends Controller
{
    /**
     * 跳转规则定义
     * @var string[]
     */
    protected $rules = [
        '#'  => ['name' => '不跳转'],
        'LK' => ['name' => '自定义链接'],
        'NL' => ['name' => '新闻资讯列表'],
        'NS' => ['name' => '新闻资讯详情', 'node' => 'data/news.item/select'],
    ];

    /**
     * 数据类型
     * @var string
     */
    protected $type = '图片内容';

    /**
     * 页面类型
     * @var array
     */
    protected $types = [];

    /**
     * 控制器初始化
     * @return void
     */
    protected function initialize()
    {
        $this->types = SystemBase::mk()->items($this->type);
        foreach ($this->types as &$type) {
            if (preg_match('/^(.*?)#(\d+)$/', $type['name'], $matches)) {
                $type['name'] = $matches[1];
                $type['number'] = $matches[2];
            } else {
                $type['number'] = 0;
            }
        }
    }

    /**
     * 图片内容管理
     * @auth true
     * @menu true
     */
    public function index()
    {
        $this->title = '图片内容管理';
        $this->fetch();
    }

    /**
     * 编辑图片内容
     * @auth true
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function edit()
    {
        $this->skey = $this->get['type'] ?? '';
        $this->base = $this->types[$this->skey] ?? [];
        if (empty($this->base)) $this->error('未配置基础数据！');
        $this->number = $this->base['number'];
        $this->sysdata();
    }

    /**
     * 保存图片内容
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    private function sysdata()
    {
        if ($this->request->isGet()) {
            $this->data = sysdata($this->skey);
            $this->title = "{$this->base['name']}管理";
            $this->fetch('form');
        } else {
            if (sysdata($this->skey, json_decode(input('data'), true))) {
                $this->success("{$this->base['name']}保存成功！");
            } else {
                $this->error("{$this->base['name']}保存失败，请稍候再试!");
            }
        }
    }
}