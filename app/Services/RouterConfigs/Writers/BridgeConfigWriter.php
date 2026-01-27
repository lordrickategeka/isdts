<?

namespace App\Services\RouterConfigs\Writers;

use App\Models\RouterBridge;

class BridgeConfigWriter
{
    public function reconcile(int $routerId, array $current)
    {
        foreach ($current as $row) {
            RouterBridge::updateOrCreate(
                ['router_id' => $routerId, 'name' => $row['name']],
                $row
            );
        }
    }
}
