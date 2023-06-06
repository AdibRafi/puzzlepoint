<?php

namespace App\Console\Commands;

use Database\Seeders\GroupSeeder;
use Illuminate\Console\Command;

class GroupSeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'group:seed {--students=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To migrate refresh and add in number of students';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('migrate:refresh');
        (new GroupSeeder())->run($this->option('students'));
        return 0;
    }
}
