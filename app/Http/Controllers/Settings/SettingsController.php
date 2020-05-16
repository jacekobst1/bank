<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Settings\Requests\UsersSaveRequest;
use App\Models\User;
use Illuminate\Http\Request;
use PDF;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class SettingsController extends Controller
{
    /**
     * Protects this controller with middleware
     * Only these users which have right permission can call methods inside this class
     */
    public function __construct()
    {
        $this->middleware('permission:manage-settings');
    }

    /**
     * Getting all users and paginate
     */
    public function users(Request $request) {
        $search = $request->get('search') ?? null;
        $users = User::with('roles');
        if ($search) {
            $users->where('first_name', 'LIKE', '%'.$search.'%')
                ->orWhere('last_name', 'LIKE', '%'.$search.'%')
                ->orWhere('pesel', 'LIKE', '%'.$search.'%');
        }
        $users = $users->paginate(15);
        return view('settings.users.index', compact('users', 'search'));
    }

    /**
     * Getting the user create dialog
     */
    public function usersCreate() {
        $roles = Role::pluck('name', 'id');
        return view('settings.users.modals.create', compact('roles'));
    }

    /**
     * Creating new user
     */
    public function usersStore(UsersSaveRequest $request) {
        $validated = $request->validated();

        $user_duplicate = User::where('email', 'LIKE', $validated['email'])
            ->orWhere('pesel', 'LIKE', $validated['pesel'])
            ->exists();
        if ($user_duplicate) {
            return response()->json(['error' => __('User with identical data already exists')], 400);
        }

        $user = new User();
        $user->first_name =     $validated['first_name'];
        $user->last_name =      $validated['last_name'];
        $user->pesel =          $validated['pesel'];
        $user->address =        $validated['address'];
        $user->city =           $validated['city'];
        $user->email =          $validated['email'];
        $user->zip_code =       $validated['zip_code'];
        $user->password =       bcrypt(Str::random());
        $user->save();
        $user->assignRole($validated['role_id']);

        return response([
            'url' => route('settings.users.download-file', $user->id),
            'download' => true
        ], 200);
    }

    /**
     * Getting the user edit dialog
     */
    public function usersEdit($id) {
        $user = User::findOrFail($id);
        $roles = Role::pluck('name', 'id');
        return view('settings.users.modals.edit', compact('user', 'roles'));
    }

    /**
     * Updating the user
     */
    public function usersUpdate($id, UsersSaveRequest $request) {
        $validated = $request->validated();
        $user = User::findOrFail($id);
        $user->first_name =     $validated['first_name'];
        $user->last_name =      $validated['last_name'];
        $user->pesel =          $validated['pesel'];
        $user->address =        $validated['address'];
        $user->city =           $validated['city'];
        $user->email =          $validated['email'];
        $user->zip_code =       $validated['zip_code'];
        $user->save();
        $user->syncRoles([$validated['role_id']]);
        return redirect()->back();
    }

    /**
     * Downloading the user account initial document that should be later send by post
     * Every file download results in password change
     */
    public function usersDownloadFile($id) {
        $user = User::findOrFail($id);
        $password = Str::random();
        $user->password = bcrypt($password);
        $user->save();
        $pdf = PDF::loadView('pdf.user-creation', compact('user', 'password'));
        return $pdf->download("$user->first_name-$user->last_name-$user->pesel.pdf");
    }

    /**
     * Getting the user delete dialog
     */
    public function usersDeleteConfirm($id) {
        $user = User::findOrFail($id);
        return view('settings.users.modals.delete', compact('user'));
    }

    /**
     * Deleting the user
     */
    public function usersDelete($id) {
        User::findOrFail($id)->delete();
        return redirect()->back();
    }
}
