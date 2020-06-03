<?php

namespace App\Http\Controllers\Settings;

use App\Models\Card;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Settings\Requests\UsersChangePasswordRequest;
use App\Http\Controllers\Settings\Requests\UsersStoreRequest;
use App\Http\Controllers\Settings\Requests\UsersUpdateRequest;
use App\Models\Bill;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
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
        $this->middleware('permission:manage-settings', ['except' => [
            'usersChangePasswordDialog',
            'usersChangePassword'
        ]]);
    }

    /**
     * Getting all users and paginate
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function users(Request $request)
    {
        $search = $request->get('search') ?? null;
        $users = User::with('roles');
        if ($search) {
            $users->where('first_name', 'LIKE', '%' . $search . '%')
                ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                ->orWhere('pesel', 'LIKE', '%' . $search . '%');
        }
        $users = $users->paginate(15);
        return view('settings.users.index', compact('users', 'search'));
    }

    /**
     * Getting the user create dialog
     */
    public function usersCreate()
    {
        $roles = Role::pluck('name', 'id');
        return view('settings.users.modals.create', compact('roles'));
    }

    /**
     * Creating new user
     * @param UsersStoreRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function usersStore(UsersStoreRequest $request)
    {
        $validated = $request->validated();

        $user_duplicate = User::where('email', 'LIKE', $validated['email'])
            ->orWhere('pesel', 'LIKE', $validated['pesel'])
            ->exists();
        if ($user_duplicate) {
            return response()->json([
                'status' => 400,
                'error' => __('User with identical data already exists')
            ], 400);
        }

        $user = new User();
        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->pesel = $validated['pesel'];
        $user->address = $validated['address'];
        $user->city = $validated['city'];
        $user->email = $validated['email'];
        $user->zip_code = $validated['zip_code'];
        $user->password = bcrypt(Str::random());
        $user->save();
        $user->assignRole($validated['role_id']);

        // Redirect back if admin
        if ($validated['role_id'] == 1) {
            return response()->json(['status' => 200], 200);
        }

        // Create bill and card if user, and download init user file
        while (true) {
            $bill_number = randomNumber(26);
            if (!Bill::where('number', 'LIKe', $bill_number)->exists()) {
                break;
            }
        }
        $bill = new Bill();
        $bill->number = $bill_number;
        $bill->save();
        $user->bills()->attach($bill);

        while (true) {
            $card_number = randomNumber(16);
            if (!Card::where('number', 'LIKE', $card_number)->exists()) {
                break;
            }
        }
        $card = new Card();
        $card->user_id = $user->id;
        $card->bill_id = $bill->id;
        $card->number = $card_number;
        $card->expiration_date = Carbon::now()->addYears(5)->format('Y-m-d');
        $card->save();

        return response([
            'url' => route('settings.users.download-file', $user->id),
            'download' => true
        ], 200);
    }

    /**
     * Getting the user edit dialog
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function usersEdit(int $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::pluck('name', 'id');
        return view('settings.users.modals.edit', compact('user', 'roles'));
    }

    /**
     * Updating the user
     * @param int $id
     * @param UsersUpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function usersUpdate(int $id, UsersUpdateRequest $request)
    {
        $validated = $request->validated();
        $user = User::findOrFail($id);
        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->pesel = $validated['pesel'];
        $user->address = $validated['address'];
        $user->city = $validated['city'];
        $user->email = $validated['email'];
        $user->zip_code = $validated['zip_code'];
        $user->save();
        $user->syncRoles([$validated['role_id']]);
        return redirect()->back();
    }

    /**
     * Getting the user delete dialog
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function usersDeleteDialog(int $id)
    {
        $user = User::findOrFail($id);
        return view('settings.users.modals.delete', compact('user'));
    }

    /**
     * Deleting the user
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function usersDelete(int $id)
    {
        User::findOrFail($id)->delete();
        return redirect()->back();
    }

    /**
     * Downloading the user account initial document that should be later send by post
     * Every file download results in password change
     * @param int $id
     * @return \Barryvdh\DomPDF\PDF
     */
    public function usersDownloadFile(int $id)
    {
        $user = User::findOrFail($id);
        $password = Str::random();
        $user->password = bcrypt($password);
        $user->save();
        $pdf = PDF::loadView('pdf.user-creation', compact('user', 'password'));
        return $pdf->download("$user->first_name-$user->last_name-$user->pesel.pdf");
    }

    /**
     * Getting the user change password dialog
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function usersChangePasswordDialog(int $id)
    {
        $user = User::findOrFail($id);
        return view('settings.users.modals.change-password', compact('user'));
    }

    /**
     * Changing password of the user
     * @param int $id
     * @param UsersChangePasswordRequest $request
     * @return JsonResponse
     */
    public function usersChangePassword(int $id, UsersChangePasswordRequest $request)
    {
        $validated = $request->validated();
        $user = User::findOrFail($id);
        $user->password = bcrypt($validated['password']);
        $user->save();
        return response()->json(['status' => 200], 200);
    }

    /**
     * Getting the user bills manage dialog
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function usersManageBillsAndCardsDialog(int $id)
    {
        $user = User::findOrFail($id);
        return view('settings.users.modals.manage-bills-and-cards', compact('user'));
    }
}
