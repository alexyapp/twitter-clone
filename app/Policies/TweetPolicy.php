<?php

namespace App\Policies;

use App\Models\Tweet;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TweetPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can modify or delete the model.
     *
     * @return mixed
     */
    public function can_modify_or_delete(User $user, Tweet $tweet)
    {
        return $user->id == $tweet->user_id;
    }
}
