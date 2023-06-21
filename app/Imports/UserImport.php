<?php

namespace App\Imports;

use App\Models\Classroom;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;

class UserImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (User::where('email', '=', $row[1])->exists()) {
            return null;
        } else {
            return new User([
                'name' => $row[0],
                'email' => $row[1],
                'type' => 'student',
                'is_wizard_complete' => 1,
            ]);
        }
    }
}
