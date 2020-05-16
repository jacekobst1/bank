<?php

namespace App\Observers;

use App\Models\User;

// Deleting the "-" sign from zip_code which will go to the database
class UserObserver
{
    public function creating(User $user)
    {
        $dirty = $user->getDirty();
        if (isset($dirty['zip_code'])) {
            $user->zip_code = substr($dirty['zip_code'], 0, 2).substr($dirty['zip_code'], 3, 3);
        }
    }

    public function updating(User $user)
    {
        $dirty = $user->getDirty();
        if (isset($dirty['zip_code'])) {
            $user->zip_code = substr($dirty['zip_code'], 0, 2).substr($dirty['zip_code'], 3, 3);
        }
    }
}
