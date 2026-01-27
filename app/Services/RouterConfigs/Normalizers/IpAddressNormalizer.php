<?

namespace App\Services\RouterConfigs\Normalizers;

use App\Models\RouterInterface;

class IpAddressNormalizer
{
    public function normalize(array $rows, int $routerId): array
    {
        return collect($rows)->map(function ($r) use ($routerId) {
            $iface = RouterInterface::where('router_id', $routerId)
                ->where('name', $r['interface'])
                ->first();

            if (! $iface) return null;

            return [
                'router_id' => $routerId,
                'router_interface_id' => $iface->id,
                'address' => $r['address'],
                'network' => $r['network'],
                'vrf' => $r['vrf'] ?? null,
                'attributes' => $r,
            ];
        })->filter()->all();
    }
}
