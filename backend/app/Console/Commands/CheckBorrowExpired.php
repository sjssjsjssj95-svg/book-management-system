<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Borrow;
use Carbon\Carbon;

class CheckBorrowExpired extends Command
{
    protected $signature = 'borrow:check-expired';

    protected $description = '检查借阅是否过期';

    public function handle()
    {
        // 当前时间减5天
        $expireDate = Carbon::now()->subDays(5);

        $count = Borrow::where('status', 0)
            ->where('borrow_time', '<', $expireDate)
            ->update([
                'status' => 2
            ]);

        $this->info("更新 {$count} 条过期借阅");

        return 0;
    }
}