<?php

namespace App\Http\Controllers\Bills;

use App\Http\Controllers\Bills\Requests\BillsAttachUserRequest;
use App\Http\Controllers\Bills\Requests\BillsDetachUserRequest;
use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class BillsController extends Controller
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
     * Get all bills, that belongs to user with given id
     * @param int $user_id
     * @return JsonResponse
     */
    public function getAll(int $user_id)
    {
        $bills = Bill::whereHas('users', function($u) use($user_id) {
                $u->whereId($user_id);
            })
            ->get();
        return response()->json([
            'status' => 200,
            'bills' => $bills
        ], 200);
    }

    /**
     * Create new bill
     * @param int $user_id
     * @return JsonResponse
     */
    public function store(int $user_id)
    {
        $user = User::findOrFail($user_id);
        while (true) {
            $bill_number = randomNumber(26);
            if (!Bill::where('number', 'LIKe', $bill_number)->exists()) {
                break;
            }
        }
        $bill = new Bill();
        $bill->number = $bill_number;
        $bill->save();
        $bill->users()->attach($user);
        return response()->json(['status' => 200], 200);
    }

    /**
     * Assign new user to an existing bill
     * @param BillsAttachUserRequest $request
     * @return JsonResponse
     */
    public function attachUser(BillsAttachUserRequest $request)
    {
        $validated = $request->validated();
        Bill::where('number', 'LIKE', $validated['bill_number'])
            ->first()
            ->users()
            ->sync($validated['user_id'], false);
        return response()->json(['status' => 200], 200);
    }

    /**
     * Delete the bill
     * @param int $id
     * @param BillsDetachUserRequest $request
     * @return JsonResponse
     */
    public function delete(int $id, BillsDetachUserRequest $request)
    {
        $validated = $request->validated();
        $bill = Bill::findOrFail($id);
        // Detaching the user and if no users attached anymore - deleting the bill
        $bill->users()->detach($validated['user_id']);
        if ($bill->fresh()->users->count() === 0) {
            $bill->delete();
        }
        return response()->json(['status' => 200], 200);
    }
}
