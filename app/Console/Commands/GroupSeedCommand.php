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
        $this->call('migrate:refresh');
        (new GroupSeeder())->run($this->option('students'),
            $this->option('fixed_student'),
            $this->option('modules'));
        $this->output->write('Done migrate and seed', true);
        return 0;
    }
}
