<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WizardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'adib',
            'email' => 'adibtest@gmail.com',
            'password' => bcrypt('test1234'),
            'type' => 'lecturer',
            'wizard_status' => 'onCreateClassroom',
            'is_wizard_complete' => 0
        ]);

        User::factory(19)->create([
            'type' => 'studentDummy',
            'wizard_status' => 'dummy',
            'is_wizard_complete' => 1
        ]);
    }
}
