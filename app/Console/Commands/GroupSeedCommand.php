<?php

namespace App\Console\Commands;

use Database\Seeders\GroupSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GroupSeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'group:seed {--students=} {--modules=} {--fixed_student=}';

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
//        dd((bool)$this->option('fixed_student'));

        $this->call('migrate:refresh');
        (new GroupSeeder())->run($this->option('students'),
            $this->option('modules'),
            (bool)$this->option('fixed_student'));
        $this->output->info('Done migrate and seed');
        return 0;
    }
}
