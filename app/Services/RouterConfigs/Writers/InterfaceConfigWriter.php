<?

namespace App\Services\RouterConfigs\Writers;

use App\Models\RouterInterface;

class InterfaceConfigWriter
{
    public function reconcile(int $routerId, array $current)
    {
        $names = collect($current)->pluck('name');

        foreach ($current as $row) {
            RouterInterface::updateOrCreate(
                ['router_id' => $routerId, 'name' => $row['name']],
                $row
            );
        }

        RouterInterface::where('router_id', $routerId)
            ->whereNotIn('name', $names)
            ->update(['disabled' => true]);
    }
}
