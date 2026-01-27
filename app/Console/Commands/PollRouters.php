<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use App\Models\Router;
use App\Services\Routers\RouterSnapshotService;
use App\Services\Sessions\SessionCorrelationEngine;

class PollRouters extends Command
{
    protected $signature = 'routers:poll';
    protected $description = 'Poll all routers for enabled truth sources';

    public function handle(
        RouterSnapshotService $snapshots,
        SessionCorrelationEngine $correlator
    ) {
        $total = 0;
        $success = 0;
        $fail = 0;
        $start = microtime(true);

        Router::where('is_active', true)->each(function ($router) use ($snapshots, $correlator, &$total, &$success, &$fail) {
            foreach ($router->truthSources()->where('enabled', true)->get() as $source) {
                $total++;
                $this->info("Polling router: {$router->name} ({$router->management_ip}) source: {$source->source}");
                try {
                    $snapshots->capture($router, $source->source, $correlator);
                    $this->info("✔ Success");
                    $success++;
                } catch (\Throwable $e) {
                    $this->error("✖ Failed: " . $e->getMessage());
                    $fail++;
                }
            }
        });

        $duration = round(microtime(true) - $start, 2);
        $this->line("");
        $this->info("Polling complete. Total: $total, Success: $success, Failed: $fail, Duration: {$duration}s");
    }
}
