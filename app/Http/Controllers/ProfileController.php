<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    public function edit(User $user)
    {
        return inertia('Profile/Profile');
    }

    public function update(ProfileUpdateRequest $request)
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return redirect()->route('profile.edit')
            ->with('alertMessage', 'Profile update successfully');
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        $user->classrooms()->detach();
        $user->assessments()->detach();
        $user->groups()->detach();
        $user->attendances()->delete();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updateWizard()
    {
        Auth::user()->update([
            'wizard_status' => 'done',
        ]);
    }

    public function endWizard()
    {
       $this->updateWizard();

       return \redirect()->route('classroom.index')
           ->with('alertMessage','You have complete the tutorial! Happy Teaching!!!');
    }
}
