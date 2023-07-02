<?php

namespace App\Imports;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
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
                'type' => 'student',
                'is_wizard_complete' => 1,
            ]);
        }
    }

//    todo: CHECK IF WORKING
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required',
            'gender' => 'required',
        ];
    }
}
