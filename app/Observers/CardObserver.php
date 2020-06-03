<?php

namespace App\Observers;

use App\Models\Card;
use App\Models\User;
use Carbon\Carbon;

// Deleting the "-" sign from zip_code which will go to the database
class CardObserver
{
    public function creating(Card $card)
    {
        $card->expiration_date = Carbon::now()->addYears(5);
    }
}
