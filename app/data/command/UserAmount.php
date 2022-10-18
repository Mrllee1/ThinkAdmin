<?php

namespace app\data\command;

use app\data\model\DataUser;
use app\data\service\UserBalanceService;
use app\data\service\UserRebateService;
use think\admin\Command;
use think\console\Input;
use think\console\Output;

/**
 * 用户余额及返利重算处理
 * Class UserBalance
 * @package app\data\command
 */
class UserAmount extends Command
{
    protected function configure()
    {
        $this->setName('xdata:UserAmount');
        $this->setDescription('批量重新计算余额返利');
    }

    /**
     * @param \think\console\Input $input
     * @param \think\console\Output $output
     * @return void
     * @throws \think\admin\Exception
     * @throws \think\db\exception\DbException
     */
    protected function execute(Input $input, Output $output)
    {
        [$total, $count, $error] = [DataUser::mk()->count(), 0, 0];
        foreach (DataUser::mk()->field('id')->cursor() as $user) try {
            $this->queue->message($total, ++$count, "刷新用户 [{$user['id']}] 余额及返利开始");
            UserRebateService::amount($user['id']) && UserBalanceService::amount($user['id']);
            $this->queue->message($total, $count, "刷新用户 [{$user['id']}] 余额及返利完成", 1);
        } catch (\Exception $exception) {
            $error++;
            $this->queue->message($total, $count, "刷新用户 [{$user['id']}] 余额及返利失败, {$exception->getMessage()}", 1);
        }
        $this->setQueueSuccess("此次共处理 {$total} 个刷新操作, 其中有 {$error} 个刷新失败。");
    }
}