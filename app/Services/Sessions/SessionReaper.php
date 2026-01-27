<?php

namespace App\Services\Sessions;

use App\Models\NetworkSession;

class SessionReaper
{
    public function reap(int $minutes = 3): void
    {
        NetworkSession::where('active', true)
            ->where('last_seen_at', '<', now()->subMinutes($minutes))
            ->each(function ($session) {
                $session->update([
                    'active' => false,
                    'ended_at' => now(),
                ]);
            });
    }
}
