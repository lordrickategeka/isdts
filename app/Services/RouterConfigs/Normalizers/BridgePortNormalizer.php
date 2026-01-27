<?

namespace App\Services\RouterConfigs\Normalizers;

use App\Models\RouterInterface;
use App\Models\RouterBridge;

class BridgePortNormalizer
{
    public function normalize(array $rows, int $routerId): array
    {
        return collect($rows)->map(function ($r) use ($routerId) {
            $iface = RouterInterface::where('router_id', $routerId)
                ->where('name', $r['interface'])
                ->first();

            $bridge = RouterBridge::where('router_id', $routerId)
                ->where('name', $r['bridge'])
                ->first();

            if (! $iface || ! $bridge) return null;

            return [
                'router_bridge_id' => $bridge->id,
                'router_interface_id' => $iface->id,
                'disabled' => ($r['disabled'] ?? 'false') === 'true',
                'attributes' => $r,
            ];
        })->filter()->all();
    }
}

