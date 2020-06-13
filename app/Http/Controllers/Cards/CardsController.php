<?php

namespace App\Http\Controllers\Cards;

use App\Models\Card;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CardsController extends Controller
{
    /**
     * Protects this controller with middleware
     * Only these users which have right permission can call methods inside this class
     */
    public function __construct()
    {
        $this->middleware('permission:manage-settings')->except([
            'toggleActive'
        ]);
    }

    /**
     * Get all cards, that belongs to user with given id
     * @param int $user_id
     * @return JsonResponse
     */
    public function getAll(int $user_id)
    {
        return response()->json([
            'status' => 200,
            'cards' => Card::where('user_id', $user_id)->get()
        ], 200);
    }

    /**
     * Create new card
     * @param int $user_id
     * @param int $bill_id
     * @return JsonResponse
     */
    public function store(int $user_id, int $bill_id)
    {
        while (true) {
            $card_number = randomNumber(16);
            if (!Card::where('number', 'LIKE', $card_number)->exists()) {
                break;
            }
        }
        $card = new Card();
        $card->number = $card_number;
        $card->user_id = $user_id;
        $card->bill_id = $bill_id;
        $card->save();
        return response()->json(['status' => 200], 200);
    }

    /**
     * Delete the card
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id)
    {
        Card::findOrFail($id)->delete();
        return response()->json(['status' => 200], 200);
    }

    /**
     * Toggle the card "active" attribute
     * @param int $id
     * @return JsonResponse
     */
    public function toggleActive(Request $request, int $id)
    {
        $card = Card::findOrFail($id);
        $card->update(['active' => !$card->active]);
        if ($request->ajax()) {
            return response()->json(['status' => 200], 200);
        }
        return redirect()->back();
    }
}
