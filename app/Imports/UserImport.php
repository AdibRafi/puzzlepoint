<?php

namespace App\Imports;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class UserImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return Model|null
     */
    public function model(array $row): Model|User|null
    {
//        dd($row);
        if (User::where('email', '=', $row[1])->exists()) {
            return null;
        } else {
            return new User([
                'name' => $row[0],
                'email' => $row[1],
                'gender' => $row[2],
                'password' => Hash::make('test1234'),
                'email_verified_at' => now(),
                'type' => 'student',
                'is_wizard_complete' => 1,
            ]);
        }
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required',
            'gender' => 'required',
        ];
    }
}
