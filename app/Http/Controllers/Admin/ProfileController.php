<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('admin.profile.edit', ['admin' => Auth::guard('admin')->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $admin = Auth::guard('admin')->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('admins')->ignore($admin->id)],
            'profile_image' => ['nullable', 'image', 'max:4096'],
            'current_password' => ['nullable', 'required_with:password', 'current_password:admin'],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        $admin->name = $data['name'];
        $admin->email = $data['email'];

        if (! empty($data['password'])) {
            $admin->password = Hash::make($data['password']);
        }

        if ($request->hasFile('profile_image')) {
            if ($admin->profile_image && File::exists(public_path($admin->profile_image))) {
                File::delete(public_path($admin->profile_image));
            }

            $image = $request->file('profile_image');
            $filename = 'admin-' . $admin->id . '-' . Str::random(8) . '.' . $image->getClientOriginalExtension();
            $directory = public_path('data/admin');

            File::ensureDirectoryExists($directory);
            $image->move($directory, $filename);

            $admin->profile_image = 'data/admin/' . $filename;
        }

        $admin->save();

        return back()->with('status', 'Profile updated successfully.');
    }
}
