<?php

namespace App\Services\Sessions;

use App\Models\RouterSnapshot;

class SessionCorrelationEngine
{
    protected SessionMatcher $matcher;
    protected SessionWriter $writer;

    public function __construct(
        SessionMatcher $matcher,
        SessionWriter $writer
    ) {
        $this->matcher = $matcher;
        $this->writer = $writer;
    }

    public function processSnapshot(RouterSnapshot $snapshot): void
    {
        if (! $snapshot->success) {
            return;
        }

        $normalizer = SessionNormalizerResolver::resolve($snapshot->source);

        if (! $normalizer) {
            return;
        }

        $observedSessions = $normalizer->normalize(
            $snapshot->payload,
            $snapshot->router_id
        );

        foreach ($observedSessions as $obs) {
            $existing = $this->matcher->match($obs);
            $this->writer->upsert($obs, $existing);
        }
    }
}
