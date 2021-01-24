<?php

namespace App\Observers;

use App\Models\Card;
use Carbon\Carbon;

class CardObserver
{
    public function creating(Card $card)
    {
        $card->expiration_date = Carbon::now()->addYears(5);
    }
}
