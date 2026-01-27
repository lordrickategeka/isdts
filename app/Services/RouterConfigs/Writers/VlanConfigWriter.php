<?

namespace App\Services\RouterConfigs\Writers;

use App\Models\RouterVlan;

class VlanConfigWriter
{
    public function reconcile(int $routerId, array $current)
    {
        foreach ($current as $row) {
            RouterVlan::updateOrCreate(
                [
                    'router_id' => $routerId,
                    'vlan_id' => $row['vlan_id'],
                    'parent_interface_id' => $row['parent_interface_id'],
                ],
                $row
            );
        }
    }
}
