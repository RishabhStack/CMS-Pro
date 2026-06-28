<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\PasswordUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends BaseController
{
    public function show()
    {
        try {
            $user = auth()->user()->load(['employee.department', 'employee.designation', 'roles']);

            return $this->view('profile.show', compact('user'));
        } catch (\Exception $e) {
            return $this->error('Failed to load profile.', $e->getMessage());
        }
    }

    public function update(ProfileUpdateRequest $request)
    {
        try {
            $user = auth()->user();
            $user->update($request->validated());

            return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('profile.show')->with('error', 'Failed to update profile.');
        }
    }

    public function password(PasswordUpdateRequest $request)
    {
        try {
            $user = auth()->user();

            $demoEmails = ['owner@example.com', 'admin@example.com', 'employee@example.com'];
            if (in_array($user->email, $demoEmails)) {
                return redirect()->route('profile.show')->with('error', 'Password changes are disabled for demo accounts.');
            }

            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }

            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            return redirect()->route('profile.show')->with('success', 'Password updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('profile.show')->with('error', 'Failed to update password.');
        }
    }

    public function avatar(Request $request)
    {
        try {
            $request->validate([
                'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $user = auth()->user();

            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar' => $path]);

            return $this->success('Avatar updated successfully.', [
                'avatar' => Storage::disk('public')->url($path),
            ]);
        } catch (\Exception $e) {
            return $this->error('Failed to update avatar.', $e->getMessage());
        }
    }
}
