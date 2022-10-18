<?php

use think\migration\Migrator;

/**
 * 文章数据
 */
class InstallNews extends Migrator
{
    public function change()
    {
        $this->_news();
        $this->_mark();
        $this->_collect();
    }

    private function _collect()
    {

        // 当前数据表
        $table = 'data_news_x_collect';

        // 存在则跳过
        if ($this->hasTable($table)) return;

        // 创建数据表
        $this->table($table, [
            'engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '数据-文章-标记',
        ])
            ->addColumn('uuid', 'integer', ['limit' => 20, 'default' => 0, 'comment' => '用户UID'])
            ->addColumn('type', 'integer', ['limit' => 1, 'default' => 1, 'comment' => '记录类型(1收藏,2点赞,3历史,4评论)'])
            ->addColumn('code', 'string', ['limit' => 20, 'default' => '', 'comment' => '文章编号'])
            ->addColumn('reply', 'text', ['default' => null, 'comment' => '评论内容'])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1, 'comment' => '记录状态(0无效,1待审核,2已审核)'])
            ->addColumn('create_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'comment' => '创建时间'])
            ->addIndex('type', ['name' => 'idx_data_news_x_collect_type'])
            ->addIndex('code', ['name' => 'idx_data_news_x_collect_code'])
            ->addIndex('status', ['name' => 'idx_data_news_x_collect_status'])
            ->addIndex('uuid', ['name' => 'idx_data_news_x_collect_uuid'])
            ->save();

    }

    private function _mark()
    {
        // 当前数据表
        $table = 'data_news_mark';

        // 存在则跳过
        if ($this->hasTable($table)) return;

        // 创建数据表
        $this->table($table, [
            'engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '数据-文章-标签',
        ])
            ->addColumn('name', 'string', ['limit' => 100, 'default' => '', 'comment' => '标签名称'])
            ->addColumn('remark', 'string', ['limit' => 500, 'default' => '', 'comment' => '标签说明'])
            ->addColumn('sort', 'integer', ['limit' => 20, 'default' => 0, 'comment' => '排序权重'])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1, 'comment' => '标签状态(0禁用,1启用)'])
            ->addColumn('deleted', 'integer', ['limit' => 1, 'default' => 0, 'comment' => '删除状态(0未删,1已删)'])
            ->addColumn('create_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'comment' => '创建时间'])
            ->addIndex('status', ['name' => 'idx_data_news_mark_status'])
            ->addIndex('deleted', ['name' => 'idx_data_news_mark_deleted'])
            ->save();
    }

    private function _news()
    {
        // 当前数据表
        $table = 'data_news_item';

        // 存在则跳过
        if ($this->hasTable($table)) return;

        // 创建数据表
        $this->table($table, [
            'engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '数据-文章-内容',
        ])
            ->addColumn('code', 'string', ['limit' => 20, 'default' => '', 'comment' => '文章编号'])
            ->addColumn('name', 'string', ['limit' => 100, 'default' => '', 'comment' => '文章标题'])
            ->addColumn('mark', 'string', ['limit' => 200, 'default' => '', 'comment' => '文章标签'])
            ->addColumn('cover', 'string', ['limit' => 500, 'default' => '', 'comment' => '文章封面'])
            ->addColumn('remark', 'string', ['limit' => 500, 'default' => '', 'comment' => '备注说明'])
            ->addColumn('content', 'text', ['default' => null, 'comment' => '文章内容'])
            ->addColumn('num_like', 'integer', ['limit' => 20, 'default' => 0, 'comment' => '文章点赞数'])
            ->addColumn('num_read', 'integer', ['limit' => 20, 'default' => 0, 'comment' => '文章阅读数'])
            ->addColumn('num_collect', 'integer', ['limit' => 20, 'default' => 0, 'comment' => '文章收藏数'])
            ->addColumn('num_comment', 'integer', ['limit' => 20, 'default' => 0, 'comment' => '文章评论数'])
            ->addColumn('sort', 'integer', ['limit' => 20, 'default' => 0, 'comment' => '排序权重'])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1, 'comment' => '文章状态(0禁用,1启用)'])
            ->addColumn('deleted', 'integer', ['limit' => 1, 'default' => 0, 'comment' => '删除状态(0未删,1已删)'])
            ->addColumn('create_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'comment' => '创建时间'])
            ->addIndex('code', ['name' => 'idx_data_news_item_code'])
            ->addIndex('status', ['name' => 'idx_data_news_item_status'])
            ->addIndex('deleted', ['name' => 'idx_data_news_item_deleted'])
            ->save();
    }
}