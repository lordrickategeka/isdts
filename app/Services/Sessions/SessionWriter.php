<?php

namespace App\Services\Sessions;

use App\Models\NetworkSession;
use App\Models\NetworkSessionEvent;
use App\Services\Sessions\Dto\ObservedSession;

class SessionWriter
{
    public function upsert(ObservedSession $obs, ?NetworkSession $existing)
    {
        if (! $existing) {
            $session = NetworkSession::create([
                'router_id' => $obs->routerId,
                'mac_address' => $obs->mac,
                'ip_address' => $obs->ip,
                'username' => $obs->username,
                'access_type' => $obs->accessType,
                'authenticated' => $obs->authenticated,
                'active' => true,
                'started_at' => now(),
                'last_seen_at' => now(),
                'confidence_score' => $obs->confidenceScore(),
                'attributes' => $obs->attributes,
                'interface_name' => $obs->interfaceName,
            ]);

            NetworkSessionEvent::create([
                'network_session_id' => $session->id,
                'router_id' => $obs->routerId,
                'event_type' => 'session_created',
                'source' => $obs->accessType,
                'occurred_at' => now(),
            ]);

            return;
        }

        $before = $existing->toArray();

        $existing->update([
            'ip_address' => $obs->ip ?? $existing->ip_address,
            'username' => $obs->username ?? $existing->username,
            'authenticated' => $obs->authenticated || $existing->authenticated,
            'last_seen_at' => now(),
            'confidence_score' => max(
                $existing->confidence_score,
                $obs->confidenceScore()
            ),
            'attributes' => array_merge(
                $existing->attributes ?? [],
                $obs->attributes
            ),
            'interface_name' => $obs->interfaceName ?? $existing->interface_name,
        ]);

        NetworkSessionEvent::create([
            'network_session_id' => $existing->id,
            'router_id' => $obs->routerId,
            'event_type' => 'session_updated',
            'source' => $obs->accessType,
            'before' => $before,
            'after' => $existing->fresh()->toArray(),
            'occurred_at' => now(),
        ]);
    }
}
