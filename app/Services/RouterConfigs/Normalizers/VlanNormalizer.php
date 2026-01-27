<?

namespace App\Services\RouterConfigs\Normalizers;

use App\Models\RouterInterface;

class VlanNormalizer
{
    public function normalize(array $rows, int $routerId): array
    {
        return collect($rows)->map(function ($r) use ($routerId) {
            $parent = RouterInterface::where('router_id', $routerId)
                ->where('name', $r['interface'])
                ->first();

            return [
                'router_id' => $routerId,
                'name' => $r['name'] ?? null,
                'vlan_id' => (int) $r['vlan-id'],
                'parent_interface_id' => $parent?->id,
                'attributes' => $r,
            ];
        })->all();
    }
}
