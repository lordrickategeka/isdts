<?

namespace App\Services\RouterConfigs\Writers;

use App\Models\RouterBridgePort;

class BridgePortConfigWriter
{
    public function reconcile(int $routerId, array $current)
    {
        foreach ($current as $row) {
            RouterBridgePort::updateOrCreate(
                [
                    'router_bridge_id' => $row['router_bridge_id'],
                    'router_interface_id' => $row['router_interface_id'],
                ],
                $row
            );
        }
    }
}

