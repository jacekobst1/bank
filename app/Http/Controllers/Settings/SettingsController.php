<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Settings\Requests\UsersSaveRequest;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use PDF;
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
        $password = Crypt::encryptString(Str::random());

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
        $user->zip_code =       substr($validated['zip_code'], 0, 2).substr($validated['zip_code'], 2, 3);
        $user->password =       $password;
        $user->save();
        $user->assignRole($validated['role_id']);

        return response([
            'url' => route('settings.users.download-file', $user->id),
            'download' => true
        ], 200);
    }
    public function usersDownloadFile($id) {
        $user = User::findOrFail($id);
        $password = Crypt::decryptString($user->password);
        $pdf = PDF::loadView('pdf.user-creation', compact('user', 'password'));
        return $pdf->download("$user->first_name-$user->last_name-$user->pesel.pdf");
        // TODO ostyluj pdfa i sprawdź czy działa logowanie usera (Crypt::decryptString)
    }
}
