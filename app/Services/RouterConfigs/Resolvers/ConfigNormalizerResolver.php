<?

namespace App\Services\RouterConfigs\Resolvers;

use App\Services\RouterConfigs\Normalizers\{
    InterfaceNormalizer, BridgeNormalizer, BridgePortNormalizer,
    VlanNormalizer, IpAddressNormalizer
};

class ConfigNormalizerResolver
{
    public static function resolve(string $target)
    {
        return match ($target) {
            'interfaces' => new InterfaceNormalizer(),
            'bridges' => new BridgeNormalizer(),
            'bridge_ports' => new BridgePortNormalizer(),
            'vlans' => new VlanNormalizer(),
            'ip_addresses' => new IpAddressNormalizer(),
            default => null,
        };
    }
}

