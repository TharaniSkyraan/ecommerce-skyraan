<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Cart;

class ForgetCart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:forget-cart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = Carbon::now()->subDays(15); 
        $carts = Cart::whereNull('reminder')
                      ->where('attempt', 0)
                      ->whereDate('updated_at', '<=', $date)
                      ->groupBy('user_id')
                      ->get(['user_id']);

                      $userIds = $carts->pluck('user_id');
                      return 0;
    }
}
