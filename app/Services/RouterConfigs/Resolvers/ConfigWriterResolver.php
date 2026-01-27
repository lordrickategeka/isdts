<?

namespace App\Services\RouterConfigs\Resolvers;

use App\Services\RouterConfigs\Writers\{
    InterfaceConfigWriter, BridgeConfigWriter,
    BridgePortConfigWriter, VlanConfigWriter,
    IpAddressConfigWriter
};

class ConfigWriterResolver
{
    public static function resolve(string $target)
    {
        return match ($target) {
            'interfaces' => new InterfaceConfigWriter(),
            'bridges' => new BridgeConfigWriter(),
            'bridge_ports' => new BridgePortConfigWriter(),
            'vlans' => new VlanConfigWriter(),
            'ip_addresses' => new IpAddressConfigWriter(),
            default => null,
        };
    }
}
