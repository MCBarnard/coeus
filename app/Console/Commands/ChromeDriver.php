<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ChromeDriver extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chrome-driver:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts the selenium session';

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $path = Storage::path("Selenium/ChromeDriver/chromedriver");
        $this->info(print_r("$path --port=9595", true));
        shell_exec("$path --port=9595");
    }
}
