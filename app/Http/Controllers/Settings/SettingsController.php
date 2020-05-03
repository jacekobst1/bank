<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Settings\Requests\UsersSaveRequest;
use App\Models\User;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage-settings');
    }

    public function users() {
        $users = User::with('roles')->get();
        return view('settings.users.index', compact('users'));
    }
    public function usersCreate() {
        $roles = Role::pluck('name', 'id');
        return view('settings.users.modals.create', compact('roles'));
    }
    public function usersStore(UsersSaveRequest $request) {
        $validated = $request->validated();

        $user = new User();
        $user->first_name =     $validated['first_name'];
        $user->last_name =      $validated['last_name'];
        $user->pesel =          $validated['pesel'];
        $user->address =        $validated['address'];
        $user->city =           $validated['city'];
        $user->email =          $validated['email'];
        $user->zip_code =       substr($validated['zip_code'], 0, 2).substr($validated['zip_code'], 2, 3);
        $user->password =       bcrypt(Str::random());
        $user->save();
        $user->assignRole($validated['role_id']);

        return response(200);
    }
}
