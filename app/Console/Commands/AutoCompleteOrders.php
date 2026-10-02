<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AutoCompleteOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:autocomplete {--days=2 : Number of days after shipping to auto-complete}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically complete orders that have been shipped for X days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $date = now()->subDays($days);

        $this->info("Checking for orders shipped before {$date->toDateTimeString()}...");

        $orders = \App\Models\Pesanan::where('status_pesanan', 'dikirim')
            ->whereDate('tanggal_dikirim', '<=', $date)
            ->get();

        if ($orders->isEmpty()) {
            $this->info("No orders found to auto-complete.");
            return;
        }

        $count = 0;
        foreach ($orders as $order) {
            $order->update(['status_pesanan' => 'selesai']);
            $this->line("Order #{$order->nomor_pesanan} completed.");
            $count++;
        }

        $this->info("Successfully completed {$count} orders.");
    }
}
