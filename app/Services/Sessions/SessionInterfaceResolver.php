<?

namespace App\Services\Sessions;

use App\Models\NetworkSession;
use App\Models\RouterInterface;

class SessionInterfaceResolver
{
    public function resolve(NetworkSession $session): void
    {
        if (! $session->interface_name || $session->router_interface_id) {
            return;
        }

        $iface = RouterInterface::where('router_id', $session->router_id)
            ->where('name', $session->interface_name)
            ->first();

        if ($iface) {
            $session->update([
                'router_interface_id' => $iface->id,
            ]);
        }
    }
}
