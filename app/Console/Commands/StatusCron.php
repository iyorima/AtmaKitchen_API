<?php

namespace App\Console\Commands;

use App\Http\Controllers\PesananController;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class StatusCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status:cron';

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
        Log::info("Crob job running " . now());

        // Call the autoUpdateStatueAfter2Days method from PesananController
        $pesananController = new PesananController();
        $pesananController->autoUpdateStatueAfter2Days();
    }
}
