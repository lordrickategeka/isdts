<?php

namespace App\Services\Sessions;

use App\Models\NetworkSession;
use App\Services\Sessions\Dto\ObservedSession;

class SessionMatcher
{
    public function match(ObservedSession $obs): ?NetworkSession
    {
        return NetworkSession::where('router_id', $obs->routerId)
            ->where('active', true)
            ->where(function ($q) use ($obs) {
                if ($obs->mac) {
                    $q->where('mac_address', $obs->mac);
                }
                if ($obs->username) {
                    $q->orWhere('username', $obs->username);
                }
                if ($obs->ip) {
                    $q->orWhere('ip_address', $obs->ip);
                }
            })
            ->orderByDesc('confidence_score')
            ->first();
    }
}
