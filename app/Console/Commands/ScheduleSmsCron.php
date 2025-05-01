<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ScheduleSmsCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ScheduleSms:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description: Schedule SMS';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return 0;
    }
}