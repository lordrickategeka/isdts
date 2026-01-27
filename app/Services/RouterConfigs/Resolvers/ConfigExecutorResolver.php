<?

namespace App\Services\RouterConfigs\Resolvers;

use App\Services\RouterConfigs\Executors\{
    InterfaceExecutor, BridgeExecutor, BridgePortExecutor,
    VlanExecutor, IpAddressExecutor
};

class ConfigExecutorResolver
{
    public static function resolve(string $target)
    {
        return match ($target) {
            'interfaces' => new InterfaceExecutor(),
            'bridges' => new BridgeExecutor(),
            'bridge_ports' => new BridgePortExecutor(),
            'vlans' => new VlanExecutor(),
            'ip_addresses' => new IpAddressExecutor(),
            default => null,
        };
    }
}
