<?php

namespace App\Services;

use App\Models\User;

class ReputationService
{
    const POINTS = [
        'vote_up_received'   => +2,
        'vote_down_received' => -1,
        'vote_up_cancelled'  => -2,
        'vote_down_cancelled'=> +1,
        'best_answer'        => +5,
        'create_theme'       => +1,
    ];

    public static function updateForVote(
        User   $author,
        string $action
    ): void {
        $points = self::POINTS[$action] ?? 0;
        if ($points === 0) return;

        $author->increment('rating', $points);


        if ($author->rating < 0) {
            $author->update(['rating' => 0]);
        }
    }
}
