<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Transaction;
use Carbon\Carbon;

class ExpireTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark expired pending transactions as expired (auto-hangus after 3 hours)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired transactions...');

        // Find and update expired pending transactions
        $expiredCount = Transaction::where('status', 'pending')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', Carbon::now())
            ->update([
                'status' => 'expired',
                'updated_at' => Carbon::now(),
            ]);

        $this->info("Expired {$expiredCount} transaction(s).");

        return Command::SUCCESS;
    }
}
