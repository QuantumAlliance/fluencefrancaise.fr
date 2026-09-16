<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoogleMeetService;
use Illuminate\Support\Facades\Log;

class SyncMeetLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-meet-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Google Meet attendance logs from Google API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Google Meet logs sync...');
        
        try {
            $service = new GoogleMeetService();
            $result = $service->syncMeetLogs();
            
            Log::info('Scheduled Meet logs sync completed', $result);
            $this->info(sprintf(
                'Sync completed: %d inserted, %d updated, %d errors',
                $result['inserted'],
                $result['updated'],
                $result['errors']
            ));
        } catch (\Exception $e) {
            Log::error('Scheduled Meet logs sync failed: ' . $e->getMessage());
            $this->error('Sync failed: ' . $e->getMessage());
        }
    }
}
