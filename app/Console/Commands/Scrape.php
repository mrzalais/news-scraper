<?php

namespace App\Console\Commands;

use App\Http\Controllers\ScraperController;
use Illuminate\Console\Command;

class Scrape extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:scrape';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $scraperController = new ScraperController();
        $scraperController->scrape();

        return self::SUCCESS;
    }
}
