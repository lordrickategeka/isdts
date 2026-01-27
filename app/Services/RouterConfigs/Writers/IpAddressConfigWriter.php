<?

namespace App\Services\RouterConfigs\Writers;

use App\Models\RouterIpAddress;

class IpAddressConfigWriter
{
    public function reconcile(int $routerId, array $current)
    {
        foreach ($current as $row) {
            RouterIpAddress::updateOrCreate(
                [
                    'router_id' => $routerId,
                    'router_interface_id' => $row['router_interface_id'],
                    'address' => $row['address'],
                ],
                $row
            );
        }
    }
}

