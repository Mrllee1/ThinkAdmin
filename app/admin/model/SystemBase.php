<?php

// +----------------------------------------------------------------------
// | ThinkAdmin
// +----------------------------------------------------------------------
// | 版权所有 2014~2021 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方网站: https://thinkadmin.top
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// | 免费声明 ( https://thinkadmin.top/disclaimer )
// +----------------------------------------------------------------------
// | gitee 代码仓库：https://gitee.com/zoujingli/ThinkAdmin
// | github 代码仓库：https://github.com/zoujingli/ThinkAdmin
// +----------------------------------------------------------------------

namespace app\admin\model;

use think\Model;

/**
 * 数据字典数据模型
 * Class SystemBase
 * @package app\admin\model
 */
class SystemBase extends Model
{
    /**
     * 获取指定数据列表
     * @param string $type 数据类型
     * @param array $data 外围数据
     * @param string $field 外链字段
     * @param string $bind 绑定字段
     * @return array
     */
    public function items(string $type, array &$data = [], string $field = 'base_code', string $bind = 'base_info'): array
    {
        $map = ['status' => 1, 'deleted' => 0, 'type' => $type];
        $bases = $this->where($map)->order('sort desc,id desc')->column('code,name,content', 'code');
        if (count($data) > 0) foreach ($data as &$vo) $vo[$bind] = $bases[$vo[$field]] ?? [];
        return $bases;
    }

    /**
     * 格式化创建时间
     * @param string $value
     * @return string
     */
    public function getCreateAtAttr(string $value): string
    {
        return format_datetime($value);
    }
}